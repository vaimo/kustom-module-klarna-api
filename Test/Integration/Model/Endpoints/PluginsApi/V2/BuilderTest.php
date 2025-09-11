<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Test\Integration\Model\Endpoints\PluginsApi\V2;

use Klarna\Base\Helper\VersionInfo;
use Klarna\Base\Test\Integration\Helper\ApiRequestTestCase;
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Builder;
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Result;
use Klarna\Kp\Model\Api\Builder\Request;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @internal
 */
class BuilderTest extends ApiRequestTestCase
{
    /**
     * @var Builder
     */
    public $builder;

    /**
     * @var VersionInfo
     */
    public $versionInfo;

    /**
     * @var StoreManagerInterface
     */
    public $storeManager;

    /**
     * @var string
     */
    private $mockPluginInstallationId;
    /**
     * @var Result
     */
    private $result;

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
    public function testBuilderBuildPluginApiPostRequestBodyPlatformData(): void
    {
        $expectedBody = $this->getExpectedBodyArray();

        $results = $this->builder->buildPluginApiPostRequestBody($this->result)
            ->getBody();

        static::assertEquals($expectedBody['installation_data']['platform_data'], $results['installation_data']['platform_data']);
    }

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
    public function testBuilderBuildPluginApiPostRequestBodyKlarnaPluginData(): void
    {
        $expectedBody = $this->getExpectedBodyArray();

        $results = $this->builder->buildPluginApiPostRequestBody($this->result)
            ->getBody();

        static::assertEquals($expectedBody['installation_data']['klarna_plugin_data'], $results['installation_data']['klarna_plugin_data']);
    }

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
    public function testBuilderBuildPluginApiPostRequestBodyStoreData(): void
    {
        $expectedBody = $this->getExpectedBodyArray();

        $results = $this->builder->buildPluginApiPostRequestBody($this->result)
            ->getBody();

        static::assertEquals($expectedBody['installation_data']['store_data'], $results['installation_data']['store_data']);
    }

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
    public function testBuildPostPluginFeaturesUrl(): void
    {
        $this->configureKlarnaCredentials($this->result->getStore(), 'eu');
        $expectedUrl = 'https://api-global.test.klarna.com/v2/plugins/' . $this->mockPluginInstallationId . '/features';

        $results = $this->builder->buildPostPluginFeaturesUrl($this->result)
            ->getUrl();

        static::assertEquals($expectedUrl, $results);
    }

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
    public function testPreparePluginsFeaturesPostRequestValidHttpMethod(): void
    {
        $this->configureKlarnaCredentials($this->result->getStore(), 'eu');

        $request = $this->builder->preparePluginsFeaturesPostRequest($this->result);

        static::assertEquals('post', $request->getHttpMethod());
    }

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
    public function testPreparePluginsFeaturesPostRequestValidRequestBody(): void
    {
        $this->configureKlarnaCredentials($this->result->getStore(), 'eu');
        $expectedBody = $this->getExpectedBodyArray();

        $request = $this->builder->preparePluginsFeaturesPostRequest($this->result);

        static::assertEquals($expectedBody, $request->getBody());
    }

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
    public function testPreparePluginsFeaturesPostRequestValidUrl(): void
    {
        $this->configureKlarnaCredentials($this->result->getStore(), 'eu');
        $expectedUrl = 'https://api-global.test.klarna.com/v2/plugins/' . $this->mockPluginInstallationId . '/features';

        $request = $this->builder->preparePluginsFeaturesPostRequest($this->result);

        static::assertEquals($expectedUrl, $request->getUrl());
    }

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
    public function testPreparePluginsFeaturesPostRequestValidHeaders(): void
    {
        $this->configureKlarnaCredentials($this->result->getStore(), 'eu');
        $expectedHeaders = [
            'Content-Type' => 'application/json'
        ];

        $request = $this->builder->preparePluginsFeaturesPostRequest($this->result);

        static::assertEquals($expectedHeaders['Content-Type'], $request->getHeaders()['Content-Type']);
    }

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
    public function testPreparePluginsFeaturesPostRequestValidRequestData(): void
    {
        $this->configureKlarnaCredentials($this->result->getStore(), 'eu');
        $expectedBody = $this->getExpectedBodyArray();
        $expectedRequestData = [
            'json' => $expectedBody,
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ];

        $request = $this->builder->preparePluginsFeaturesPostRequest($this->result);

        static::assertEquals($expectedRequestData['json'], $request->getRequestData()['json']);
        static::assertEquals($expectedRequestData['headers']['Content-Type'], $request->getRequestData()['headers']['Content-Type']);
    }

    protected function getExpectedBodyArray(): array
    {
        $expectedPlatformData = [
            'platform_name' => $this->versionInfo->getMageName(),
            'platform_version' => $this->versionInfo->getMageVersion(),
            'platform_plugin_name' => $this->versionInfo::M2_KLARNA,
        ];
        $expectedKlarnaPluginData = [
            'plugin_identifier' => $this->versionInfo::M2_KLARNA,
            'plugin_version' => $this->versionInfo->getM2KlarnaVersion() ?: '0.0.0',
        ];

        $expectedStoreData = [
            'store_urls' => ['http://localhost/'],
        ];
        return [
            'installation_data' => [
                'platform_data' => $expectedPlatformData,
                'klarna_plugin_data' => $expectedKlarnaPluginData,
                'store_data' => $expectedStoreData,
            ],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->builder = $this->objectManager->get(Builder::class);
        $this->versionInfo = $this->objectManager->get(VersionInfo::class);

        $this->mockPluginInstallationId = 'b1c372dd-d46a-4ba9-8c5c-937bceaffb01';
        $this->result = $this->objectManager->get(Result::class);

        $storeManager = $this->objectManager->get(StoreManagerInterface::class);
        $store = $storeManager->getStore();
        $this->result->setStore($store);
        $this->result->setMarket('eu');
        $this->result->setKlarnaInstallationId($this->mockPluginInstallationId);
    }
}
