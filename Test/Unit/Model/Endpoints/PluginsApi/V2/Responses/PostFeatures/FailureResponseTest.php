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
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures\FailureResponse;
use Klarna\KlarnaApi\Model\Http\Response\FailureResponseAbstract;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * @coversDefaultClass \Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures\FailureResponse
 */
class FailureResponseTest extends TestCase
{
    /**
     * @var FailureResponse
     */
    private FailureResponse $failureResponse;

    /**
     * @var PsrResponseInterface
     */
    private PsrResponseInterface $psrResponse;

    /**
     * @var \Psr\Http\Message\StreamInterface
     */
    private \Psr\Http\Message\StreamInterface $body;

    /**
     * @dataProvider responseDataProvider
     */
    public function testProcessResponseFailure($code, $headers, $reasonPhrase, $body): void
    {
        $this->psrResponse->method('getBody')
            ->willReturn($this->body);
        $this->psrResponse->method('getStatusCode')
            ->willReturn($code);
        $this->psrResponse->method('getHeaders')
            ->willReturn($headers);
        $this->psrResponse->method('getReasonPhrase')
            ->willReturn($reasonPhrase);

        $this->failureResponse->processResponse($this->psrResponse);

        $this->assertSame($code, $this->failureResponse->getCode());
        $this->assertSame($headers, $this->failureResponse->getHeaders());
        $this->assertSame($reasonPhrase, $this->failureResponse->getReasonPhrase());
        $this->assertSame($body, $this->failureResponse->getBody());
    }

    public static function responseDataProvider(): array
    {
        return [
            [400, ['Content-Type' => 'application/json'], '', []],
            [401, ['Content-Type' => 'application/json'], '', []],
            [500, ['Content-Type' => 'application/json'], '', []]
        ];
    }

    protected function setUp(): void
    {
        $mockFactory = new MockFactory($this);
        $objectFactory = new TestObjectFactory($mockFactory);

        $this->failureResponse = $objectFactory->create(FailureResponse::class);
        $this->psrResponse = $this->createMock(PsrResponseInterface::class);
        $this->body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $this->body->method('__toString')
            ->willReturn('{}');
    }
}
