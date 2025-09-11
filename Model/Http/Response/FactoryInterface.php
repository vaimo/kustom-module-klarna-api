<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Model\Http\Response;

/**
 * @internal
 */
interface FactoryInterface
{
    /**
     * Getting back the failure instance
     *
     * @return FailureResponseAbstract
     */
    public function createFailureInstance(): FailureResponseAbstract;

    /**
     * Getting back the success instance
     *
     * @return SuccessResponseAbstract
     */
    public function createSuccessInstance(): SuccessResponseAbstract;
}
