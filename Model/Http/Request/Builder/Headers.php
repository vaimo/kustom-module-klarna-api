<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Model\Http\Request\Builder;

/**
 * @internal
 */
class Headers
{
    /**
     * @var array,
     */
    private array $headers = ['Content-Type' => 'application/json'];

    /**
     * Adds a key-value pair to the header for the request, will overwrite existing values
     *
     * @param string|int $key
     * @param string|int $value
     * @return $this
     */
    public function add(string|int $key, string|int $value): self
    {
        $this->headers[(string) $key] = $value;
        return $this;
    }

    /**
     * Sets the Authorization header in the Headers object
     *
     * @param string $apiKey
     * @return void
     */
    public function addAuthorizationHeader(string $apiKey): void
    {
        $this->add('Authorization', 'Bearer ' . $apiKey);
    }

    /**
     * Returns the built request header
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
