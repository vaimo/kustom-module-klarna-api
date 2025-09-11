<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Model\Http\Request;

/**
 * @internal
 */
abstract class RequestAbstract implements RequestInterface
{
    /**
     * @var array
     */
    private array $body = [];
    /**
     * @var array
     */
    private array $headers = [];
    /**
     * @var string
     */
    private string $url = '';
    /**
     * @var string
     */
    private string $apiKey = '';
    /**
     * @var string
     */
    private string $username = '';
    /**
     * @var string
     */
    private string $password = '';
    /**
     * @var string
     */
    private string $httpMethod = '';

    /**
     * @inheritDoc
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @inheritDoc
     */
    public function setBody(array $body): void
    {
        $this->body = $body;
    }

    /**
     * @inheritDoc
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @inheritDoc
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * Setting the API ID
     *
     * @param string $apiKey
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Getting back the API ID
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Getting back the user name
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Setting the user name
     *
     * @param string $username
     * @return void
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * Getting back the password
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Setting the password
     *
     * @param string $password
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @inheritDoc
     */
    public function getRequestData(): array
    {
        $result = [
            'headers' => $this->headers,
            'json'    => $this->body
        ];

        if (empty($this->getApiKey())) {
            $result['auth'] = [$this->getUsername(), $this->getPassword()];
        } else {
            $result['auth'] = [$this->getApiKey(), ''];
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    /**
     * @inheritDoc
     */
    public function setHttpMethod(string $httpMethod): void
    {
        $this->httpMethod = $httpMethod;
    }
}
