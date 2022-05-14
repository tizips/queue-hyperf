<?php

declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;

Router::get('/test', function () {

    $producer = new \App\Amqp\Producer\DemoProducer(['name' => '测试', 'id' => 10000]);

    $context = \Hyperf\Utils\ApplicationContext::getContainer()->get(\Hyperf\Amqp\Producer::class);

    $context->produce($producer);

});