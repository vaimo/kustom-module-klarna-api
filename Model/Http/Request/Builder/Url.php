<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Model\Http\Request\Builder;

use Klarna\AdminSettings\Model\Configurations\Api;
use Klarna\Logger\Model\Logger;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;

/**
 * @internal
 */
class Url
{

    /**
     * @var Api
     */
    private Api $apiConfiguration;

    /**
     * @var string
     */
    private string $url = '';

    /**
     * @var Logger
     */
    private Logger $logger;
    /**
     * @var StoreInterface
     */
    private StoreInterface $store;
    /**
     * @var string
     */
    private string $currency;

    /**
     * @param Api $apiConfiguration
     * @param Logger $logger
     * @codeCoverageIgnore
     */
    public function __construct(
        Api $apiConfiguration,
        Logger $logger
    ) {
        $this->apiConfiguration = $apiConfiguration;
        $this->logger = $logger;
    }

    /**
     * Setting the store
     *
     * @param StoreInterface $store
     * @return void
     */
    public function setStore(StoreInterface $store): void
    {
        $this->store = $store;
    }

    /**
     * Setting the currency
     *
     * @param string $currency
     * @return void
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * Clearing the url
     *
     * @return $this
     */
    public function clearUrl(): self
    {
        $this->url = '';
        return $this;
    }

    /**
     * Checks if the URL is empty, it it is, populate it with the getPrefixUrl() results)
     *
     * @return void
     * @throws NoSuchEntityException
     */
    private function isUrlEmpty(): void
    {
        if (empty($this->url)) {
            $this->url = $this->getPrefixUrl();
        }
    }

    /**
     * Adds a portion of the URL for the request, trims "/" character at the end of parameter
     *
     * @param string $urlPart
     * @return $this
     * @throws NoSuchEntityException
     */
    public function add(string $urlPart): self
    {
        $this->isUrlEmpty();
        $urlPart = trim($urlPart, '/');
        $this->url = rtrim($this->url, '/') . '/' . $urlPart;
        return $this;
    }

    /**
     * Gets built URL
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getUrl(): string
    {
        $this->isUrlEmpty();
        return $this->url;
    }

    /**
     * Getting back the prefix url
     *
     * @return string
     * @throws NoSuchEntityException
     */
    private function getPrefixUrl(): string
    {
        try {
            return $this->apiConfiguration->getGlobalApiUrl($this->store, $this->currency);
        } catch (NoSuchEntityException $e) {
            $this->logger->log('error', $e);
            throw $e;
        }
    }
}
