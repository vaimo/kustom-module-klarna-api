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
class Body
{
    /**
     * @var array
     */
    private array $body;

    /**
     * @param array $body
     * @codeCoverageIgnore
     */
    public function __construct(array $body = [])
    {
        $this->body = $body;
    }

    /**
     * Adds a key-value pair to the body for the request, will overwrite existing values
     * if the key already exists, supports nested keys using arrow notation
     * e.g. 'key->subkey->subsubkey' for adding values to nested arrays
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function add(string $key, mixed $value): self
    {
        if (!str_contains($key, '->')) {
            $this->body[$key] = $value;
            return $this;
        }
        $keys = explode('->', $key);
        // Reference to the $current to the $this->body array
        $current = &$this->body;

        // Traverse the array to the correct depth
        foreach ($keys as $k) {
            // If the key doesn't exist, create it with an empty array as value
            if (!isset($current[$k])) {
                $current[$k] = [];
            }
            $current = &$current[$k];
        }

        // Set the value to the traversed key
        $current = $value;
        return $this;
    }

    /**
     * Returns the built request body
     *
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }
}
