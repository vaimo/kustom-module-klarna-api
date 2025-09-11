<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Test\Unit\Model\Http\Request;

use Klarna\Base\Test\Unit\Mock\MockFactory;
use Klarna\Base\Test\Unit\Mock\TestObjectFactory;
use Klarna\KlarnaApi\Model\Http\Request\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var array
     */
    private array $headers = ['Content-Type: application/json'];

    /**
     * @var array
     */
    private array $body = ['key' => 'value'];
    /**
     * @var string
     */
    private string $apiKey = 'api_key';
    /**
     * @var string
     */
    private string $password = 'api_password';
    /**
     * @var string
     */
    private string $username = 'api_username';

    public function testSetAndGetBody(): void
    {
        $this->assertSame($this->body, $this->request->getBody());
    }

    public function testSetAndGetHeaders(): void
    {
        $this->assertSame($this->headers, $this->request->getHeaders());
    }

    public function testSetAndGetUrl(): void
    {
        $expected = 'https://example.com';
        $this->request->setUrl($expected);
        $this->assertSame($expected, $this->request->getUrl());
    }

    public function testSetAndGetApiKey(): void
    {
        $this->request->setApiKey($this->apiKey);
        $this->assertSame($this->apiKey, $this->request->getApiKey());
    }

    public function testSetAndGetUsername(): void
    {
        $this->assertSame($this->username, $this->request->getUsername());
    }

    public function testSetAndGetPassword(): void
    {
        $this->assertSame($this->password, $this->request->getPassword());
    }

    public function testGetRequestDataWithApiKey(): void
    {
        $this->request->setApiKey($this->apiKey);

        $expected = [
            'headers' => $this->headers,
            'json' => $this->body,
            'auth' => [$this->apiKey, '']
        ];

        $this->assertSame($expected, $this->request->getRequestData());
    }

    public function testGetRequestDataWithApiUsernameAndPassword(): void
    {
        $expected = [
            'headers' => $this->headers,
            'json' => $this->body,
            'auth' => [$this->username, $this->password]
        ];

        $this->assertSame($expected, $this->request->getRequestData());
    }

    protected function setUp(): void
    {
        $mockFactory = new MockFactory($this);
        $objectFactory = new TestObjectFactory($mockFactory);
        $this->request = $objectFactory->create(Request::class);

        $this->request->setBody($this->body);
        $this->request->setHeaders($this->headers);
        $this->request->setUsername($this->username);
        $this->request->setPassword($this->password);
    }
}
