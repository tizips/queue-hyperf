<?php

declare(strict_types=1);

namespace App\Amqp\Consumer\Site\Queue;

use App\Amqp\Consumer\AbstractConsumer;
use Exception;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Logger\LoggerFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

#[Consumer(exchange: 'hyperf', routingKey: 'hyperf.queue.fail', queue: 'hyperf.queue.fail')]
class FailConsumer extends AbstractConsumer
{
    protected bool $fail = false;

    protected string $logger = 'logger';

    protected int $retries = 1;

    /**
     * @param mixed $data
     * @param int   $retries
     * @return void
     * @throws Exception
     */
    protected function handler(mixed $data, int $retries)
    {
//        $queue = Queue::query()
//            ->create([
//                'producer' => $data['data']['producer'],
//                'retries' => $data['data']['retries'],
//                'data' => $data['data']['payload'],
//                'exception' => $data['exception'],
//                'status' => Queue::STATUS_AUDIT,
//            ]);
//
//        if (! $queue) throw new Exception('写入失败！');
    }

    /**
     * @param mixed     $data
     * @param Exception $exception
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function fail(mixed $data, Exception $exception)
    {
        $logger = $this->container->get(LoggerFactory::class)->get('queue', 'queue');

        $logger->error(sprintf('retries[%s] in %s', $data['data']['retries'], $data['data']['producer']));
        $logger->error($data['exception']);
        $logger->error(json_encode($data['data']['payload'], JSON_UNESCAPED_UNICODE));
    }
}