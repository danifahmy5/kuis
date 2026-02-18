<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::with('creator')->latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:draft,running,paused,finished',
            'config' => 'nullable|json',
        ]);

        $event = new Event($validated);
        $event->created_by = Auth::id();
        $event->save();

        return redirect()->route('events.index')
            ->with('success', 'Acara berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load(['questions' => function ($query) {
            $query->orderBy('seq');
        }, 'contestants']);

        $currentQuestion = null;
        if (isset($event->config['current_question_seq'])) {
            $currentQuestion = $event->questions()
                ->wherePivot('seq', $event->config['current_question_seq'])
                ->with('options')
                ->first();
        }

        return view('admin.events.show', compact('event', 'currentQuestion'));
    }

    public function startIntro(Event $event)
    {
        $config = $event->config ?? [];
        $config['is_intro'] = true;
        $config['quiz_started'] = false;
        
        $event->update([
            'status' => 'running',
            'config' => $config,
            'started_at' => now(),
        ]);

        return back()->with('success', 'Acara dimulai (Intro).');
    }

    public function startQuiz(Event $event)
    {
        $config = $event->config ?? [];
        $config['is_intro'] = false;
        $config['quiz_started'] = true;
        $config['current_question_seq'] = 1;
        $config['question_state'] = 'blurred';
        $config['timer_started_at'] = null;
        $config['timer_stopped_at'] = null;

        $event->update(['config' => $config]);

        return back()->with('success', 'Kuis dimulai!');
    }

    public function nextQuestion(Event $event)
    {
        $config = $event->config ?? [];
        $maxSeq = $event->questions()->max('seq');
        
        if ($config['current_question_seq'] < $maxSeq) {
            $config['current_question_seq']++;
            $config['question_state'] = 'blurred';
            $config['timer_started_at'] = null;
            $config['timer_stopped_at'] = null;
            $event->update(['config' => $config]);
        }

        return back();
    }

    public function prevQuestion(Event $event)
    {
        $config = $event->config ?? [];
        
        if ($config['current_question_seq'] > 1) {
            $config['current_question_seq']--;
            $config['question_state'] = 'blurred';
            $config['timer_started_at'] = null;
            $config['timer_stopped_at'] = null;
            $event->update(['config' => $config]);
        }

        return back();
    }

    public function unblurQuestion(Event $event)
    {
        $config = $event->config ?? [];
        $config['question_state'] = 'unblurred';
        $config['timer_started_at'] = now()->timestamp;
        $config['timer_stopped_at'] = null;
        $event->update(['config' => $config]);

        return back();
    }

    public function stopTimer(Event $event)
    {
        $config = $event->config ?? [];
        $config['timer_stopped_at'] = now()->timestamp;
        $event->update(['config' => $config]);

        return back();
    }

    public function revealAnswer(Event $event)
    {
        $config = $event->config ?? [];
        $config['question_state'] = 'revealed';
        $event->update(['config' => $config]);

        return back();
    }

    public function awardPoints(Request $request, Event $event)
    {
        $request->validate([
            'contestant_ids' => 'required|array',
            'points' => 'required|integer',
        ]);

        $currentQuestion = $event->questions()
            ->wherePivot('seq', $event->config['current_question_seq'])
            ->first();

        foreach ($request->contestant_ids as $contestantId) {
            \App\Models\EventAnswer::updateOrCreate(
                [
                    'event_id' => $event->id,
                    'question_id' => $currentQuestion->id,
                    'contestant_id' => $contestantId,
                ],
                [
                    'is_correct' => true,
                    'marked_by' => auth()->id(),
                    'marked_at' => now(),
                    'points_awarded' => $request->points,
                ]
            );
        }

        return back()->with('success', 'Poin berhasil diberikan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:draft,running,paused,finished',
            'config' => 'nullable|json',
        ]);

        $event->update($validated);

        return redirect()->route('events.index')
            ->with('success', 'Acara berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Acara berhasil dihapus.');
    }
}
