<?php

namespace Tests\Stubs;

use BRCas\CA\Domain\Events\EventInterface;
use BRCas\MV\UseCases\Video\Interfaces\VideoEventManagerInterface;

class VideoEventManagerStub implements VideoEventManagerInterface
{
    public function dispatch(EventInterface $event): void
    {
        event(self::class);

        $data = json_encode((array) $event->payload());
        \Log::info("Event: {$event->name()}: {$data}");
    }
}
