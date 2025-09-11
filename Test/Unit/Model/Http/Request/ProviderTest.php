<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Test\Unit\Model\Http\Request;

use Klarna\Base\Test\Unit\Mock\MockFactory;
use Klarna\Base\Test\Unit\Mock\TestObjectFactory;
use Klarna\KlarnaApi\Model\Exception as KlarnaApiException;
use Klarna\KlarnaApi\Model\Http\Request\Provider;
use Magento\Store\Api\Data\StoreInterface;
use PHPUnit\Framework\TestCase;
use Magento\Store\Model\Store;

/**
 * @coversDefaultClass \Klarna\KlarnaApi\Model\Http\Request\Provider
 */
class ProviderTest extends TestCase
{
    /**
     * @var Provider
     */
    private $provider;
    /**
     * @var array
     */
    private array $dependencyMocks;
    /**
     * @var StoreInterface
     */
    private $store;

    public function testGetApiKeyReturnsValue(): void
    {
        $expected = '123';
        $this->dependencyMocks['apiConfiguration']->method('getPassword')
            ->willReturn($expected);

        $this->provider->setStore($this->store);
        $this->provider->setCurrency('EUR');
        static::assertEquals($expected, $this->provider->getApiKey());
    }

    public function testGetApiKeyNoStoreSetImpliesThrowingException(): void
    {
        $this->provider->setCurrency('EUR');

        $this->expectException(KlarnaApiException::class);
        $this->provider->getApiKey();
    }

    public function testGetApiKeyNoCurrencySetImpliesThrowingException(): void
    {
        $this->provider->setStore($this->store);

        $this->expectException(KlarnaApiException::class);
        $this->provider->getApiKey();
    }

    public function testGetApiKeyNoStoreSetAndNoCurrencySetImpliesThrowingException(): void
    {
        $this->expectException(KlarnaApiException::class);
        $this->provider->getApiKey();
    }

    protected function setUp(): void
    {
        $mockFactory = new MockFactory($this);
        $objectFactory = new TestObjectFactory($mockFactory);
        $this->provider = $objectFactory->create(Provider::class);
        $this->dependencyMocks = $objectFactory->getDependencyMocks();

        $this->store = $mockFactory->create(Store::class);
        $this->store->method('getBaseCurrencyCode')
            ->willReturn('EUR');
    }
}
