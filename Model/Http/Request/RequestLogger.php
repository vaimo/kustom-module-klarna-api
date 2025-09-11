<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Model\Http\Request;

use Exception;
use GuzzleHttp\Exception\RequestException;
use Klarna\Logger\Model\Api\Container;
use Klarna\Logger\Model\Api\Logger as ApiLogger;
use Klarna\Logger\Model\Logger;
use Magento\Framework\Exception\LocalizedException;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * @internal
 */
class RequestLogger
{
    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var Container
     */
    private Container $loggerContainer;

    /**
     * @var ApiLogger
     */
    private ApiLogger $apiLogger;

    /**
     * @param Logger $logger
     * @param Container $loggerContainer
     * @param ApiLogger $apiLogger
     * @codeCoverageIgnore
     */
    public function __construct(
        Logger $logger,
        Container $loggerContainer,
        ApiLogger $apiLogger
    ) {
        $this->logger = $logger;
        $this->loggerContainer = $loggerContainer;
        $this->apiLogger = $apiLogger;
    }

    /**
     * Logs the client request and response to a file
     *
     * @return void
     */
    public function logToFile(): void
    {
        $this->logger->error(
            'API requests failed for the following request service: ' .
            $this->loggerContainer->getService() .
            '. Request: ' .
            json_encode($this->loggerContainer->getRequest()) .
            'Reason: ' .
            json_encode($this->loggerContainer->getResponse())
        );
    }

    /**
     * Sets the response for the request in the logger container
     *
     * @param array $responseData
     * @return void
     */
    public function setResponse(array $responseData): void
    {
        $this->loggerContainer->setResponse($responseData);
    }

    /**
     * Sets the request, action, url and service for the logger container
     *
     * @param Request $request
     * @param string $service
     * @param string $action
     * @return void
     */
    public function requestLogging(Request $request, string $service, string $action): void
    {
        $reqData = [];
        $requestData = $request->getRequestData();
        if (isset($requestData['json'])) {
            $reqData = $requestData['json'];
        }
        $this->loggerContainer->setRequest($reqData);
        $this->loggerContainer->setMethod($request->getHttpMethod());
        $this->loggerContainer->setAction($action);
        $this->loggerContainer->setUrl($request->getUrl());
        $this->loggerContainer->setService($service);
    }

    /**
     * Logs an exception with type error to the logger
     *
     * @param Exception|RequestException $exception
     * @return void
     * @throws Exception
     */
    public function logError(Exception|RequestException $exception): void
    {
        $this->logger->log('error', $exception);
    }

    /**
     * Logs the container to the API logger
     *
     * @return void
     * @throws LocalizedException|Exception
     */
    public function logContainer(): void
    {
        try {
            $this->apiLogger->logContainer($this->loggerContainer);
        } catch (LocalizedException|Exception $exception) {
            $this->logger->log('error', $exception);
        }
    }
}
