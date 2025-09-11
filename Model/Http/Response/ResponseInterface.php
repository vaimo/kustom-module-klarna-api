<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Model\Http\Response;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use GuzzleHttp\Exception\RequestException;

/**
 * @internal
 */
interface ResponseInterface
{

    /**
     * Getting the basic response data
     *
     * @return array
     */
    public function getBasicResponseData(): array;

    /**
     * Getting back the reason phrase
     *
     * @return string
     */
    public function getReasonPhrase(): string;

    /**
     * Setting the reason phrase
     *
     * @param string $reasonPhrase
     * @return void
     */
    public function setReasonPhrase(string $reasonPhrase): void;

    /**
     * Getting back the headers
     *
     * @return array
     */
    public function getHeaders(): array;

    /**
     * Setting the headers
     *
     * @param array $headers
     * @return void
     */
    public function setHeaders(array $headers): void;

    /**
     * Getting back the code
     *
     * @return int
     */
    public function getCode(): int;

    /**
     * Setting the code
     *
     * @param int $code
     * @return void
     */
    public function setCode(int $code): void;

    /**
     * Getting back the body
     *
     * @return array
     */
    public function getBody(): array;

    /**
     * Setting the body
     *
     * @param array $body
     * @return void
     */
    public function setBody(array $body): void;

    /**
     * Processing the response
     *
     * @param null|PsrResponseInterface $response
     * @param RequestException|null $e
     * @return void
     */
    public function processResponse(null|PsrResponseInterface $response, ?RequestException $e = null): void;

    /**
     * Returns true if the response was successful
     *
     * @param int|null $code
     * @return bool
     */
    public function isSuccessful(?int $code = null): bool;

    /**
     * Getting a specific key/value pair in the response body data if it exists, otherwise returns null
     *
     * @param string $key
     * @return mixed
     */
    public function getBodyData(string $key): mixed;
}
