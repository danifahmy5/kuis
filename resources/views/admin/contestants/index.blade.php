@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Daftar Peserta') }}</span>
                    <a href="{{ route('contestants.create') }}" class="btn btn-primary btn-sm">Tambah Peserta Baru</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Peserta</th>
                                <th>Nama Tim (Opsional)</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($contestants as $contestant)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $contestant->name }}</td>
                                    <td>{{ $contestant->team_name ?? '-' }}</td>
                                    <td>{{ Str::limit($contestant->notes, 50) }}</td>
                                    <td>
                                        <a href="{{ route('contestants.show', $contestant->id) }}" class="btn btn-info btn-sm">Detail</a>
                                        <a href="{{ route('contestants.edit', $contestant->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('contestants.destroy', $contestant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus peserta ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada peserta terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $contestants->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
