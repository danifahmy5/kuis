@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ $event->title }}</h1>
        <div>
            <a href="{{ route('admin.events.leaderboard', $event) }}" class="btn btn-info">Leaderboard</a>
            <form action="{{ route('admin.events.start', $event) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">Start</button>
            </form>
            <form action="{{ route('admin.events.stop', $event) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger">Stop</button>
            </form>
        </div>
    </div>

    <h2>Questions</h2>
    {{-- Logic to display questions and controls to show/mark them --}}

    <hr>

    <h2>Contestants</h2>
    {{-- Logic to display contestants --}}
@endsection
