<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures;

use Klarna\KlarnaApi\Model\Http\Response\FactoryInterface;

/**
 * @internal
 */
class Factory implements FactoryInterface
{
    /**
     * @var FailureResponseFactory
     */
    private FailureResponseFactory $failureResponseFactory;
    /**
     * @var SuccessResponseFactory
     */
    private SuccessResponseFactory $successResponseFactory;

    /**
     * @param FailureResponseFactory $failureResponseFactory
     * @param SuccessResponseFactory $successResponseFactory
     * @codeCoverageIgnore
     */
    public function __construct(
        FailureResponseFactory $failureResponseFactory,
        SuccessResponseFactory $successResponseFactory
    ) {
        $this->failureResponseFactory = $failureResponseFactory;
        $this->successResponseFactory = $successResponseFactory;
    }

    /**
     * Getting back the failure instance
     *
     * @return FailureResponse
     */
    public function createFailureInstance(): FailureResponse
    {
        return $this->failureResponseFactory->create();
    }

    /**
     * Getting back the success instance
     *
     * @return SuccessResponse
     */
    public function createSuccessInstance(): SuccessResponse
    {
        return $this->successResponseFactory->create();
    }
}
