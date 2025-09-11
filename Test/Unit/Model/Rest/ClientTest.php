<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Test\Unit\Model\Rest;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Klarna\Base\Api\ServiceInterface;
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures\FailureResponse;
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures\SuccessResponse;
use Klarna\KlarnaApi\Model\Http\Request\RequestLogger;
use Klarna\KlarnaApi\Model\Http\Request\Request as KlarnaRequest;
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures\Factory;
use Klarna\KlarnaApi\Model\Http\Response\SuccessResponseAbstract;
use Klarna\KlarnaApi\Model\Rest\Client as KlarnaClient;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @coversDefaultClass \Klarna\KlarnaApi\Model\Rest\Client
 * @covers \Klarna\KlarnaApi\Model\Http\Request\RequestLogger
 */
class ClientTest extends TestCase
{
    /**
     * @var KlarnaClient
     */
    private $client;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|Client
     */
    private $guzzleClient;

    /**
     * @var Factory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $factory;

    /**
     * @var RequestLogger|\PHPUnit\Framework\MockObject\MockObject
     */
    private $requestLogger;

    /**
     * @var RequestInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $request;

    public function testSendRequestHandlesBadResponseException(): void
    {
        // Create a simulated BadResponseException
        $exception = new BadResponseException(
            "Bad Response",
            new Request(ServiceInterface::POST, 'https://localhost/'),
            new Response(400, [], 'Bad Request')
        );

        // Configure Guzzle client mock to throw BadResponseException on POST request
        $this->guzzleClient->method(ServiceInterface::POST)
            ->willThrowException($exception);

        // Expect failure response instance to be created
        $failureResponse = $this->createMock(FailureResponse::class);
        $this->factory->expects($this->once())
            ->method('createFailureInstance')
            ->willReturn($failureResponse);

        // Expect client logger to capture the error
        $this->requestLogger->expects($this->once())
            ->method('logError')
            ->with($exception);

        // Call the method under test
        $response = $this->client->sendRequest($this->request, $this->factory);

        // Assert that the response is a failure response
        $this->assertInstanceOf(FailureResponse::class, $response);
    }

    public function testSendRequestSuccessResponse(): void
    {
        $mockResponse = new Response(200, [], json_encode(['success' => true]));
        $this->guzzleClient->method(ServiceInterface::POST)
            ->willReturn($mockResponse);

        // Expect success response instance to be created
        $successResponse = $this->createMock(SuccessResponse::class);
        $this->factory->expects($this->once())
            ->method('createSuccessInstance')
            ->willReturn($successResponse);

        $this->requestLogger->expects($this->once())
            ->method('setResponse')
            ->with([]);

        // Call the method under test
        $response = $this->client->sendRequest($this->request, $this->factory);

        // Assert that the response is a success response
        $this->assertInstanceOf(SuccessResponse::class, $response);
    }

    // Had these tests differently from the other tests, since I needed to mock the Guzzle client and inject it into the class we are testing.
    protected function setUp(): void
    {
        // Mock dependencies
        $this->guzzleClient = $this->createMock(Client::class);
        $this->requestLogger = $this->createMock(RequestLogger::class);

        // Create an instance of Klarna Client with the mocked Guzzle client
        $this->client = new KlarnaClient(
            $this->guzzleClient,
            $this->requestLogger,
        );

        // Mock the request
        $this->factory = $this->createMock(Factory::class);
        $this->request = $this->createMock(KlarnaRequest::class);
        $this->request->method('getHttpMethod')
            ->willReturn(ServiceInterface::POST);
        $this->request->method('getUrl')
            ->willReturn('https://localhost/');
        $this->request->method('getRequestData')
            ->willReturn(['json' => ['key' => 'value']]);
    }
}
