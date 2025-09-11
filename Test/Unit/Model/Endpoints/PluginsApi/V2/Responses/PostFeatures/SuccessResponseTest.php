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
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures\SuccessResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * @coversDefaultClass \Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures\SuccessResponse
 */
class SuccessResponseTest extends TestCase
{
    /**
     * @var SuccessResponse
     */
    private SuccessResponse $successResponse;

    /**
     * @var PsrResponseInterface
     */
    private PsrResponseInterface $psrResponse;

    /**
     * @var \Psr\Http\Message\StreamInterface
     */
    private \Psr\Http\Message\StreamInterface $body;

    public function testProcessResponseSuccess(): void
    {
        $code = 200;
        $headers = ['Content-Type' => 'application/json'];
        $reasonPhrase = 'OK';

        $this->psrResponse->method('getBody')
            ->willReturn($this->body);
        $this->psrResponse->method('getStatusCode')
            ->willReturn($code);
        $this->psrResponse->method('getHeaders')
            ->willReturn($headers);
        $this->psrResponse->method('getReasonPhrase')
            ->willReturn($reasonPhrase);

        $this->successResponse->processResponse($this->psrResponse);

        $this->assertSame($code, $this->successResponse->getCode());
        $this->assertSame($headers, $this->successResponse->getHeaders());
        $this->assertSame($reasonPhrase, $this->successResponse->getReasonPhrase());
        $this->assertSame(['key' => 'value'], $this->successResponse->getBody());
    }

    protected function setUp(): void
    {
        $mockFactory = new MockFactory($this);
        $objectFactory = new TestObjectFactory($mockFactory);

        $this->successResponse = $objectFactory->create(SuccessResponse::class);
        $this->psrResponse = $this->createMock(PsrResponseInterface::class);
        $this->body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $this->body->method('__toString')
            ->willReturn('{"key": "value"}');
    }
}
