<?php

namespace App\Services\AMQP;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class PhpAmqplib implements AMQPInterface
{
    protected ?AMQPStreamConnection $connection = null;
    protected $channel = null;

    public function __construct()
    {
        if ($this->connection) {
            return null;
        }

        $configs = config('ms.rabbitmq.hosts')[0];

        $this->connection = new AMQPStreamConnection(
            host: $configs['host'],
            port: $configs['port'],
            user: $configs['user'],
            password: $configs['password'],
            vhost: $configs['vhost']
        );

        $this->channel = $this->connection->channel();
    }

    public function producer(string $queue, array $payload, string $exchange): void
    {
    }

    public function producerFanout(array $payload, string $exchange)
    {
        $this->channel->exchange_declare(
            exchange: $exchange,
            type: AMQPExchangeType::FANOUT,
            passive: false,
            durable: true,
            auto_delete: false,
        );

        $message = new AMQPMessage(json_encode($payload), [
            'content_type' => 'text/plan',
        ]);

        $this->channel->basic_publish(
            msg: $message,
            exchange: $exchange
        );

        $this->closeChannel();
        $this->closeConnection();
    }

    public function consumer(string $queue, string $exchange, Clojure $callback): void
    {
    }

    protected function closeChannel() {
        $this->channel->close();
    }

    protected function closeConnection(){
        $this->connection->close();
    }
}
