<?php

declare(strict_types=1);

namespace App\Amqp\Consumer;

use Hyperf\Amqp\Annotation\Consumer;

#[Consumer(exchange: 'hyperf', routingKey: 'hyperf', queue: 'hyperf', name: "DemoConsumer", nums: 3)]
class DemoConsumer extends AbstractConsumer
{
    protected function handler(mixed $data, int $retries)
    {
        print_r($data);

        print_r($retries);
    }
}
