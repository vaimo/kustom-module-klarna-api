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
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures\SuccessResponse;
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Result;
use PHPUnit\Framework\TestCase;
use Magento\Store\Model\Store;

/**
 * @coversDefaultClass \Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Result
 */
class ResultTest extends TestCase
{
    /**
     * @var Result
     */
    private Result $result;
    /**
     * @var array
     */
    private array $dependencyMocks;
    /**
     * @var Store
     */
    private Store $store;
    /**
     * @var SuccessResponse
     */
    private SuccessResponse $successResponse;
    /**
     * @var FailureResponse
     */
    private FailureResponse $failureResponse;

    public function testGetKlarnaInstallationIdReturnsFromClass(): void
    {
        $this->result->setKlarnaInstallationId('test');
        static::assertEquals('test', $this->result->getKlarnaInstallationId());
    }

    public function testGetKlarnaInstallationIdReturnsFromApiResponse(): void
    {
        $this->result->setKlarnaInstallationId('test');
        $this->result->setResponse($this->successResponse);
        $this->successResponse->method('getBody')
            ->willReturn(['plugin_installation_id' => 'test2']);

        static::assertEquals('test2', $this->result->getKlarnaInstallationId());
    }

    public function testGetStoreIdScopeIsDefaultImpliesReturningZero(): void
    {
        $this->store->method('getScope')
            ->willReturn('default');
        $this->result->setStore($this->store);
        static::assertEquals(0, $this->result->getStoreId());
    }

    public function testGetStoreIdScopeIsNotDefaultImpliesReturningWebsiteId(): void
    {
        $this->store->method('getScope')
            ->willReturn('aaa');
        $this->store->method('getWebsiteId')
            ->willReturn(1);

        $this->result->setStore($this->store);
        static::assertEquals(1, $this->result->getStoreId());
    }

    public function testGetApiAvailableMarketsReturningValue(): void
    {
        $this->successResponse->method('getBody')
            ->willReturn(['available_markets' => ['DE']]);
        $this->result->setResponse($this->successResponse);
        static::assertEquals(['DE'], $this->result->getApiAvailableMarkets());
    }

    public function testGetApiFeaturesReturningValue(): void
    {
        $this->successResponse->method('getBody')
            ->willReturn(['features' => ['DE']]);
        $this->result->setResponse($this->successResponse);
        static::assertEquals(['DE'], $this->result->getApiFeatures());
    }

    public function testIsValidResponseResponseIsFailureResponseImpliesReturningFalse(): void
    {
        $this->result->setResponse($this->failureResponse);
        static::assertFalse($this->result->isValidResponse());
    }

    public function testIsValidResponseResponseIsSuccessResponseButNotSuccessfulImpliesReturningFalse(): void
    {
        $this->successResponse->method('isSuccessful')
            ->willReturn(false);

        $this->result->setResponse($this->successResponse);
        static::assertFalse($this->result->isValidResponse());
    }

    public function testIsValidResponseValidResponseAndCompleteResponseDataImpliesReturningTrue(): void
    {
        $this->successResponse->method('isSuccessful')
            ->willReturn(true);
        $this->successResponse->method('getBody')
            ->willReturn(['plugin_installation_id' => [], 'features' => [], 'available_markets' => []]);

        $this->result->setResponse($this->successResponse);
        static::assertTrue($this->result->isValidResponse());
    }

    public function testIsValidResponseValidResponseButMissingPluginsInstallationIdDataImpliesReturningFalse(): void
    {
        $this->successResponse->method('isSuccessful')
            ->willReturn(true);
        $this->successResponse->method('getBody')
            ->willReturn(['features' => [], 'available_markets' => []]);

        $this->result->setResponse($this->successResponse);
        static::assertFalse($this->result->isValidResponse());
    }

    public function testIsValidResponseValidResponseButMissingFeaturesDataImpliesReturningFalse(): void
    {
        $this->successResponse->method('isSuccessful')
            ->willReturn(true);
        $this->successResponse->method('getBody')
            ->willReturn(['plugin_installation_id' => [], 'available_markets' => []]);

        $this->result->setResponse($this->successResponse);
        static::assertFalse($this->result->isValidResponse());
    }

    public function testIsValidResponseValidResponseButMissingAvailableMarketsDataImpliesReturningFalse(): void
    {
        $this->successResponse->method('isSuccessful')
            ->willReturn(true);
        $this->successResponse->method('getBody')
            ->willReturn(['plugin_installation_id' => [], 'features' => []]);

        $this->result->setResponse($this->successResponse);
        static::assertFalse($this->result->isValidResponse());
    }

    protected function setUp(): void
    {
        $mockFactory = new MockFactory($this);
        $objectFactory = new TestObjectFactory($mockFactory);

        $this->result = $objectFactory->create(Result::class);
        $this->dependencyMocks = $objectFactory->getDependencyMocks();

        $this->store = $mockFactory->create(Store::class, ['getWebsiteId'], ['getScope']);

        $this->successResponse = $mockFactory->create(SuccessResponse::class);
        $this->failureResponse = $mockFactory->create(FailureResponse::class);
    }
}