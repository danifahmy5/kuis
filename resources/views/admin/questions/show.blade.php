@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Detail Soal') }}</span>
                    <a href="{{ route('questions.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h5>Pertanyaan</h5>
                        <p class="lead">{{ $question->question_text }}</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Tingkat Kesulitan:</strong>
                            @if($question->difficulty_level == 1)
                                <span class="badge bg-success">Mudah</span>
                            @elseif($question->difficulty_level == 2)
                                <span class="badge bg-info">Sedang</span>
                            @elseif($question->difficulty_level == 3)
                                <span class="badge bg-warning">Sulit</span>
                            @elseif($question->difficulty_level == 4)
                                <span class="badge bg-danger">Sangat Sulit</span>
                            @else
                                <span class="badge bg-dark">Expert</span>
                            @endif
                        </div>
                    </div>

                    @if($question->explanation)
                    <div class="alert alert-info">
                        <strong>Penjelasan:</strong><br>
                        {{ $question->explanation }}
                    </div>
                    @endif

                    <hr>
                    <h5>Pilihan Jawaban</h5>
                    <ul class="list-group">
                        @foreach($question->options as $option)
                            <li class="list-group-item {{ $option->is_correct ? 'list-group-item-success' : '' }}">
                                <span class="fw-bold me-2">{{ $option->label }}.</span>
                                {{ $option->option_text }}
                                @if($option->is_correct)
                                    <span class="badge bg-success float-end">Kunci Jawaban</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-4">
                        <a href="{{ route('questions.edit', $question->id) }}" class="btn btn-warning">Edit Soal</a>
                        <form action="{{ route('questions.destroy', $question->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus Soal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
