<?php

/**
 * @link https://github.com/idksapr/lptest
 * @copyright Copyright (c) 2019 idksapr
 * @author Dmitry Ivanov <idksapr@gmail.com>
 */

namespace idksapr\lptest\tests;

use PHPUnit\Framework\TestCase;
use idksapr\lptest\SubscriptionResult;

/**
 * Class SubscriptionResultTest
 * @package idksapr\lptest
 */
class SubscriptionResultTest extends TestCase
{
    /**
     * Test full
     */
    public function testInit()
    {
        $message = 'Test';
        $result = new SubscriptionResult(true, $message);

        $this->assertTrue($result->isSuccess());
        $this->assertEquals($result->getMessage(), $message);

        return $result;
    }

    /**
     * Test full
     * @depends testInit
     * @param SubscriptionResult $result
     */
    public function testFull(SubscriptionResult $result)
    {
        $statusCode = '200';
        $response = 'OK';

        $result->setStatusCode($statusCode)->setResponse($response);

        $this->assertEquals($result->getStatusCode(), $statusCode);
        $this->assertEquals($result->getResponse(), $response);
    }
}
