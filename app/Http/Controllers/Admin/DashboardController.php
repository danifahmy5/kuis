<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contestant;
use App\Models\Event;
use App\Models\Question;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalEvents' => Event::count(),
            'totalQuestions' => Question::count(),
            'totalContestants' => Contestant::count(),
        ]);
    }
}
