@extends('layouts.app')
@push('styles')
    <style>
        .rounded-4 {
            border-radius: 1rem !important;
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .ls-tight {
            letter-spacing: -0.5px;
        }

        .ls-1 {
            letter-spacing: 1px;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            transition: transform 0.2s;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        /* Custom Scrollbar for contestants */
        .card-body::-webkit-scrollbar {
            width: 6px;
        }

        .card-body::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 4px;
        }

        /* Animation for revealing answer */
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush
@section('content')
    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                @php
                    $showControlPanel = $event->status == 'running' && $event->quiz_started;
                @endphp

                <div class="row g-4 align-items-start">
                    <div class="{{ $showControlPanel ? 'col-xl-4' : 'col-12' }}">
                        <!-- Header Card -->
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h2 class="mb-1 fw-bold">{{ $event->title }}</h2>
                                        <div class="mt-2">
                                            @if ($event->status == 'draft')
                                                <span class="badge rounded-pill bg-secondary px-3">Draft</span>
                                            @elseif($event->status == 'running')
                                                <span class="badge rounded-pill bg-success px-3">Berlangsung</span>
                                            @elseif($event->status == 'paused')
                                                <span class="badge rounded-pill bg-warning text-dark px-3">Jeda</span>
                                            @elseif($event->status == 'finished')
                                                <span class="badge rounded-pill bg-dark px-3">Selesai</span>
                                            @endif
                                            <span class="text-muted ms-2 small"><i class="bi bi-clock"></i>
                                                {{ $event->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('events.index') }}" class="btn btn-outline-secondary btn-sm">
                                        &larr; Kembali
                                    </a>
                                </div>

                                <hr class="my-4">

                                <div class="row g-4">
                                    <div class="col-md-6 {{ $showControlPanel ? 'col-xl-12' : '' }}">
                                        <h6 class="text-muted text-uppercase small fw-bold mb-2">Informasi Penyelenggara</h6>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3 text-center"
                                                style="width: 45px; height: 45px;">
                                                <span class="fw-bold text-primary">{{ substr($event->creator->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-bold text-dark">{{ $event->creator->name }}</p>
                                                <small class="text-muted">Creator</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 {{ $showControlPanel ? 'col-xl-12' : '' }}">
                                        <h6 class="text-muted text-uppercase small fw-bold mb-2">Daftar Peserta</h6>
                                        @if ($event->contestants->isEmpty())
                                            <span class="text-muted fst-italic">Belum ada peserta terdaftar.</span>
                                        @else
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($event->contestants as $contestant)
                                                    <span class="badge bg-light text-dark border border-light-subtle">
                                                        {{ $contestant->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Action Bar -->
                            <div class="card-footer bg-white border-top p-3">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        @if ($event->status == 'draft')
                                            <form action="{{ route('events.start-intro', $event->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-play-fill"></i> Mulai Acara (Intro)
                                                </button>
                                            </form>
                                        @endif

                                        @if ($event->status == 'running' && $event->is_intro)
                                            <form action="{{ route('events.start-quiz', $event->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success">
                                                    <i class="bi bi-lightning-fill"></i> Mulai Kuis
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <div class="btn-group" role="group">
                                        <a href="{{ route('events.edit', $event->id) }}"
                                            class="btn btn-outline-warning text-dark">
                                            Edit
                                        </a>

                                        <form action="{{ route('events.destroy', $event->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin ingin menghapus acara ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-outline-danger border-start-0 rounded-0 rounded-end">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($showControlPanel)
                        <div class="col-xl-8">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h5 class="fw-bold text-dark m-0 ls-tight">
                                        <i class="bi bi-grid-1x2-fill text-primary me-2"></i>Control Panel
                                    </h5>
                                    <small class="text-muted">Event ID: #{{ $event->id }}</small>
                                </div>
                                <a href="{{ route('display', $event->id) }}" target="_blank"
                                    class="btn btn-light bg-white border shadow-sm rounded-pill px-4 text-secondary hover-lift">
                                    <i class="bi bi-box-arrow-up-right me-2"></i>Layar Audience
                                </a>
                            </div>

                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                                <div
                                    class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                                    <form action="{{ route('events.prev-question', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary rounded-pill px-3"
                                            {{ $event->current_question_seq <= 1 ? 'disabled' : '' }}
                                            data-bs-toggle="tooltip" title="Soal Sebelumnya">
                                            <i class="bi bi-chevron-left me-1"></i>Sebelumnya
                                        </button>
                                    </form>

                                    <div class="text-center">
                                        <span class="d-block text-uppercase text-muted fw-bold"
                                            style="font-size: 0.75rem; letter-spacing: 2px;">Soal No</span>
                                        <span
                                            class="fs-3 fw-bold text-primary lh-1">{{ $event->current_question_seq }}</span>
                                    </div>

                                    <form action="{{ route('events.next-question', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary rounded-pill px-3"
                                            {{ $event->current_question_seq >= $event->questions()->max('seq') ? 'disabled' : '' }}
                                            data-bs-toggle="tooltip" title="Soal Selanjutnya">
                                            Selanjutnya<i class="bi bi-chevron-right ms-1"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="card-body p-md-5 text-center">
                                    @if ($currentQuestion)

                                        <div >
                                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-normal">
                                                <i class="bi bi-clock me-1 text-danger"></i> Durasi:
                                                <strong>{{ $currentQuestion->duration }}s</strong>
                                            </span>
                                        </div>

                                        <h2 class="display-6 fw-bold text-dark lh-base">
                                            {{ $currentQuestion->question_text }}
                                        </h2>

                                        <div class="d-flex justify-content-center gap-3 mb-4">
                                            @if ($event->question_state == 'blurred')
                                                <form action="{{ route('events.unblur-question', $event->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm py-3 transition-all">
                                                        <i class="bi bi-eye me-2"></i>Tampilkan Soal
                                                    </button>
                                                </form>
                                            @elseif($event->question_state == 'unblurred')
                                                <form action="{{ route('events.stop-timer', $event->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-danger btn-lg rounded-pill px-4 py-3 shadow-sm">
                                                        <i class="bi bi-stop-circle me-2"></i>Stop Waktu
                                                    </button>
                                                </form>
                                                <form action="{{ route('events.reveal-answer', $event->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-success btn-lg rounded-pill px-4 py-3 shadow-sm">
                                                        <i class="bi bi-check-lg me-2"></i>Buka Jawaban
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        @if ($event->question_state == 'revealed')
                                            <div class="animate-fade-in mt-4">
                                                <div class="alert alert-success bg-success bg-opacity-10 border-success border-opacity-25 rounded-3 d-inline-block text-start px-4 py-3 mb-5"
                                                    style="min-width: 300px;">
                                                    <div class="text-uppercase text-success fw-bold small mb-2 opacity-75">
                                                        Jawaban Benar</div>
                                                    @foreach ($currentQuestion->options as $option)
                                                        @if ($option->is_correct)
                                                            <div class="fs-4 text-dark">
                                                                <span
                                                                    class="badge bg-success me-2">{{ $option->label }}</span>
                                                                {{ $option->option_text }}
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>

                                                <hr class="border-light my-4">

                                                <div class="row justify-content-center">
                                                    <div class="col-md-8">
                                                        <h6 class="fw-bold text-muted text-uppercase mb-3 ls-1">Berikan
                                                            Poin</h6>

                                                        <form action="{{ route('events.award-points', $event->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="card border bg-light mb-3">
                                                                <div class="card-body p-0"
                                                                    style="max-height: 200px; overflow-y: auto;">
                                                                    <ul class="list-group list-group-flush text-start">
                                                                        @foreach ($event->contestants as $contestant)
                                                                            <li class="list-group-item bg-transparent">
                                                                                <div class="form-check custom-checkbox">
                                                                                    <input class="form-check-input"
                                                                                        type="checkbox"
                                                                                        name="contestant_ids[]"
                                                                                        value="{{ $contestant->id }}"
                                                                                        id="c-{{ $contestant->id }}">
                                                                                    <label
                                                                                        class="form-check-label w-100 stretched-link"
                                                                                        for="c-{{ $contestant->id }}">
                                                                                        {{ $contestant->name }}
                                                                                    </label>
                                                                                </div>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                                <div class="card-footer bg-white border-top p-2">
                                                                    <div class="input-group">
                                                                        <span
                                                                            class="input-group-text bg-white border-end-0 text-muted">Poin</span>
                                                                        <input type="number" name="points"
                                                                            class="form-control border-start-0"
                                                                            value="10" required>
                                                                        <button class="btn btn-primary px-4"
                                                                            type="submit">Kirim</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
