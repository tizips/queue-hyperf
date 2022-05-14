<?php

declare(strict_types=1);

namespace App\Amqp\Producer\Site\Queue;

use App\Amqp\Producer\AbstractProducer;
use Hyperf\Amqp\Annotation\Producer;

#[Producer(exchange: 'hyperf', routingKey: 'hyperf.queue.fail')]
class FailProducer extends AbstractProducer
{

}