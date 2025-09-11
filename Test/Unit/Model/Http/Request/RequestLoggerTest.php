<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Test\Unit\Model\Http\Request;

use Exception;
use GuzzleHttp\Exception\RequestException;
use Klarna\Base\Test\Unit\Mock\MockFactory;
use Klarna\Base\Test\Unit\Mock\TestObjectFactory;
use Klarna\KlarnaApi\Model\Http\Request\Request;
use Klarna\KlarnaApi\Model\Http\Request\RequestLogger;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Klarna\KlarnaApi\Model\Http\Request\RequestLogger
 */
class RequestLoggerTest extends TestCase
{
    /**
     * @var RequestLogger
     */
    private $requestLogger;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Exception
     */
    private $exception;
    /**
     * @var RequestException
     */
    private $requestException;
    /**
     * @var array
     */
    private array $dependencyMocks;

    public function testRequestLogging(): void
    {
        $service = 'Klarna Test Service';
        $action = 'Test Action';
        $httpMethod = 'post';
        $url = 'https://test.com';
        $reqData = [
            'headers' => [
                'Content-type' => 'application/json'
            ],
            'json' => [
                'key' => 'value'
            ],
            'auth' => ['auth_username', 'auth_password']
        ];
        $this->request->expects($this->once())
            ->method('getRequestData')
            ->willReturn($reqData);
        $this->request->expects($this->once())
            ->method('getHttpMethod')
            ->willReturn($httpMethod);
        $this->request->expects($this->once())
            ->method('getUrl')
            ->willReturn($url);
        $this->dependencyMocks['loggerContainer']->expects($this->once())
            ->method('setRequest')
            ->with($reqData['json']);
        $this->dependencyMocks['loggerContainer']->expects($this->once())
            ->method('setMethod')
            ->with($httpMethod);
        $this->dependencyMocks['loggerContainer']->expects($this->once())
            ->method('setAction')
            ->with($action);
        $this->dependencyMocks['loggerContainer']->expects($this->once())
            ->method('setUrl')
            ->with($url);
        $this->dependencyMocks['loggerContainer']->expects($this->once())
            ->method('setService')
            ->with($service);

        $this->requestLogger->requestLogging($this->request, $service, $action);
    }

    public function testLogErrorException(): void
    {
        $this->dependencyMocks['logger']->expects($this->once())
            ->method('log')
            ->with('error', $this->exception);
        $this->requestLogger->logError($this->exception);
    }

    public function testLogErrorRequestException(): void
    {
        $this->dependencyMocks['logger']->expects($this->once())
            ->method('log')
            ->with('error', $this->requestException);
        $this->requestLogger->logError($this->requestException);
    }

    public function testLogContainer(): void
    {
        $this->dependencyMocks['apiLogger']->expects($this->once())
            ->method('logContainer');
        $this->requestLogger->logContainer();
    }

    public function testSetResponse(): void
    {
        $expected = ['key_1' => 123, 'key_2' => 'value_2'];

        $this->dependencyMocks['loggerContainer']->expects($this->once())
            ->method('setResponse')
            ->with($expected);
        $this->requestLogger->setResponse($expected);
    }

    public function testLogToFile(): void
    {
        $this->dependencyMocks['loggerContainer']->expects($this->once())
            ->method('getService')
            ->willReturn('Test Service');
        $this->dependencyMocks['loggerContainer']->expects($this->once())
            ->method('getRequest')
            ->willReturn(['a' => 'b']);
        $this->dependencyMocks['loggerContainer']->expects($this->once())
            ->method('getResponse')
            ->willReturn(['c' => 'd']);
        $this->dependencyMocks['logger']->expects($this->once())
            ->method('error')
            ->with(
                'API requests failed for the following request service: ' .
                'Test Service' .
                '. Request: ' .
                '{"a":"b"}' .
                'Reason: ' .
                '{"c":"d"}'
            );
        $this->requestLogger->logToFile();
    }

    protected function setUp(): void
    {
        $mockFactory = new MockFactory($this);
        $objectFactory = new TestObjectFactory($mockFactory);
        $this->requestLogger = $objectFactory->create(RequestLogger::class);
        $this->dependencyMocks = $objectFactory->getDependencyMocks();

        $this->request = $mockFactory->create(Request::class);
        $this->requestException = $mockFactory->create(RequestException::class);
        $this->exception = $mockFactory->create(Exception::class);
    }
}
