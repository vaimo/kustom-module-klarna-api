<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2;

use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\Responses\PostFeatures\FailureResponse;
use Klarna\KlarnaApi\Model\Http\Response\ResponseAbstract;
use Magento\Store\Model\Store;

/**
 * @internal
 */
class Result
{
    /**
     * @var string
     */
    private string $market = '';
    /**
     * @var array
     */
    private array $requestPayLoad = [];
    /**
     * @var string
     */
    private string $klarnaInstallationId = '';
    /**
     * @var Store
     */
    private Store $store;
    /**
     * @var ResponseAbstract|null
     */
    private ?ResponseAbstract $response = null;

    /**
     * Setting the Klarna installation ID
     *
     * @param string $installationId
     * @return void
     */
    public function setKlarnaInstallationId(string $installationId)
    {
        $this->klarnaInstallationId = $installationId;
    }

    /**
     * Getting back the Klarna installation ID
     *
     * @return string
     */
    public function getKlarnaInstallationId(): string
    {
        if ($this->response !== null) {
            return $this->response->getBody()['plugin_installation_id'];
        } else {
            return $this->klarnaInstallationId;
        }
    }

    /**
     * Setting the store
     *
     * @param Store $store
     * @return void
     */
    public function setStore(Store $store): void
    {
        $this->store = $store;
    }

    /**
     * Getting back the store
     *
     * @return Store
     */
    public function getStore(): Store
    {
        return $this->store;
    }

    /**
     * Getting back the scope
     *
     * @return string
     */
    public function getScope(): string
    {
        return $this->store->getScope();
    }

    /**
     * Getting back the store ID
     *
     * @return int
     */
    public function getStoreId(): int
    {
        $scope = $this->getScope();
        if ($scope === 'default') {
            return 0;
        }

        return (int) $this->store->getWebsiteId();
    }

    /**
     * Setting the market
     *
     * @param string $market
     * @return void
     */
    public function setMarket(string $market)
    {
        $this->market = $market;
    }

    /**
     * Getting back the market
     *
     * @return string
     */
    public function getMarket(): string
    {
        return $this->market;
    }

    /**
     * Setting the request pay load
     *
     * @param array $requestPayLoad
     * @return void
     */
    public function setRequestPayLoad(array $requestPayLoad)
    {
        $this->requestPayLoad = $requestPayLoad;
    }

    /**
     * Getting back the request pay load
     *
     * @return array
     */
    public function getRequestPayLoad(): array
    {
        return $this->requestPayLoad;
    }

    /**
     * Setting the response
     *
     * @param ResponseAbstract $response
     * @return void
     */
    public function setResponse(ResponseAbstract $response)
    {
        $this->response = $response;
    }

    /**
     * Getting back the api available markets
     *
     * @return array
     */
    public function getApiAvailableMarkets(): array
    {
        return $this->response->getBody()['available_markets'];
    }

    /**
     * Getting back the api features
     *
     * @return array
     */
    public function getApiFeatures(): array
    {
        return $this->response->getBody()['features'];
    }

    /**
     * Returns true if the response is valid
     *
     * @return bool
     */
    public function isValidResponse(): bool
    {
        if ($this->response instanceof FailureResponse) {
            return false;
        }
        if (!$this->response->isSuccessful($this->response->getCode())) {
            return false;
        }

        $responseData = $this->response->getBody();
        return isset($responseData['plugin_installation_id']) &&
            isset($responseData['features']) &&
            isset($responseData['available_markets']);
    }
}
