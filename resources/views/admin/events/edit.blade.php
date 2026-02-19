@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Acara') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('events.update', $event->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="title" class="col-md-4 col-form-label text-md-end">{{ __('Judul Acara') }}</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $event->title) }}" required autofocus>

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="status" class="col-md-4 col-form-label text-md-end">{{ __('Status') }}</label>

                            <div class="col-md-6">
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="draft" {{ $event->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="running" {{ $event->status == 'running' ? 'selected' : '' }}>Berlangsung</option>
                                    <option value="paused" {{ $event->status == 'paused' ? 'selected' : '' }}>Jeda</option>
                                    <option value="finished" {{ $event->status == 'finished' ? 'selected' : '' }}>Selesai</option>
                                </select>

                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="started_at" class="col-md-4 col-form-label text-md-end">{{ __('Mulai') }}</label>

                            <div class="col-md-6">
                                <input id="started_at" type="datetime-local" class="form-control @error('started_at') is-invalid @enderror" name="started_at" value="{{ old('started_at', optional($event->started_at)->format('Y-m-d\\TH:i')) }}" required>

                                @error('started_at')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">{{ __('Peserta') }}</label>
                            <div class="col-md-6">
                                <input type="text" id="contestant-search" class="form-control mb-2" placeholder="Cari peserta...">
                                <div class="border rounded p-2" style="max-height: 260px; overflow-y: auto;">
                                    @forelse($contestants as $contestant)
                                        <div class="form-check contestant-item" data-name="{{ strtolower($contestant->name) }}">
                                            <input class="form-check-input" type="checkbox" name="contestant_ids[]" value="{{ $contestant->id }}" id="contestant-{{ $contestant->id }}"
                                                {{ in_array($contestant->id, old('contestant_ids', $selectedContestantIds ?? [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="contestant-{{ $contestant->id }}">
                                                {{ $contestant->name }}
                                            </label>
                                        </div>
                                    @empty
                                        <div class="text-muted">Belum ada peserta.</div>
                                    @endforelse
                                </div>
                                @error('contestant_ids')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Perbarui Acara') }}
                                </button>
                                <a href="{{ route('events.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    (function () {
        const search = document.getElementById('contestant-search');
        const items = document.querySelectorAll('.contestant-item');
        if (!search) return;
        search.addEventListener('input', () => {
            const q = search.value.trim().toLowerCase();
            items.forEach(item => {
                const name = item.getAttribute('data-name') || '';
                item.style.display = name.includes(q) ? '' : 'none';
            });
        });
    })();
</script>
@endpush
@endsection
