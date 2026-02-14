<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class DisplayController extends Controller
{
    public function show(Event $event)
    {
        return view('display', compact('event'));
    }
}
