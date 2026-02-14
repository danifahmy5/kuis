<?php

namespace App\Services;

use App\Models\Event;

class EventEngine
{
    public function startEvent(Event $event)
    {
        // Logic to start an event
    }

    public function showQuestion(Event $event, int $sequence)
    {
        // Logic to show a question
    }

    public function markAnswers(Event $event, int $sequence, array $contestantIds, bool $isCorrect)
    {
        // Logic to mark answers
    }

    public function getLeaderboard(Event $event)
    {
        // Logic to get the leaderboard
    }
}
