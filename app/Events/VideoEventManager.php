<?php

namespace App\Events;

use BRCas\CA\Domain\Events\EventInterface;
use BRCas\MV\UseCases\Video\Interfaces\VideoEventManagerInterface;

class VideoEventManager implements VideoEventManagerInterface
{
    public function dispatch(EventInterface $event): void
    {
        //
    }
}
