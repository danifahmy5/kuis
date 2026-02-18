@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Detail Acara') }}</span>
                    <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>

                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Judul Acara</th>
                            <td>{{ $event->title }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($event->status == 'draft')
                                    <span class="badge bg-secondary">Draft</span>
                                @elseif($event->status == 'running')
                                    <span class="badge bg-success">Berlangsung</span>
                                @elseif($event->status == 'paused')
                                    <span class="badge bg-warning">Jeda</span>
                                @elseif($event->status == 'finished')
                                    <span class="badge bg-dark">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Dibuat Oleh</th>
                            <td>{{ $event->creator->name }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat Pada</th>
                            <td>{{ $event->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Konfigurasi</th>
                            <td>
                                <pre>{{ json_encode($event->config, JSON_PRETTY_PRINT) }}</pre>
                            </td>
                        </tr>
                    </table>

                    <div class="mt-4">
                        @if($event->status == 'draft')
                            <form action="{{ route('events.start-intro', $event->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary">Mulai Acara (Intro)</button>
                            </form>
                        @endif

                        @if($event->status == 'running' && ($event->config['is_intro'] ?? false))
                            <form action="{{ route('events.start-quiz', $event->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">Mulai Kuis</button>
                            </form>
                        @endif

                        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning">Edit Acara</a>
                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus acara ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus Acara</button>
                        </form>
                    </div>

                    @if($event->status == 'running' && ($event->config['quiz_started'] ?? false))
                        <hr>
                        <h4>Kontrol Kuis</h4>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="btn-group">
                                    <form action="{{ route('events.prev-question', $event->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary" {{ $event->config['current_question_seq'] <= 1 ? 'disabled' : '' }}>Sebelumnya</button>
                                    </form>
                                    <form action="{{ route('events.next-question', $event->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary" {{ $event->config['current_question_seq'] >= $event->questions()->max('seq') ? 'disabled' : '' }}>Selanjutnya</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('display', $event->id) }}" target="_blank" class="btn btn-info">Buka Display Audience</a>
                            </div>
                        </div>

                        @if($currentQuestion)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Soal Ke-{{ $event->config['current_question_seq'] }}</h5>
                                    <p class="card-text">{{ $currentQuestion->question_text }}</p>
                                    
                                    <div class="mt-3">
                                        @if($event->config['question_state'] == 'blurred')
                                            <form action="{{ route('events.unblur-question', $event->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Mulai Soal (Unblur)</button>
                                            </form>
                                        @elseif($event->config['question_state'] == 'unblurred')
                                            <form action="{{ route('events.stop-timer', $event->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-warning">Stop Timer</button>
                                            </form>
                                            <form action="{{ route('events.reveal-answer', $event->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Tampilkan Jawaban</button>
                                            </form>
                                        @elseif($event->config['question_state'] == 'revealed')
                                            <div class="alert alert-success">
                                                <strong>Jawaban Benar:</strong> 
                                                @foreach($currentQuestion->options as $option)
                                                    @if($option->is_correct)
                                                        {{ $option->label }}. {{ $option->option_text }}
                                                    @endif
                                                @endforeach
                                            </div>

                                            <h6>Beri Poin ke Peserta:</h6>
                                            <form action="{{ route('events.award-points', $event->id) }}" method="POST">
                                                @csrf
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-auto">
                                                        <select name="contestant_ids[]" class="form-select" multiple required>
                                                            @foreach($event->contestants as $contestant)
                                                                <option value="{{ $contestant->id }}">{{ $contestant->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-auto">
                                                        <input type="number" name="points" class="form-control" placeholder="Poin" value="10" required>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="submit" class="btn btn-primary">Beri Poin</button>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
