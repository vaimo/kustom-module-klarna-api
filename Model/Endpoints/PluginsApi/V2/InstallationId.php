<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2;

use Klarna\Base\Helper\GUIDGenerator;
use Klarna\KlarnaApi\Model\Exception as KlarnaApiException;
use Klarna\PluginsApi\Model\Database\InstallationRepository;

/**
 * @internal
 */
class InstallationId
{
    /**
     * @var InstallationRepository
     */
    private InstallationRepository $installationRepository;
    /**
     * @var GUIDGenerator
     */
    private GUIDGenerator $guidGenerator;

    /**
     * @param InstallationRepository $installationRepository
     * @param GUIDGenerator $guidGenerator
     * @codeCoverageIgnore
     */
    public function __construct(InstallationRepository $installationRepository, GUIDGenerator $guidGenerator)
    {
        $this->installationRepository = $installationRepository;
        $this->guidGenerator = $guidGenerator;
    }

    /**
     * Getting back the installation ID or generating a new one if it does not exist
     *
     * @param string $scope
     * @param int $scopeId
     * @param string $market
     * @return string
     * @throws KlarnaApiException
     * @throws \Random\RandomException
     */
    public function get(string $scope, int $scopeId, string $market): string
    {
        $installation = $this->installationRepository->getEntriesByScopeAndStoreAndMarket($scope, $scopeId, $market);
        $installationId = $installation->getInstallationId();
        if (empty($installationId)) {
            $installationId = strtolower($this->guidGenerator->generateGUID());
        }
        if (empty($installationId)) {
            throw new KlarnaApiException(__('Could not generate a new installation ID'));
        }
        return $installationId;
    }
}
