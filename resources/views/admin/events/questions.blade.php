@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold m-0">Kelola Soal</h2>
            <div class="text-muted">Acara: <strong>{{ $event->title }}</strong></div>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                <a href="{{ route('events.questions.template', $event->id) }}" class="btn btn-outline-primary">
                    Download Template
                </a>
                <form method="POST" action="{{ route('events.questions.import', $event->id) }}" enctype="multipart/form-data" class="d-flex gap-2 align-items-center">
                    @csrf
                    <input type="file" name="file" accept=".xlsx,.csv" class="form-control form-control-sm" required>
                    <button type="submit" class="btn btn-success btn-sm">Import</button>
                </form>
                <a href="{{ route('events.questions.index') }}" class="btn btn-outline-secondary">
                    &larr; Kembali
                </a>
                <a href="{{ route('events.show', $event->id) }}" class="btn btn-outline-info">
                    Detail Acara
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->has('import'))
                <div class="alert alert-danger">{{ $errors->first('import') }}</div>
            @endif
            <form method="POST" action="{{ route('events.questions.update', $event->id) }}">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <label class="col-md-3 col-form-label text-md-end">Cari Soal</label>
                    <div class="col-md-7">
                        <input type="text" id="question-search" class="form-control" placeholder="Ketik untuk mencari soal...">
                        <div class="form-text text-muted">Centang soal dan atur urutan sesuai kebutuhan.</div>
                    </div>
                </div>

                <div class="row mb-4">
                    <label class="col-md-3 col-form-label text-md-end">Daftar Soal</label>
                    <div class="col-md-7">
                        <div class="border rounded p-3" style="max-height: 420px; overflow-y: auto;">
                            @forelse($questions as $question)
                                @php
                                    $selectedQuestions = old('question_ids', $selectedQuestionIds ?? []);
                                    $seqValue = old('question_seq.' . $question->id, $questionSeqs[$question->id] ?? null);
                                @endphp
                                <div class="d-flex align-items-start gap-2 mb-3 question-item" data-text="{{ strtolower($question->question_text) }}">
                                    <div class="form-check mt-1">
                                        <input class="form-check-input" type="checkbox" name="question_ids[]" value="{{ $question->id }}" id="question-{{ $question->id }}"
                                            {{ in_array($question->id, $selectedQuestions) ? 'checked' : '' }}>
                                    </div>
                                    <label class="form-check-label flex-grow-1" for="question-{{ $question->id }}">
                                        <div class="fw-semibold">{{ Str::limit($question->question_text, 160) }}</div>
                                        <small class="text-muted">Durasi: {{ $question->duration }} detik</small>
                                    </label>
                                    <input type="number" class="form-control form-control-sm" style="width: 90px;"
                                        name="question_seq[{{ $question->id }}]" min="1" placeholder="Urutan"
                                        value="{{ $seqValue }}">
                                </div>
                            @empty
                                <div class="text-muted">Belum ada soal.</div>
                            @endforelse
                        </div>
                        <div class="form-text text-muted">Urutan akan diurutkan dan dinormalisasi menjadi 1..N.</div>
                        @error('question_ids')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-7 offset-md-3">
                        <button type="submit" class="btn btn-primary">Simpan Soal Acara</button>
                        <a href="{{ route('events.questions.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    (function () {
        const search = document.getElementById('question-search');
        const items = document.querySelectorAll('.question-item');
        if (!search) return;
        search.addEventListener('input', () => {
            const q = search.value.trim().toLowerCase();
            items.forEach(item => {
                const text = item.getAttribute('data-text') || '';
                item.style.display = text.includes(q) ? '' : 'none';
            });
        });
    })();
</script>
@endpush
@endsection
