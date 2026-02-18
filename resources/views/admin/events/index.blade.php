@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Daftar Acara Kuis') }}</span>
                    <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm">Tambah Acara Baru</a>
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
                                <th>Judul Acara</th>
                                <th>Status</th>
                                <th>Dibuat Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($events as $event)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $event->title }}</td>
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
                                    <td>{{ $event->creator->name }}</td>
                                    <td>
                                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-info btn-sm">Detail</a>
                                        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus acara ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada acara kuis.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
