<?php

namespace App\Services\AMQP;

interface AMQPInterface
{
    public function producer(string $queue, array $payload, string $exchange): void;
    public function producerFanout(array $payload, string $exchange);
    public function consumer(string $queue, string $exchange, Clojure $callback): void;
}
