@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Events</h1>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">Create Event</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Started At</th>
                <th>Finished At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($events as $event)
                <tr>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->status }}</td>
                    <td>{{ $event->started_at }}</td>
                    <td>{{ $event->finished_at }}</td>
                    <td>
                        <a href="{{ route('admin.events.show', $event) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
