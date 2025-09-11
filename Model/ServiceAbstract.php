<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Model;

use Klarna\KlarnaApi\Model\Rest\Client;
use Klarna\Logger\Model\Api\Container;

/**
 * @internal
 */
abstract class ServiceAbstract
{
    /**
     * @var Client
     */
    protected Client $client;
    /**
     * @var Container
     */
    protected Container $loggerContainer;

    /**
     * @param Client $client
     * @param Container $loggerContainer
     * @codeCoverageIgnore
     */
    public function __construct(
        Client $client,
        Container $loggerContainer
    ) {
        $this->client = $client;
        $this->loggerContainer = $loggerContainer;
    }
}
