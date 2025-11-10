<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Test\Unit\Model\Endpoints\PluginsApi\V2;

use Klarna\Base\Test\Unit\Mock\MockFactory;
use Klarna\Base\Test\Unit\Mock\TestObjectFactory;
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Builder;
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Result;
use PHPUnit\Framework\TestCase;
use Magento\Store\Model\Store;
use Klarna\KlarnaApi\Model\Exception as KlarnaApiException;
use Klarna\KlarnaApi\Model\Http\Request\Request;

/**
 * @coversDefaultClass \Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Builder
 */
class BuilderTest extends TestCase
{
    /**
     * @var Builder
     */
    private Builder $builder;
    /**
     * @var array
     */
    private array $dependencyMocks;
    /**
     * @var Result
     */
    private Result $result;
    /**
     * @var Store
     */
    private Store $store;
    /**
     * @var Request
     */
    private Request $request;

    public function testBuildPostPluginFeaturesUrlSettingStore(): void
    {
        $this->dependencyMocks['urlBuilder']->expects(static::once())
            ->method('setStore')
            ->with($this->store);
        $this->result->method('getKlarnaInstallationId')
            ->willReturn('13');

        $this->builder->buildPostPluginFeaturesUrl($this->result);
    }

    public function testBuildPostPluginFeaturesUrlSettingCurrency(): void
    {
        $this->dependencyMocks['marketCurrencyMapper']->method('getCurrencyByMarket')
            ->willReturn('EUR');
        $this->dependencyMocks['urlBuilder']->expects(static::once())
            ->method('setCurrency')
            ->with('EUR');
        $this->result->method('getKlarnaInstallationId')
            ->willReturn('13');

        $this->builder->buildPostPluginFeaturesUrl($this->result);
    }

    public function testBuildPluginApiPostRequestBodyNoStoreUrlConfiguredImpliesThrowingException(): void
    {
        $this->store->method('getBaseUrl')
            ->willReturn(null);

        $this->expectException(KlarnaApiException::class);

        $this->builder->buildPluginApiPostRequestBody($this->result);
    }

    public function testBuildPluginApiPostRequestBodyCheckingCorrectData(): void
    {
        $this->store->method('getBaseUrl')
            ->willReturn('https://example.com');

        $this->dependencyMocks['versionInfo']->expects($this->once())
            ->method('getMageName')
            ->willReturn('Magento');
        $this->dependencyMocks['versionInfo']->expects($this->once())
            ->method('getMageVersion')
            ->willReturn('2.4.7');
        $this->dependencyMocks['versionInfo']->expects($this->once())
            ->method('getM2KlarnaVersion')
            ->willReturn('1.1.0');
        $this->dependencyMocks['bodyBuilder']
            ->method('add')
            ->willReturnCallback(fn($key, $value) =>
                match([$key, $value]) {
                    ['installation_data', []] => $this->dependencyMocks['bodyBuilder'],
                    ['installation_data->platform_data', [
                        'platform_name' => 'Magento',
                        'platform_version' => '2.4.7',
                        'platform_plugin_name' => 'kustom/module-checkout',
                    ]] => $this->dependencyMocks['bodyBuilder'],
                    ['installation_data->klarna_plugin_data', [
                        'plugin_identifier' => 'kustom/module-checkout',
                        'plugin_version' => '1.1.0',
                    ]] => $this->dependencyMocks['bodyBuilder'],
                    ['installation_data->store_data', [
                        'store_urls' => ['https://example.com'],
                    ]] => $this->dependencyMocks['bodyBuilder']
                }
            );

        $this->builder->buildPluginApiPostRequestBody($this->result);
    }

    public function testPreparePluginsFeaturesPostRequestSettingTheStore(): void
    {
        $this->store->method('getBaseUrl')
            ->willReturn('https://example.com');

        $this->dependencyMocks['provider']->expects($this->once())
            ->method('setStore')
            ->with($this->store);
        $this->result->method('getKlarnaInstallationId')
            ->willReturn('13');

        $this->dependencyMocks['provider']->method('prepareRequest')
            ->willReturn($this->request);
        $this->builder->preparePluginsFeaturesPostRequest($this->result);
    }

    public function testPreparePluginsFeaturesPostRequestSettingTheCurrency(): void
    {
        $this->store->method('getBaseUrl')
            ->willReturn('https://example.com');

        $this->dependencyMocks['marketCurrencyMapper']->method('getCurrencyByMarket')
            ->willReturn('EUR');
        $this->dependencyMocks['provider']->expects($this->once())
            ->method('setCurrency')
            ->with('EUR');
        $this->result->method('getKlarnaInstallationId')
            ->willReturn('13');

        $this->dependencyMocks['provider']->method('prepareRequest')
            ->willReturn($this->request);
        $this->builder->preparePluginsFeaturesPostRequest($this->result);
    }

    public function testPreparePluginsFeaturesPostRequestReturnsInstance(): void
    {
        $this->store->method('getBaseUrl')
            ->willReturn('https://example.com');

        $this->dependencyMocks['provider']->expects($this->once())
            ->method('prepareRequest')
            ->willReturn($this->request);
        $this->result->method('getKlarnaInstallationId')
            ->willReturn('13');

        $this->builder->preparePluginsFeaturesPostRequest($this->result);
    }

    public function testPreparePluginsFeaturesPostRequestNoKlarnaInstallationIdSetImpliesThrowingException(): void
    {
        $this->result->method('getKlarnaInstallationId')
            ->willReturn('');
        $this->expectException(KlarnaApiException::class);

        $this->builder->preparePluginsFeaturesPostRequest($this->result);
    }

    protected function setUp(): void
    {
        $mockFactory = new MockFactory($this);
        $objectFactory = new TestObjectFactory($mockFactory);

        $this->builder = $objectFactory->create(Builder::class);
        $this->dependencyMocks = $objectFactory->getDependencyMocks();

        $this->result = $mockFactory->create(Result::class);
        $this->store = $mockFactory->create(Store::class);

        $this->result->method('getStore')
            ->willReturn($this->store);

        $this->request = $mockFactory->create(Request::class);
    }
}