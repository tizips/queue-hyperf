<?php

declare(strict_types=1);

namespace App\Amqp\Producer;

use Hyperf\Amqp\Message\ProducerMessage;

class AbstractProducer extends ProducerMessage
{
    /**
     * @param mixed $data  队列数据
     */
    public function __construct(mixed $data)
    {
        $payload = $data;

        if (is_array($payload) && isset($payload['payload'], $payload['retries'], $payload['delay'])) {
            $payload['retries'] += 1;
        } else {

            $Class = get_called_class();

            $payload = [
                'delay' => 0,
                'retries' => 0,
                'payload' => $data,
                'producer' => $Class,
            ];
        }

        $this->payload = $payload;
    }
}
