<?php

/**
 * @link https://github.com/idksapr/lptest
 * @copyright Copyright (c) 2019 idksapr
 * @author Dmitry Ivanov <idksapr@gmail.com>
 */

namespace idksapr\lptest;

use idksapr\lptest\SubscriptionTask;
use idksapr\lptest\SubscriptionServiceInterface;

/**
 * Class SubscriptionQueue
 * @package idksapr\lptest
 */
class SubscriptionQueue
{
    /**
     * @var SubscriptionServiceInterface[]
     */
    protected $services = [];

    /**
     * Run subscriptions queue
     *
     * @param array $config config of subscription queue
     * @return \Generator
     */
    public function run(array $config)
    {
        foreach ($config as $taskConfig) {
            if (empty($taskConfig['integration']) ||
                empty($this->services[$taskConfig['integration']['service']]) ||
                empty($taskConfig['lead'])
            ) {
                continue;
            }

            $service = $this->services[$taskConfig['integration']['service']];
            $service->prepare($taskConfig['integration']);

            $task = new SubscriptionTask($service);
            $task->run($taskConfig['lead']);

            yield $task;
        }
    }

    /**
     * Add subscription service
     *
     * @param string $name
     * @param SubscriptionServiceInterface $service
     */
    public function addService(string $name, SubscriptionServiceInterface $service)
    {
        $this->services[$name] = $service;
    }

    /**
     * Remove subscription service
     *
     * @param string $name
     */
    public function rmService(string $name)
    {
        if (isset($this->services[$name])) {
            unset($this->services[$name]);
        }
    }
}
