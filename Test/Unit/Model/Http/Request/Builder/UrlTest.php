<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Test\Unit\Model\Http\Request\Builder;

use Klarna\Base\Test\Unit\Mock\MockFactory;
use Klarna\Base\Test\Unit\Mock\TestObjectFactory;
use Klarna\KlarnaApi\Model\Http\Request\Builder\Url;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Klarna\KlarnaApi\Model\Http\Request\Builder\Url
 */
class UrlTest extends TestCase
{
    /**
     * @var Url
     */
    private $url;
    /**
     * @var StoreInterface
     */
    private $store;
    /**
     * @var array
     */
    private array $dependencyMocks;
    /**
     * @var string
     */
    private string $baseUrl = 'https://api-global.test.klarna.com/';

    public function testGetUrl(): void
    {
        $this->assertSame($this->baseUrl, $this->url->getUrl());
    }

    public function testAdd(): void
    {
        $expected = $this->baseUrl . 'path/path2/path3';
        $this->url->add('path')->add('path2')->add('path3');
        $this->assertSame($expected, $this->url->getUrl());
    }

    public function testAddButWithExtraSlashes(): void
    {
        $expected = $this->baseUrl . 'path/path2/path3';
        $this->url->add('path/')->add('/path2')->add('/path3/');
        $this->assertSame($expected, $this->url->getUrl());
    }

    protected function setUp(): void
    {
        $mockFactory = new MockFactory($this);
        $objectFactory = new TestObjectFactory($mockFactory);
        $this->url = $objectFactory->create(Url::class);
        $this->dependencyMocks = $objectFactory->getDependencyMocks();

        $this->store = $mockFactory->create(Store::class);
        $this->store->method('getBaseCurrencyCode')
            ->willReturn('EUR');
        $this->dependencyMocks['apiConfiguration']->method('getGlobalApiUrl')
            ->willReturn($this->baseUrl);
        $this->url->setStore($this->store);
        $this->url->setCurrency('EUR');
    }
}
