<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            $query->orderBy('seq');
        }]);

        $currentQuestion = null;
        if (isset($event->config['current_question_seq'])) {
            $currentQuestion = $event->questions()
                ->wherePivot('seq', $event->config['current_question_seq'])
                ->with('options')
                ->first();
        }

        return response()->json([
            'event' => $event,
            'current_question' => $currentQuestion,
        ]);
    }
}
