<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Model\Http\Response;

use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * @internal
 */
abstract class ResponseAbstract implements ResponseInterface
{
    /**
     * @var array
     */
    protected array $body = [];
    /**
     * @var int
     */
    protected int $code = 0;
    /**
     * @var array
     */
    protected array $headers = [];
    /**
     * @var string
     */
    protected string $reasonPhrase = '';
    /**
     * @var array
     */
    protected array $successfulStatusCodes = ['200', '201', '202', '204'];

    /**
     * @inheritDoc
     */
    public function processResponse(null|PsrResponseInterface $response, ?RequestException $e = null): void
    {
        if ($e instanceof RequestException) {
            $response = $e->getResponse();
        }
        if (!empty($response)) {
            $this->body = json_decode($response->getBody()->__toString(), true);
            $this->code = $response->getStatusCode();
            $this->headers = $response->getHeaders();
            $this->reasonPhrase = $response->getReasonPhrase();
        }
    }

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
    public function setBody(array $body): void
    {
        $this->body = $body;
    }

    /**
     * @inheritDoc
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @inheritDoc
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
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
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @inheritDoc
     */
    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    /**
     * @inheritDoc
     */
    public function setReasonPhrase(string $reasonPhrase): void
    {
        $this->reasonPhrase = $reasonPhrase;
    }

    /**
     * @inheritDoc
     */
    public function getBasicResponseData(): array
    {
        return [
            'body' => $this->body,
            'code' => $this->code,
            'headers' => $this->headers,
            'reasonPhrase' => $this->reasonPhrase
        ];
    }

    /**
     * @inheritDoc
     */
    public function isSuccessful(?int $code = null): bool
    {
        $code = $code ?? $this->code;
        return str_starts_with((string) $code, '2');
    }

    /**
     * @inheritDoc
     */
    public function getBodyData(string $key): mixed
    {
        $body = $this->getBody();
        if (array_key_exists($key, $body)) {
            return $body[$key];
        }
        return null;
    }
}
