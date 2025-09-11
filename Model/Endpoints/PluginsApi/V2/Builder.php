<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2;

use Klarna\AdminSettings\Model\MarketCurrencyMapper;
use Klarna\Base\Api\ServiceInterface;
use Klarna\Base\Helper\VersionInfo;
use Klarna\KlarnaApi\Model\Http\Request\Builder\Body;
use Klarna\KlarnaApi\Model\Http\Request\Builder\Headers;
use Klarna\KlarnaApi\Model\Http\Request\Builder\Url;
use Klarna\KlarnaApi\Model\Http\Request\Provider;
use Klarna\KlarnaApi\Model\Http\Request\Request;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Klarna\KlarnaApi\Model\Exception as KlarnaApiException;

/**
 * @internal
 */
class Builder
{
    /**
     * @var Provider
     */
    private Provider $provider;

    /**
     * @var Body
     */
    private Body $bodyBuilder;

    /**
     * @var Url
     */
    private Url $urlBuilder;

    /**
     * @var VersionInfo
     */
    private VersionInfo $versionInfo;

    /**
     * @var Headers
     */
    private Headers $headers;
    /**
     * @var MarketCurrencyMapper
     */
    private MarketCurrencyMapper $marketCurrencyMapper;

    /**
     * @param Provider $provider
     * @param Body $bodyBuilder
     * @param Url $urlBuilder
     * @param VersionInfo $versionInfo
     * @param Headers $headers
     * @param MarketCurrencyMapper $marketCurrencyMapper
     * @codeCoverageIgnore
     */
    public function __construct(
        Provider $provider,
        Body $bodyBuilder,
        Url $urlBuilder,
        VersionInfo $versionInfo,
        Headers $headers,
        MarketCurrencyMapper $marketCurrencyMapper
    ) {
        $this->provider = $provider;
        $this->bodyBuilder = $bodyBuilder;
        $this->urlBuilder = $urlBuilder;
        $this->versionInfo = $versionInfo;
        $this->headers = $headers;
        $this->marketCurrencyMapper = $marketCurrencyMapper;
    }

    /**
     * Sets URL for Plugins API features request with the given plugin_installation_id
     *
     * @param Result $result
     * @return Url
     * @throws NoSuchEntityException
     */
    public function buildPostPluginFeaturesUrl(Result $result): Url
    {
        if (empty($result->getKlarnaInstallationId())) {
            throw new KlarnaApiException(__('Could not generate the URL: No Klarna installation ID was found.'));
        }
        $this->urlBuilder->setStore($result->getStore());
        $this->urlBuilder->setCurrency($this->marketCurrencyMapper->getCurrencyByMarket($result->getMarket()));

        $this->urlBuilder->clearUrl();
        $this->urlBuilder->add('v2/plugins');
        $this->urlBuilder->add($result->getKlarnaInstallationId());
        $this->urlBuilder->add('features');

        return $this->urlBuilder;
    }

    /**
     * Builds the request body for the Plugin Features POST request
     *
     * @param Result $result
     * @return Body
     * @throws KlarnaApiException
     */
    public function buildPluginApiPostRequestBody(Result $result): Body
    {
        $storeUrl = $result->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_WEB, true);

        if (empty($storeUrl)) {
            throw new KlarnaApiException(__('Could not generate request body: No store URLs were found.'));
        }

        $platformData = [
            'platform_name' => $this->versionInfo->getMageName(),
            'platform_version' => $this->versionInfo->getMageVersion(),
            'platform_plugin_name' => $this->versionInfo::M2_KLARNA,
        ];
        $klarnaPluginData = [
            'plugin_identifier' => $this->versionInfo::M2_KLARNA,
            'plugin_version' => $this->versionInfo->getM2KlarnaVersion() ?: '0.0.0',
        ];
        $storeData = [
            'store_urls' => [$storeUrl],
        ];

        $this->bodyBuilder->add('installation_data', []);
        $this->bodyBuilder->add('installation_data->platform_data', $platformData);
        $this->bodyBuilder->add('installation_data->klarna_plugin_data', $klarnaPluginData);
        $this->bodyBuilder->add('installation_data->store_data', $storeData);

        return $this->bodyBuilder;
    }

    /**
     * Prepares an Klarna Plugins Features API request
     *
     * @param Result $result
     * @return Request
     * @throws NoSuchEntityException|KlarnaApiException
     */
    public function preparePluginsFeaturesPostRequest(Result $result): Request
    {
        $url = $this->buildPostPluginFeaturesUrl($result);
        $body = $this->buildPluginApiPostRequestBody($result);
        $headers = $this->headers;
        $service = ServiceInterface::SERVICE_PA;
        $action = 'Plugin Features';

        $this->provider->setStore($result->getStore());
        $this->provider->setCurrency($this->marketCurrencyMapper->getCurrencyByMarket($result->getMarket()));

        return $this->provider->prepareRequest(
            $url,
            $body,
            $headers,
            ServiceInterface::POST,
            $service,
            $action
        );
    }
}
