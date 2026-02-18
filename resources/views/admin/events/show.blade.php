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
                        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning">Edit Acara</a>
                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus acara ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus Acara</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
