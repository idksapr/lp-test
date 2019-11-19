<?php

/**
 * @link https://github.com/idksapr/lptest
 * @copyright Copyright (c) 2019 idksapr
 * @author Dmitry Ivanov <idksapr@gmail.com>
 */

namespace idksapr\lptest;

use idksapr\lptest\SubscriptionServiceInterface;
use idksapr\lptest\SubscriptionResult;

/**
 * Class SubscriptionTask
 * @package idksapr\lptest
 */
class SubscriptionTask
{
    /**
     * @var SubscriptionServiceInterface
     */
    protected $service = null;

    /**
     * @var SubscriptionResult
     */
    protected $result = null;

    /**
     * @param SubscriptionServiceInterface $service
     */
    public function __construct(SubscriptionServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Run service for lead
     *
     * @param array $lead - lead props (example: ['name' => 'Dmitry', 'email' => 'idksapr@gmail.com'])
     * @return SubscriptionResult
     */
    public function run(array $lead): SubscriptionResult
    {
        $this->result = $this->service->run($lead);
        return $this->result;
    }

    /**
     * Get service
     *
     * @return SubscriptionServiceInterface
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Get service name
     *
     * @return string
     */
    public function getServiceName()
    {
        return (new \ReflectionClass($this->service))->getShortName();
    }

    /**
     * Get result
     *
     * @return SubscriptionResult
     */
    public function getResult()
    {
        return $this->result;
    }
}
