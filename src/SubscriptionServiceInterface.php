<?php

/**
 * @link https://github.com/idksapr/lptest
 * @copyright Copyright (c) 2019 idksapr
 * @author Dmitry Ivanov <idksapr@gmail.com>
 */

namespace idksapr\lptest;

use idksapr\lptest\SubscriptionResult;

/**
 * Interface SubscriptionServiceInterface
 * @package idksapr\lptest
 */
interface SubscriptionServiceInterface
{
    /**
     * Prepare service
     *
     * @param array $integration - integration props
     * @return bool
     */
    public function prepare(array $integration);

    /**
     * Run service for lead
     *
     * @param array $lead - lead props (example: ['name' => 'Dmitry', 'email' => 'idksapr@gmail.com'])
     * @return SubscriptionResult
     */
    public function run(array $lead): SubscriptionResult;
}
