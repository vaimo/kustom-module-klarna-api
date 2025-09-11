<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures;

use Klarna\KlarnaApi\Model\Http\Response\SuccessResponseAbstract;

/**
 * @internal
 */
class SuccessResponse extends SuccessResponseAbstract
{
    /**
     * Retrieves the features from the body object
     *
     * @return array
     */
    public function getFeatures(): array
    {
        return $this->getBodyData('features') ?? [];
    }

    /**
     * Retrieves the features from the body object
     *
     * @return array
     */
    public function getAvailableMarkets(): array
    {
        return $this->getBodyData('available_markets') ?? [];
    }

    /**
     * Retrieves the plugin installation id from the body object
     *
     * @return string
     */
    public function getPluginInstallationId(): string
    {
        return $this->getBodyData('plugin_installation_id') ?? '';
    }
}
