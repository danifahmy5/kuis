@extends('layouts.admin')

@section('content')
    <h1>Create Event</h1>

    <form action="{{ route('admin.events.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
