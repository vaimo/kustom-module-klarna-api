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
use Klarna\KlarnaApi\Model\Http\Request\Builder\Body;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Klarna\KlarnaApi\Model\Http\Request\Builder\Body
 */
class BodyTest extends TestCase
{
    /**
     * @var Body
     */
    private $body;
    /**
     * @var array
     */
    private array $dependencyMocks;

    public function testAdd(): void
    {
        $this->body->add('Header', 'Value')
            ->add('Header2', 'Value2');
        $this->assertSame(
            ['Header' => 'Value', 'Header2' => 'Value2'],
            $this->body->getBody()
        );
    }

    public function testAddWithWrongNestedKeyCharacter(): void
    {
        $this->body->add('key_1.key_2', 'value_2');
        $this->assertSame(
            ['key_1.key_2' => 'value_2'],
            $this->body->getBody()
        );
    }

    public function testAddWithNestedKey(): void
    {
        $this->body->add('key_1->key_2', 'value_2');
        $this->assertSame(
            ['key_1' => ['key_2' => 'value_2']],
            $this->body->getBody()
        );
    }

    public function testGetBody(): void
    {
        $this->body->add('key', 'value');
        $this->assertSame(['key' => 'value'], $this->body->getBody());
    }

    protected function setUp(): void
    {
        $mockFactory = new MockFactory($this);
        $objectFactory = new TestObjectFactory($mockFactory);
        $this->body = $objectFactory->create(Body::class);
        $this->dependencyMocks = $objectFactory->getDependencyMocks();
    }
}
