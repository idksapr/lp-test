<?php
require __DIR__.'./../vendor/autoload.php';

use idksapr\lptest\SubscriptionQueue;
use idksapr\lptest\services\Mailchimp;
use idksapr\lptest\services\Mailerlite;

$config = [
    [
        'integration' => [
            'service' => 'mailchimp',
            'apiKey' => '<api key>',
            'listId' => '2451b4993',
        ],
        'lead' => [
            'name' => "Петр",
            'email' => "petr@platformalp.ru",
        ],
    ],
    [
        'integration' => [
            'service' => 'mailerlite',
            'apiKey' => 'fc7b8c5b32067bcd47cafb5f475d2fe9',
            'groupId' => '3640549',
        ],
        'lead' => [
            'name' => "Вася",
            'email' => "vasya@gmail.com",
        ],
    ],
    [
        'integration' => [
            'service' => 'mailchimp',
            'apiKey' => '<api key>',
            'listId' => '2451b49939',
        ],
        'lead' => [
            'name' => "Дмитрий",
            'email' => "idksapr@gmail.com",
        ],
    ],
];

$queue = new SubscriptionQueue();
$queue->addService('mailchimp', new Mailchimp('https://us6.api.mailchimp.com/3.0'));
$queue->addService('mailerlite', new Mailerlite('http://api.mailerlite.com/api/v2'));

foreach ($queue->run($config) as $task) {
    $result = $task->getResult();

    echo "\nService: {$task->getServiceName()}\n\n";
    echo "Status: " . ($result->isSuccess() ? 'success' : 'fail') . "\n\n";
    echo "Status code: {$result->getStatusCode()}\n\n";
    echo "Message: {$result->getMessage()}\n\n";
    echo "Response: " . $result->getResponse() . "\n";

    echo str_repeat('-', 80);
    echo "\n";
}
