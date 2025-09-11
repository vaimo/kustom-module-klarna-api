<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Test\Api\Model\Endpoints\PluginsApi\V2;

use Klarna\Base\Test\Integration\Helper\ApiRequestTestCase;
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Result;
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Service;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @internal
 */
class ServiceTest extends ApiRequestTestCase
{
    /**
     * @var Service
     */
    private $service;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @magentoConfigFixture current_store general/country/default DE
     * @magentoConfigFixture current_store general/store_information/country_id DE
     * @magentoConfigFixture current_store general/store_information/region_id 82
     * @magentoConfigFixture current_store tax/defaults/country DE
     * @magentoConfigFixture current_store tax/calculation/price_includes_tax 1
     * @magentoConfigFixture current_store tax/calculation/shipping_includes_tax 1
     * @magentoConfigFixture current_store tax/calculation/discount_tax 1
     * @magentoConfigFixture current_store tax/display/shipping 2
     * @magentoConfigFixture current_store tax/display/type 2
     * @magentoConfigFixture current_store shipping/origin/country_id DE
     * @magentoConfigFixture current_store shipping/origin/region_id 82
     * @magentoConfigFixture current_store tax/display/shipping 2
     * @magentoConfigFixture current_store tax/display/type 2
     * @magentoConfigFixture current_store tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture default/currency/options/base EUR
     * @magentoConfigFixture default/currency/options/default EUR
     * @magentoConfigFixture default/currency/options/allow EUR
     * @magentoConfigFixture current_store general/locale/code de_DE
     *
     * @magentoConfigFixture current_store klarna/api/region eu
     * @magentoConfigFixture current_store klarna/api_eu/api_mode 1
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testSuccessfulSendPluginsApiPostRequest(): void
    {
        $store = $this->storeManager->getStore();
        $this->configureKlarnaCredentials($store, 'eu');

        $response = $this->service->sendPluginsApiPostRequest();
        /** @var Result $item */
        $item = $response['eu'];

        static::assertTrue($item->isValidResponse());
        static::assertTrue(!empty($item->getApiAvailableMarkets()));
        static::assertTrue(!empty($item->getApiFeatures()));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->objectManager->get(Service::class);
        $this->storeManager = $this->objectManager->get(StoreManagerInterface::class);
    }
}
