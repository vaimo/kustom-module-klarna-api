<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Model\Http\Request;

use Klarna\AdminSettings\Model\Configurations\Api;
use Klarna\Base\Api\ServiceInterface;
use Klarna\KlarnaApi\Model\Exception as KlarnaApiException;
use Klarna\KlarnaApi\Model\Http\Request\Builder\Body;
use Klarna\KlarnaApi\Model\Http\Request\Builder\Headers;
use Klarna\KlarnaApi\Model\Http\Request\Builder\Url;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;

/**
 * @internal
 */
class Provider
{
    /**
     * @var RequestFactory
     */
    private RequestFactory $requestFactory;
    /**
     * @var Api
     */
    private Api $apiConfiguration;
    /**
     * @var RequestLogger
     */
    private RequestLogger $requestLogger;
    /**
     * @var StoreInterface
     */
    private ?StoreInterface $store = null;
    /**
     * @var string
     */
    private string $currency = '';

    /**
     * @param Api $apiConfiguration
     * @param RequestFactory $requestFactory
     * @param RequestLogger $requestLogger
     * @codeCoverageIgnore
     */
    public function __construct(
        Api $apiConfiguration,
        RequestFactory $requestFactory,
        RequestLogger $requestLogger
    ) {
        $this->apiConfiguration = $apiConfiguration;
        $this->requestFactory = $requestFactory;
        $this->requestLogger = $requestLogger;
    }

    /**
     * Setting the store
     *
     * @param StoreInterface $store
     * @return void
     */
    public function setStore(StoreInterface $store)
    {
        $this->store = $store;
    }

    /**
     * Setting the currency
     *
     * @param string $currency
     * @return void
     */
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
    }

    /**
     * Getting back the API key
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getApiKey(): string
    {
        if ($this->store === null || $this->currency === '') {
            throw new KlarnaApiException(__('Store or currency not set'));
        }

        return $this->apiConfiguration->getPassword($this->store, $this->currency);
    }

    /**
     * Prepares a Klarna API request
     *
     * @param Url $urlBuilder
     * @param Body $bodyBuilder
     * @param Headers $headersBuilder
     * @param string $httpMethod
     * @param string $service
     * @param string $action
     * @return Request
     * @throws KlarnaApiException|NoSuchEntityException
     */
    public function prepareRequest(
        Url $urlBuilder,
        Body $bodyBuilder,
        Headers $headersBuilder,
        string $httpMethod,
        string $service,
        string $action
    ): Request {
        try {
            $httpMethod = strtolower($httpMethod);
            if (!in_array($httpMethod, ServiceInterface::REQUEST_METHODS)) {
                throw new KlarnaApiException(__('Invalid HTTP method for Klarna API request'));
            }
            /** @var RequestInterface $request */
            $request = $this->requestFactory->create();

            $apiKey = $this->getApiKey();
            $request->setApiKey($apiKey);
            $headersBuilder->addAuthorizationHeader($apiKey);

            $request->setHeaders($headersBuilder->getHeaders());
            $request->setUrl($urlBuilder->getUrl());
            $request->setBody($bodyBuilder->getBody());
            $request->setHttpMethod($httpMethod);

            $this->requestLogger->requestLogging(
                $request,
                $service,
                $action
            );
            return $request;
        } catch (NoSuchEntityException $e) {
            throw new NoSuchEntityException(__('Could not get API key: ' . $e->getMessage()));
        } catch (KlarnaApiException $e) {
            throw new KlarnaApiException(__('Preparing request failed: ' . $e->getMessage()));
        }
    }
}
