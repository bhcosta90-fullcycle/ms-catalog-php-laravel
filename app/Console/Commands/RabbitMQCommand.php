<?php

namespace App\Console\Commands;

use App\Services\AMQP\AMQPInterface;
use BRCas\MV\UseCases\Video\DTO\UpdatePathMediaInput;
use BRCas\MV\UseCases\Video\UpdatePathMediaUseCase;
use Illuminate\Console\Command;

class RabbitMQCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consumer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consumer RabbitMQ';

    public function __construct(
        protected AMQPInterface $aMQPInterface,
        protected UpdatePathMediaUseCase $updatePathMediaUseCase
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $callback = function ($message) {
            $body = json_decode($message->body);

            if (empty($body->error)) {
                $data = $body->video;

                $this->updatePathMediaUseCase->execute(new UpdatePathMediaInput(
                    id: $data->id,
                    type: $data->type,
                    path: $data->path.'/stream.mpd'
                ));
            }
        };

        $this->aMQPInterface->consumer(
            queue: config('ms.queue_name'),
            exchange: config('ms.micro_encoder_go.exchange_producer'),
            callback: $callback
        );
    }
}
