<?php

namespace App\Listeners;

use BRCas\MV\Domain\Event\VideoCreateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendVideoToMicroEncoderListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handle(VideoCreateEvent $event): void
    {
        \Log::info($event->name() . ': ' . json_encode((array) $event->payload()));
    }
}
