<?php

/**
 * @link https://github.com/idksapr/lptest
 * @copyright Copyright (c) 2019 idksapr
 * @author Dmitry Ivanov <idksapr@gmail.com>
 */

namespace idksapr\lptest\services;

use idksapr\lptest\services\AbstractHttpService;
use idksapr\lptest\SubscriptionResult;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\RequestOptions;

/**
 * Class Mailerlite
 * @package idksapr\lptest
 */
class Mailerlite extends AbstractHttpService
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $groupId;

    /**
     * Prepare service
     *
     * @param array $integration - integration props
     */
    public function prepare(array $integration)
    {
        parent::prepare($integration);

        if (isset($integration['apiKey'])) {
            $this->apiKey = $integration['apiKey'];
        }
        if (isset($integration['groupId'])) {
            $this->groupId = $integration['groupId'];
        }
    }

    /**
     * Send request
     *
     * @param array $lead - lead props (example: ['name' => 'Dmitry', 'email' => 'idksapr@gmail.com'])
     * @return ResponseInterface
     */
    protected function sendRequest(array $lead): ResponseInterface
    {
        return $this->client->post("{$this->baseUrl}/groups/{$this->groupId}/subscribers", [
            'headers' => [
                'X-MailerLite-ApiKey' => $this->apiKey,
            ],
            RequestOptions::JSON => $lead,
        ]);
    }

    /**
     * Run service for lead
     *
     * @param array $lead - lead props (example: ['name' => 'Dmitry', 'email' => 'idksapr@gmail.com'])
     * @return SubscriptionResult
     */
    public function run(array $lead): SubscriptionResult
    {
        parent::run($lead);

        if (!$this->result->isSuccess() && $this->response instanceof ResponseInterface) {
            $this->result
                ->setMessage(json_decode($this->response->getBody())->error->message);
        }

        return $this->result;
    }

    /**
     * Check ready
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return parent::isReady() &&
            !empty($this->baseUrl) &&
            !empty($this->apiKey) &&
            !empty($this->groupId);
    }
}
