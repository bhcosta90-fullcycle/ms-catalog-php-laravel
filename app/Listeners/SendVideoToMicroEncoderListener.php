<?php

namespace App\Listeners;

use App\Services\AMQP\AMQPInterface;
use BRCas\MV\Domain\Event\VideoCreateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendVideoToMicroEncoderListener
{
    /**
     * Create the event listener.
     */
    public function __construct(protected AMQPInterface $aMQPInterface)
    {
        //
    }

    public function handle(VideoCreateEvent $event): void
    {
        $this->aMQPInterface->producerFanout(
            payload: (array) $event->payload(),
            exchange: config('ms.micro_encoder_go.exchange')
        );

        \Log::info($event->name() . ': ' . json_encode((array) $event->payload()));
    }
}
