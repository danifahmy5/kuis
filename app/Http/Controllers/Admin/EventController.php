<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contestant;
use App\Models\Event;
use Illuminate\Support\Carbon;
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
        $contestants = Contestant::orderBy('name')->get();
        return view('admin.events.create', compact('contestants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'started_at' => 'required|date',
            'status' => 'required|in:draft,running,paused,finished',
            'contestant_ids' => 'nullable|array',
            'contestant_ids.*' => 'integer|exists:contestants,id',
        ]);

        $validated['started_at'] = Carbon::parse($validated['started_at']);
        $event = new Event($validated);
        $event->created_by = Auth::id();
        $event->save();
        $event->contestants()->sync($request->input('contestant_ids', []));

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
        if ($event->current_question_seq) {
            $currentQuestion = $event->questions()
                ->wherePivot('seq', $event->current_question_seq)
                ->with('options')
                ->first();
        }

        return view('admin.events.show', compact('event', 'currentQuestion'));
    }

    public function startIntro(Event $event)
    {
        $startedAt = $event->started_at ?? now();
        $event->update([
            'status' => 'running',
            'is_intro' => true,
            'quiz_started' => false,
            'current_question_seq' => null,
            'question_state' => null,
            'timer_started_at' => null,
            'timer_stopped_at' => null,
            'started_at' => $startedAt,
        ]);

        return back()->with('success', 'Acara dimulai (Intro).');
    }

    public function startQuiz(Event $event)
    {
        $event->update([
            'is_intro' => false,
            'quiz_started' => true,
            'current_question_seq' => 1,
            'question_state' => 'blurred',
            'timer_started_at' => null,
            'timer_stopped_at' => null,
        ]);

        return back()->with('success', 'Kuis dimulai!');
    }

    public function nextQuestion(Event $event)
    {
        $maxSeq = $event->questions()->max('seq');

        if ($event->current_question_seq && $event->current_question_seq < $maxSeq) {
            $event->update([
                'current_question_seq' => $event->current_question_seq + 1,
                'question_state' => 'blurred',
                'timer_started_at' => null,
                'timer_stopped_at' => null,
            ]);
        }

        return back();
    }

    public function prevQuestion(Event $event)
    {
        if ($event->current_question_seq && $event->current_question_seq > 1) {
            $event->update([
                'current_question_seq' => $event->current_question_seq - 1,
                'question_state' => 'blurred',
                'timer_started_at' => null,
                'timer_stopped_at' => null,
            ]);
        }

        return back();
    }

    public function unblurQuestion(Event $event)
    {
        $event->update([
            'question_state' => 'unblurred',
            'timer_started_at' => now()->timestamp,
            'timer_stopped_at' => null,
        ]);

        return back();
    }

    public function stopTimer(Event $event)
    {
        $event->update([
            'timer_stopped_at' => now()->timestamp,
        ]);

        return back();
    }

    public function revealAnswer(Event $event)
    {
        $event->update([
            'question_state' => 'revealed',
        ]);

        return back();
    }

    public function awardPoints(Request $request, Event $event)
    {
        $request->validate([
            'contestant_ids' => 'required|array',
            'points' => 'required|integer',
        ]);

        $currentQuestion = $event->questions()
            ->wherePivot('seq', $event->current_question_seq)
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
        $contestants = Contestant::orderBy('name')->get();
        $selectedContestantIds = $event->contestants()->pluck('contestants.id')->toArray();
        return view('admin.events.edit', compact('event', 'contestants', 'selectedContestantIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'started_at' => 'required|date',
            'status' => 'required|in:draft,running,paused,finished',
            'contestant_ids' => 'nullable|array',
            'contestant_ids.*' => 'integer|exists:contestants,id',
        ]);

        $validated['started_at'] = Carbon::parse($validated['started_at']);
        $event->update($validated);
        $event->contestants()->sync($request->input('contestant_ids', []));

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
