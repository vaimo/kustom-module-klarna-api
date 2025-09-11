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
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures\FailureResponse;
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Service;
use PHPUnit\Framework\TestCase;
use Magento\Store\Model\Store;
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Result;
use Klarna\KlarnaApi\Model\Http\Request\Request;
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures\SuccessResponse;

/**
 * @coversDefaultClass \Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Service
 */
class ServiceTest extends TestCase
{
    /**
     * @var Service
     */
    private Service $service;
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
    /**
     * @var SuccessResponse
     */
    private SuccessResponse $successResponse;
    /**
     * @var FailureResponse
     */
    private FailureResponse $failureResponse;

    public function testSendPluginsApiPostRequestNoMarketEnabledImpliesReturnsEmptyArray(): void
    {
        static::assertEquals([], $this->service->sendPluginsApiPostRequest('a'));
    }

    public function testSendPluginsApiPostRequestResponseIsInvalidImpliesReturnsEmptyArray(): void
    {
        $this->dependencyMocks['api']->method('getAllEnabledMarkets')
            ->willReturn(['eu' => ['eu']]);
        $this->dependencyMocks['client']->method('sendRequest')
            ->willReturn($this->failureResponse);
        $this->dependencyMocks['api']->method('getPassword')
            ->willReturn('123');

        $this->result->method('isValidResponse')
            ->willReturn(false);
        static::assertEquals([], $this->service->sendPluginsApiPostRequest('a'));
    }

    public function testSendPluginsApiPostRequestResponseIsValidImpliesReturnFilledArray(): void
    {
        $this->dependencyMocks['api']->method('getAllEnabledMarkets')
            ->willReturn(['eu' => ['eu']]);
        $this->dependencyMocks['client']->method('sendRequest')
            ->willReturn($this->successResponse);
        $this->dependencyMocks['api']->method('getPassword')
            ->willReturn('123');

        $this->result->method('isValidResponse')
            ->willReturn(true);
        static::assertEquals(['eu' => $this->result], $this->service->sendPluginsApiPostRequest('a'));
    }

    public function testSendPluginsApiPostRequestResponseNothingAddedToRResultSinceApiKeyIsEmpty(): void
    {
        $this->dependencyMocks['api']->method('getAllEnabledMarkets')
            ->willReturn(['eu' => ['eu']]);
        $this->dependencyMocks['client']->expects(static::never())
            ->method('sendRequest')
            ->willReturn($this->successResponse);
        $this->dependencyMocks['api']->method('getPassword')
            ->willReturn('');

        static::assertEquals([], $this->service->sendPluginsApiPostRequest('a'));
    }

    protected function setUp(): void
    {
        $mockFactory = new MockFactory($this);
        $objectFactory = new TestObjectFactory($mockFactory);

        $this->service = $objectFactory->create(Service::class);
        $this->dependencyMocks = $objectFactory->getDependencyMocks();

        $this->result = $mockFactory->create(Result::class);
        $this->dependencyMocks['resultFactory']->method('create')
            ->willReturn($this->result);

        $this->store = $mockFactory->create(Store::class);

        $this->request = $mockFactory->create(Request::class);
        $this->successResponse = $mockFactory->create(SuccessResponse::class);
        $this->failureResponse = $mockFactory->create(FailureResponse::class);
        
        $this->dependencyMocks['storeFactory']->method('create')
            ->willReturn($this->store);
        $this->store->method('load')
            ->willReturn($this->store);
    }
}