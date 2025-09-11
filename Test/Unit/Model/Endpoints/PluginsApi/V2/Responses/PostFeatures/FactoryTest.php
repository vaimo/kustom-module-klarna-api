<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Test\Unit\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures;

use Klarna\Base\Test\Unit\Mock\MockFactory;
use Klarna\Base\Test\Unit\Mock\TestObjectFactory;
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures\Factory;
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures\FailureResponse;
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures\SuccessResponse;
use Klarna\KlarnaApi\Model\Http\Response\FailureResponseAbstract;
use Klarna\KlarnaApi\Model\Http\Response\SuccessResponseAbstract;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Klarna\KlarnaApi\Test\Unit\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures\Factory
 */
class FactoryTest extends TestCase
{
    /**
     * @var Factory
     */
    private Factory $factory;
    /**
     * @var array
     */
    private array $dependencyMocks;
    /**
     * @var FailureResponseAbstract
     */
    private FailureResponseAbstract $failureResponse;
    /**
     * @var SuccessResponseAbstract
     */
    private SuccessResponseAbstract $successResponse;

    public function testCreateFailureInstanceReturnsFailureInstance(): void
    {
        $this->dependencyMocks['failureResponseFactory']->method('create')
            ->willReturn($this->failureResponse);
        static::assertSame($this->failureResponse, $this->factory->createFailureInstance());
    }

    public function testCreateSuccessInstanceReturnsSuccessInstance(): void
    {
        $this->dependencyMocks['successResponseFactory']->method('create')
            ->willReturn($this->successResponse);
        static::assertSame($this->successResponse, $this->factory->createSuccessInstance());
    }

    protected function setUp(): void
    {
        $mockFactory = new MockFactory($this);
        $objectFactory = new TestObjectFactory($mockFactory);
        $this->factory = $objectFactory->create(Factory::class);
        $this->dependencyMocks = $objectFactory->getDependencyMocks();

        $this->failureResponse = $mockFactory->create(FailureResponse::class);
        $this->successResponse = $mockFactory->create(SuccessResponse::class);
    }
}
