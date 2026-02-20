<?php

namespace App\Http\Controllers;

use App\Models\EventAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function display(\App\Models\Event $event)
    {
        return view('display', compact('event'));
    }

    public function displayState(\App\Models\Event $event)
    {
        $event->load(['questions' => function ($query) {
            $query->orderByPivot('seq');
        }, 'contestants']);

        $currentQuestion = null;
        if ($event->current_question_seq) {
            $currentQuestion = $event->questions()
                ->wherePivot('seq', $event->current_question_seq)
                ->with('options')
                ->first();
        }

        $scores = EventAnswer::query()
            ->select('contestant_id', DB::raw('COALESCE(SUM(points_awarded), 0) as total_points'))
            ->where('event_id', $event->id)
            ->groupBy('contestant_id')
            ->pluck('total_points', 'contestant_id');

        $leaderboard = $event->contestants
            ->map(function ($contestant) use ($scores) {
                return [
                    'id' => $contestant->id,
                    'name' => $contestant->name,
                    'team_name' => $contestant->team_name,
                    'total_points' => (int) ($scores[$contestant->id] ?? 0),
                ];
            })
            ->sortByDesc('total_points')
            ->values();

        return response()->json([
            'event' => $event,
            'current_question' => $currentQuestion,
            'leaderboard' => $leaderboard,
        ]);
    }
}
