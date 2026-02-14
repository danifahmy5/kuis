<?php

namespace App\Actions;

use App\Models\Event;
use App\Services\EventEngine;

class StartEventAction
{
    protected $eventEngine;

    public function __construct(EventEngine $eventEngine)
    {
        $this->eventEngine = $eventEngine;
    }

    public function __invoke(Event $event)
    {
        return $this->eventEngine->startEvent($event);
    }
}
