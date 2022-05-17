<?php

declare(strict_types=1);

namespace App\Amqp\Consumer;

use App\Amqp\Producer\Site\Queue\FailProducer;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Producer;
use Hyperf\Amqp\Result;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class AbstractConsumer extends ConsumerMessage
{
    protected bool $fail = true;

    protected string $logger = 'db';

    protected int $retries = 0;

    /**
     * @param $data
     * @return string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function consume($data): string
    {
        $payload = $this->parse($data);

        try {
            $this->handler($payload['payload'], $payload['retries']);
        } catch (Throwable $exception) {

            $producer = $this->container->get(Producer::class);

            $message = null;

            if (isset($payload['producer'], $payload['retries']) && $payload['retries'] < $this->retries) {
                //  获取生产类，重新投入队列重拾
                $Class = $payload['producer'];
                $message = new $Class($payload, $payload['delay']);
            } else {
                $this->fail($payload['payload'], $exception);
                if ($this->fail) {
                    $message = new FailProducer(['logger' => $this->logger, 'data' => $payload, 'exception' => $exception->getMessage()]);
                }
            }

            if ($message) $producer->produce($message);
        }

        return Result::ACK;
    }

    protected function handler(mixed $data, int $retries)
    {

    }

    protected function fail(mixed $data, Throwable $exception)
    {

    }

    private function parse(mixed $data): array
    {
        $payload = [
            'delay' => 0,
            'retries' => 0,
            'payload' => $data,
        ];

        if (is_array($data) && isset($data['retries'], $data['payload'], $data['delay'])) {
            $payload = [
                'retries' => (int) $data['retries'],
                'delay' => (int) $data['delay'],
                'producer' => (string) $data['producer'],
                'payload' => $data['payload'],
            ];
        }

        return $payload;
    }
}
