<?php

declare(strict_types=1);

namespace App\Amqp\Producer;

use Hyperf\Amqp\Annotation\Producer;

#[Producer(exchange: 'hyperf', routingKey: 'hyperf')]
class DemoProducer extends AbstractProducer
{
}
