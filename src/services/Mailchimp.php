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
 * Class Mailchimp
 * @package idksapr\lptest
 */
class Mailchimp extends AbstractHttpService
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $listId;

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
        if (isset($integration['listId'])) {
            $this->listId = $integration['listId'];
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
        return $this->client->post("{$this->baseUrl}/lists/{$this->listId}/members", [
            'auth' => [
                'anystring',
                $this->apiKey,
            ],
            RequestOptions::JSON => [
                'email_address' => $lead['email'],
                'status' => 'subscribed',
                'merge_fields' => [
                    'FNAME' => $lead['name'],
                ],
            ],
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
            $body = json_decode($this->response->getBody());
            $this->result
                ->setMessage("{$body->title}. {$body->detail}");
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
            !empty($this->listId);
    }
}
