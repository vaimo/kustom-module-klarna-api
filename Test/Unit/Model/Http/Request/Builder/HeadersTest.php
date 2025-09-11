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
use Klarna\KlarnaApi\Model\Http\Request\Builder\Headers;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Klarna\KlarnaApi\Model\Http\Request\Builder\Headers
 */
class HeadersTest extends TestCase
{
    /**
     * @var Headers
     */
    private $headers;
    /**
     * @var array
     */
    private array $baseHeaders = ['Content-Type' => 'application/json'];

    public function testAddAuthorizationHeader(): void
    {
        $this->headers->addAuthorizationHeader('api-key');
        $this->assertSame(
            array_merge($this->baseHeaders, ['Authorization' => 'Bearer api-key']),
            $this->headers->getHeaders()
        );
    }

    public function testAdd(): void
    {
        $this->headers->add('Header', 'Value')
            ->add('Header2', 'Value2');
        $this->assertSame(
            array_merge($this->baseHeaders, ['Header' => 'Value', 'Header2' => 'Value2']),
            $this->headers->getHeaders()
        );
    }

    public function testGetHeaders(): void
    {
        $this->assertSame($this->baseHeaders, $this->headers->getHeaders());
    }

    protected function setUp(): void
    {
        $mockFactory = new MockFactory($this);
        $objectFactory = new TestObjectFactory($mockFactory);
        $this->headers = $objectFactory->create(Headers::class);
    }
}
