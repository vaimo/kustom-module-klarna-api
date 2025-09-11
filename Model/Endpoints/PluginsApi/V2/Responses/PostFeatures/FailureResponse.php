<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures;

use Klarna\KlarnaApi\Model\Http\Response\FailureResponseAbstract;

/**
 * @internal
 */
class FailureResponse extends FailureResponseAbstract
{
    /**
     * Retrieves the error type from the body object
     *
     * @return string
     */
    public function getErrorType(): string
    {
        return $this->getBodyData('error_type') ?? '';
    }

    /**
     * Retrieves the error code from the body object
     *
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->getBodyData('error_code') ?? '';
    }

    /**
     * Retrieves validation errors from the body object
     *
     * @return array
     */
    public function getValidationErrors(): array
    {
        return $this->getBodyData('validation_errors') ?? [];
    }

    /**
     * Retrieves validation errors from the body object
     *
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->getBodyData('error_message') ?? '';
    }

    /**
     * Retrieves validation errors from the body object
     *
     * @return string
     */
    public function getErrorId(): string
    {
        return $this->getBodyData('error_id') ?? '';
    }
}
