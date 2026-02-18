@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold m-0"><i class="fas fa-users me-2 text-danger"></i>Daftar Peserta</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Peserta</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ route('contestants.create') }}" class="btn btn-danger shadow-sm">
                <i class="fas fa-user-plus me-1"></i> Tambah Peserta Baru
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
                            <th>Nama Peserta</th>
                            <th>Tim / Instansi</th>
                            <th>Catatan</th>
                            <th class="text-end pe-4" style="width: 250px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contestants as $contestant)
                            <tr>
                                <td class="ps-4 fw-medium text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center me-3" style="width: 38px; height: 38px;">
                                            <i class="fas fa-user text-danger"></i>
                                        </div>
                                        <div class="fw-bold">{{ $contestant->name }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if($contestant->team_name)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                            <i class="fas fa-users me-1 small"></i> {{ $contestant->team_name }}
                                        </span>
                                    @else
                                        <span class="text-muted small">Individu</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ Str::limit($contestant->notes, 40) ?: '-' }}</td>
                                <td class="text-end pe-4">
                                    <div class="btn-group shadow-sm">
                                        <a href="{{ route('contestants.show', $contestant->id) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('contestants.edit', $contestant->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('contestants.destroy', $contestant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus peserta ini?')">
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
                                        <i class="fas fa-users-slash fa-3x mb-3 opacity-25"></i>
                                        <p>Belum ada peserta yang terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($contestants->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $contestants->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
