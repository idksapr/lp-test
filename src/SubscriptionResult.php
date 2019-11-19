<?php

/**
 * @link https://github.com/idksapr/lptest
 * @copyright Copyright (c) 2019 idksapr
 * @author Dmitry Ivanov <idksapr@gmail.com>
 */

namespace idksapr\lptest;

/**
 * Class SubscriptionTask
 * @package idksapr\lptest
 */
class SubscriptionResult
{
    /**
     * @var bool
     */
    private $isSuccess = false;

    /**
     * @var string
     */
    private $message = '';

    /**
     * @var string
     */
    private $statusCode = '';

    /**
     * @var string
     */
    private $response = '';


    /**
     * @param bool $isSuccess
     * @param string $message
     */
    public function __construct(bool $isSuccess, string $message = '')
    {
        $this->isSuccess = $isSuccess;
        $this->message = $message;
    }

    /**
     * @param string $statusCode
     */
    public function setStatusCode(string $statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param string $response
     */
    public function setResponse(string $response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    /**
     * @return string
     */
    public function getStatusCode(): string
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }
}
