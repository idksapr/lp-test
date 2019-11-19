<?php

/**
 * @link https://github.com/idksapr/lptest
 * @copyright Copyright (c) 2019 idksapr
 * @author Dmitry Ivanov <idksapr@gmail.com>
 */

namespace idksapr\lptest\services;

use idksapr\lptest\SubscriptionServiceInterface;
use idksapr\lptest\SubscriptionResult;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

/**
 * Class AbstractHttpService
 * @package idksapr\lptest
 */
abstract class AbstractHttpService implements SubscriptionServiceInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var ResponseInterface
     */
    protected $response = null;

    /**
     * @var SubscriptionResult
     */
    protected $result = null;

     /**
      * @param string $baseUrl
      * @return bool
      */
    public function __construct(string $baseUrl)
    {
        $this->client = new Client();
        $this->baseUrl = $baseUrl;
    }

    /**
     * Prepare service
     *
     * @param array $integration - integration props
     */
    public function prepare(array $integration)
    {
        $this->response = null;
        $this->result = null;
    }

    /**
     * Send request
     *
     * @param array $lead - lead props (example: ['name' => 'Dmitry', 'email' => 'idksapr@gmail.com'])
     * @return ResponseInterface
     */
    abstract protected function sendRequest(array $lead): ResponseInterface;

    /**
     * Check ready
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return is_null($this->response) && is_null($this->result);
    }

    /**
     * Run service for lead
     *
     * @param array $lead - lead props (example: ['name' => 'Dmitry', 'email' => 'idksapr@gmail.com'])
     * @return SubscriptionResult
     */
    public function run(array $lead): SubscriptionResult
    {
        if (!$this->isReady()) {
            return new SubscriptionResult(false, 'Incorrectly prepared service');
        }

        try {
            if (!isset($lead['name']) || !isset($lead['email'])) {
                throw new \Exception('Incorrect lead format');
            }

            $this->response = $this->sendRequest($lead);

            $this->result = new SubscriptionResult(true, 'OK');
        } catch (\Exception $e) {
            $this->result = (new SubscriptionResult(false, $e->getMessage()));

            if ($e instanceof TransferException && $e->hasResponse()) {
                $this->response = $e->getResponse();
            }
        }

        if ($this->response instanceof ResponseInterface) {
            $this->result
                ->setResponse(Psr7\str($this->response))
                ->setStatusCode($this->response->getStatusCode());
        }

        return $this->result;
    }
}
