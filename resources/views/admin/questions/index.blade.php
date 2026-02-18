@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Bank Soal') }}</span>
                    <a href="{{ route('questions.create') }}" class="btn btn-primary btn-sm">Tambah Soal Baru</a>
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
                                <th>Pertanyaan</th>
                                <th>Tingkat Kesulitan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($questions as $question)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ Str::limit($question->question_text, 100) }}</td>
                                    <td>
                                        @if($question->difficulty_level == 1)
                                            <span class="badge bg-success">Mudah</span>
                                        @elseif($question->difficulty_level == 2)
                                            <span class="badge bg-info">Sedang</span>
                                        @elseif($question->difficulty_level == 3)
                                            <span class="badge bg-warning">Sulit</span>
                                        @elseif($question->difficulty_level == 4)
                                            <span class="badge bg-danger">Sangat Sulit</span>
                                        @else
                                            <span class="badge bg-dark">Expert</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('questions.show', $question->id) }}" class="btn btn-info btn-sm">Detail</a>
                                        <a href="{{ route('questions.edit', $question->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('questions.destroy', $question->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada soal.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $questions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
