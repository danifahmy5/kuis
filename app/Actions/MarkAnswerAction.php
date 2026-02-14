<?php

namespace App\Actions;

use App\Models\Event;
use App\Services\EventEngine;

class MarkAnswerAction
{
    protected $eventEngine;

    public function __construct(EventEngine $eventEngine)
    {
        $this->eventEngine = $eventEngine;
    }

    public function __invoke(Event $event, int $sequence, array $contestantIds, bool $isCorrect)
    {
        return $this->eventEngine->markAnswers($event, $sequence, $contestantIds, $isCorrect);
    }
}
