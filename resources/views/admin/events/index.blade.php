@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold m-0"><i class="fas fa-calendar-alt me-2 text-primary"></i>Daftar Acara Kuis</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Acara</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ route('events.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-1"></i> Tambah Acara Baru
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Judul Acara</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th class="text-end pe-4" style="width: 250px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events as $event)
                            <tr>
                                <td class="ps-4 fw-medium text-muted">{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $event->title }}</td>
                                <td>
                                    @if($event->status == 'draft')
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                            <i class="fas fa-edit me-1 small"></i> Draft
                                        </span>
                                    @elseif($event->status == 'running')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="fas fa-play me-1 small"></i> Berlangsung
                                        </span>
                                    @elseif($event->status == 'paused')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                            <i class="fas fa-pause me-1 small"></i> Jeda
                                        </span>
                                    @elseif($event->status == 'finished')
                                        <span class="badge bg-dark-subtle text-dark border border-dark-subtle">
                                            <i class="fas fa-check-circle me-1 small"></i> Selesai
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            <i class="fas fa-user text-primary small"></i>
                                        </div>
                                        <span>{{ $event->creator->name }}</span>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group shadow-sm">
                                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus acara ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-calendar-times fa-3x mb-3 opacity-25"></i>
                                        <p>Belum ada acara kuis yang dibuat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($events->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
