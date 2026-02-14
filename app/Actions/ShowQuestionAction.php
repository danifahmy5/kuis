<?php

namespace App\Actions;

use App\Models\Event;
use App\Services\EventEngine;

class ShowQuestionAction
{
    protected $eventEngine;

    public function __construct(EventEngine $eventEngine)
    {
        $this->eventEngine = $eventEngine;
    }

    public function __invoke(Event $event, int $sequence)
    {
        return $this->eventEngine->showQuestion($event, $sequence);
    }
}
