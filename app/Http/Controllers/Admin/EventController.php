<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $event = Event::create([
            'title' => $request->title,
            'created_by' => Auth::guard('admin')->id(),
        ]);

        return redirect()->route('admin.events.show', $event);
    }

    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $event->update($request->only('title'));

        return redirect()->route('admin.events.show', $event);
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index');
    }

    public function start(Event $event)
    {
        // Start event logic
        return back();
    }

    public function stop(Event $event)
    {
        // Stop event logic
        return back();
    }
}
