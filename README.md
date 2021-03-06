# LP test.

## Specification

Задача: написать библиотеку для работы с интеграциями.

На входе:
```
Tasks = '[
  {
    integration: {
      service: "mailerlite",
      apiKey: "493ede2b62a8027a21267c15c570b1b4",
      groupId: 14395208,
    },
    lead: {
      name: "Вася",
      email: "vasya@platformalp.ru",
    },
  },
  {
    integration: {
      service: "mailchimp",
      apiKey: "33f401b170e95096e9169206b229793d-us13",
      listId: 341781,
    },
    lead: {
      name: "Петр",
      email: "petr@platformalp.ru",
    },
  }
]'
```
Для каждой задачи в массиве должна выполнится соответствующая интеграция (добавления подписчика).
По каждой задаче должен выводится результат (ок и ответ от сервера, или ошибка и описание ошибки).
Система должна быть рассчитана на удобное подключения других интеграций в будущем.

При оценке задания в первую очередь будет оцениваться подход, архитектура, стиль и т.п.

Документация по указанным двум сервисам:
https://mailchimp.com/developer/
https://developers.mailerlite.com/docs

## Installation
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require idksapr/lp-test
```

## Basic usage

```php
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
?>
```
