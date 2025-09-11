<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2;

use Klarna\AdminSettings\Model\AdminLevel;
use Klarna\AdminSettings\Model\Configurations\Api;
use Klarna\AdminSettings\Model\MarketCurrencyMapper;
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures\Factory;
use Klarna\KlarnaApi\Model\Rest\Client;
use Klarna\PluginsApi\Model\Update\Api\ContainerFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreFactory;
use Random\RandomException;

/**
 * @internal
 */
class Service
{
    /**
     * @var Builder
     */
    private Builder $builder;

    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var Factory
     */
    private Factory $factory;

    /**
     * @var ResultFactory
     */
    private ResultFactory $resultFactory;
    /**
     * @var AdminLevel
     */
    private AdminLevel $adminLevel;
    /**
     * @var Api
     */
    private Api $api;
    /**
     * @var StoreFactory
     */
    private StoreFactory $storeFactory;
    /**
     * @var MarketCurrencyMapper
     */
    private MarketCurrencyMapper $marketCurrencyMapper;
    /**
     * @var InstallationId
     */
    private InstallationID $installationId;

    /**
     * @param Builder $builder
     * @param Client $client
     * @param Factory $factory
     * @param ResultFactory $resultFactory
     * @param AdminLevel $adminLevel
     * @param Api $api
     * @param StoreFactory $storeFactory
     * @param MarketCurrencyMapper $marketCurrencyMapper
     * @param InstallationId $installationId
     * @codeCoverageIgnore
     */
    public function __construct(
        Builder $builder,
        Client $client,
        Factory $factory,
        ResultFactory $resultFactory,
        AdminLevel $adminLevel,
        Api $api,
        StoreFactory $storeFactory,
        MarketCurrencyMapper $marketCurrencyMapper,
        InstallationId $installationId
    ) {
        $this->builder = $builder;
        $this->client = $client;
        $this->factory = $factory;
        $this->resultFactory = $resultFactory;
        $this->adminLevel = $adminLevel;
        $this->api = $api;
        $this->storeFactory = $storeFactory;
        $this->marketCurrencyMapper = $marketCurrencyMapper;
        $this->installationId = $installationId;
    }

    /**
     * Prepares and sends the Plugins API Features POST request
     *
     * @return array
     * @throws NoSuchEntityException|LocalizedException|RandomException
     */
    public function sendPluginsApiPostRequest(): array
    {
        $storeId = $this->adminLevel->getStoreId();
        $scope = $this->adminLevel->getScope();

        $store = $this->storeFactory->create()->load($storeId);
        $store->setScope($scope);

        $result = [];
        foreach ($this->api->getAllEnabledMarkets($store) as $markets) {
            foreach ($markets as $market) {
                if ($this->api->getPassword($store, $this->marketCurrencyMapper->getCurrencyByMarket($market))
                    === '') {
                    continue;
                }

                $pluginInstallationId = $this->installationId->get($scope, $storeId, $market);

                /** @var Result $resultContainer */
                $resultContainer = $this->resultFactory->create();
                $resultContainer->setStore($store);
                $resultContainer->setKlarnaInstallationId($pluginInstallationId);
                $resultContainer->setMarket($market);

                $request = $this->builder->preparePluginsFeaturesPostRequest($resultContainer);
                $resultContainer->setRequestPayLoad($request->getBody());

                $response = $this->client->sendRequest($request, $this->factory);

                $resultContainer->setResponse($response);
                if ($resultContainer->isValidResponse()) {
                    $result[$market] = $resultContainer;
                }
            }
        }

        return $result;
    }
}
