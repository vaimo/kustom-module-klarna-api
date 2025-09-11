<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Model\Rest;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use Klarna\KlarnaApi\Model\Http\Request\RequestLogger;
use Klarna\KlarnaApi\Model\Http\Request\RequestInterface;
use Klarna\KlarnaApi\Model\Http\Response\FactoryInterface;
use Klarna\KlarnaApi\Model\Http\Response\FailureResponseAbstract;
use Klarna\KlarnaApi\Model\Http\Response\ResponseAbstract;
use Magento\Framework\Exception\LocalizedException;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @internal
 */
class Client
{
    /**
     * @var GuzzleClient
     */
    private GuzzleClient $guzzleClient;
    /**
     * @var RequestLogger
     */
    private RequestLogger $requestLogger;

    /**
     * @param GuzzleClient $guzzleClient
     * @param RequestLogger $requestLogger
     * @codeCoverageIgnore
     */
    public function __construct(
        GuzzleClient $guzzleClient,
        RequestLogger $requestLogger
    ) {
        $this->guzzleClient = $guzzleClient;
        $this->requestLogger = $requestLogger;
    }

    /**
     * Sending the request
     *
     * @param RequestInterface $request
     * @param FactoryInterface $factory
     * @return ResponseAbstract
     * @throws LocalizedException
     */
    public function sendRequest(
        RequestInterface $request,
        FactoryInterface $factory
    ): ResponseAbstract {
        try {
            $method = $request->getHttpMethod();

            $apiResponse = $this->guzzleClient->$method(
                $request->getUrl(),
                $request->getRequestData()
            );
            $response = $factory->createSuccessInstance();
        } catch (RequestException $e) {
            // Catches all client & server exceptions that guzzle-client throws
            $response = $factory->createFailureInstance();
            $apiResponse = null;
        } catch (\Exception $e) {
            // Catches other exceptions and logs them, return a failure response
            $this->requestLogger->logError($e);
            return $factory->createFailureInstance();
        }
        $this->manageResponse($response, $apiResponse, $e ?? null);

        if (!$response->isSuccessful()) {
            $this->requestLogger->logToFile();
        }

        return $response;
    }

    /**
     * Processed the response, log if the response failed or threw an exception
     *
     * @param ResponseAbstract $response
     * @param PsrResponseInterface|null $apiResponse
     * @param RequestException|null $e
     * @throws LocalizedException
     */
    private function manageResponse(
        ResponseAbstract $response,
        PsrResponseInterface|null $apiResponse,
        RequestException|null $e = null
    ): void {
        $response->processResponse($apiResponse, $e);
        $responseData = $response->getBasicResponseData();
        $this->requestLogger->setResponse($responseData);

        // Only log if the response failed
        if ($response instanceof FailureResponseAbstract) {
            $this->requestLogger->logError($e);
        }

        $this->requestLogger->logContainer();
    }
}
