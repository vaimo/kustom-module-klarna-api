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
interface RequestInterface
{
    /**
     * Getting back the request body
     *
     * @return array
     */
    public function getBody(): array;

    /**
     * Setting the request body
     *
     * @param array $body
     */
    public function setBody(array $body): void;

    /**
     * Getting back the request headers
     *
     * @return array
     */
    public function getHeaders(): array;

    /**
     * Setting the request headers
     *
     * @param array $headers
     */
    public function setHeaders(array $headers): void;

    /**
     * Getting back the URL
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * Setting the URL
     *
     * @param string $url
     */
    public function setUrl(string $url): void;

    /**
     * Getting back the HTTP method
     *
     * @return string
     */
    public function getHttpMethod(): string;

    /**
     * Setting the HTTP method
     *
     * @param string $httpMethod
     */
    public function setHttpMethod(string $httpMethod): void;

    /**
     * Getting back the request data
     *
     * @return array
     */
    public function getRequestData(): array;
}
