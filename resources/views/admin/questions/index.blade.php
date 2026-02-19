@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold m-0"><i class="fas fa-book me-2 text-success"></i>Bank Soal</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Bank Soal</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ route('questions.create') }}" class="btn btn-success shadow-sm text-white">
                <i class="fas fa-plus me-1"></i> Tambah Soal Baru
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
                            <th>Pertanyaan</th>
                            <th>Tingkat Kesulitan</th>
                            <th>Durasi</th>
                            <th class="text-end pe-4" style="width: 250px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($questions as $question)
                            <tr>
                                <td class="ps-4 fw-medium text-muted">{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ Str::limit($question->question_text, 80) }}</td>
                                <td>
                                    @if($question->difficulty_level == 1)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="fas fa-leaf me-1 small"></i> Mudah
                                        </span>
                                    @elseif($question->difficulty_level == 2)
                                        <span class="badge bg-info-subtle text-info border border-info-subtle">
                                            <i class="fas fa-seedling me-1 small"></i> Sedang
                                        </span>
                                    @elseif($question->difficulty_level == 3)
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                            <i class="fas fa-fire me-1 small"></i> Sulit
                                        </span>
                                    @elseif($question->difficulty_level == 4)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                            <i class="fas fa-skull me-1 small"></i> Sangat Sulit
                                        </span>
                                    @else
                                        <span class="badge bg-dark-subtle text-dark border border-dark-subtle">
                                            <i class="fas fa-crown me-1 small"></i> Expert
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $question->duration }} detik</td>
                                <td class="text-end pe-4">
                                    <div class="btn-group shadow-sm">
                                        <a href="{{ route('questions.show', $question->id) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('questions.edit', $question->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('questions.destroy', $question->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
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
                                        <i class="fas fa-book-open fa-3x mb-3 opacity-25"></i>
                                        <p>Belum ada soal kuis yang dibuat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($questions->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $questions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
