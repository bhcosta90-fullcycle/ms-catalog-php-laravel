<?php

namespace App\Services\AMQP;

use Closure;

class UnitTest implements AMQPInterface
{
    public function producer(string $queue, array $payload, string $exchange): void
    {
        //
    }

    public function producerFanout(array $payload, string $exchange)
    {
        //
    }

    public function consumer(string $queue, string $exchange, Closure $callback): void
    {
        //
    }
}
