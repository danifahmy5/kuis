@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Detail Peserta') }}</span>
                    <a href="{{ route('contestants.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>

                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Nama Peserta</th>
                            <td>{{ $contestant->name }}</td>
                        </tr>
                        <tr>
                            <th>Nama Tim</th>
                            <td>{{ $contestant->team_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $contestant->notes ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Terdaftar Pada</th>
                            <td>{{ $contestant->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>

                    <div class="mt-4">
                        <h5>Riwayat Lomba</h5>
                        <p class="text-muted small">Fitur ini akan menampilkan daftar lomba yang diikuti peserta.</p>
                        {{-- TODO: Tampilkan list event yang diikuti --}}
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('contestants.edit', $contestant->id) }}" class="btn btn-warning">Edit Data</a>
                        <form action="{{ route('contestants.destroy', $contestant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus peserta ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus Peserta</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
