<?php

/**
 * @link https://github.com/idksapr/lptest
 * @copyright Copyright (c) 2019 idksapr
 * @author Dmitry Ivanov <idksapr@gmail.com>
 */

namespace idksapr\lptest\tests;

use PHPUnit\Framework\TestCase;
use idksapr\lptest\SubscriptionTask;
use idksapr\lptest\SubscriptionResult;
use idksapr\lptest\SubscriptionServiceInterface;
use idksapr\lptest\services\Mailchimp;

/**
 * Class SubscriptionTaskTest
 * @package idksapr\lptest
 */
class SubscriptionTaskTest extends TestCase
{
    /**
     * @var array
     */
    private $integration;

    /**
     * @var array
     */
    private $lead;

    /**
     * Set up
     */
    protected function setUp(): void
    {
        $this->integration = [
            'apiKey' => '<api key>',
            'listId' => '2451b49939',
        ];

        $this->lead = [
            'name' => 'Dmitry',
            'email' => 'idksapr@gmail.com',
        ];
    }

    /**
     * Tear down
     */
    protected function tearDown(): void
    {
        $this->integration = null;
        $this->lead = null;
    }

    /**
     * Test service not ready
     */
    public function testServiceNotReady()
    {
        $service = new Mailchimp('https://us6.api.mailchimp.com/3.0');
        $this->assertTrue(!$service->isReady());

        return $service;
    }

    /**
     * Test service ready
     * @depends testServiceNotReady
     * @param SubscriptionServiceInterface $service
     */
    public function testServiceReady(SubscriptionServiceInterface $service)
    {
        $service->prepare($this->integration);
        $this->assertTrue($service->isReady());

        return $service;
    }

    /**
     * Test task
     * @depends testServiceReady
     * @param SubscriptionServiceInterface $service
     */
    public function testTask(SubscriptionServiceInterface $service)
    {
        $this->task = new SubscriptionTask($service);
        $result = $this->task->run($this->lead);

        $this->assertInstanceOf(SubscriptionResult::class, $result);
        $this->assertTrue(!$result->isSuccess());
        $this->assertEquals($result->getStatusCode(), '401');
    }
}
