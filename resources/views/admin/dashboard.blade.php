@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard Admin') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h3>Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p>Anda telah masuk sebagai Administrator.</p>
                    
                    <div class="mt-4">
                        <h5>Menu Cepat</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Kelola Acara</h5>
                                        <p class="card-text">Buat dan atur acara kuis baru.</p>
                                        <a href="{{ route('events.index') }}" class="btn btn-primary">Buka Acara</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Bank Soal</h5>
                                        <p class="card-text">Tambah dan edit koleksi soal.</p>
                                        <a href="{{ route('questions.index') }}" class="btn btn-primary">Lihat Soal</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Peserta</h5>
                                        <p class="card-text">Kelola data peserta lomba.</p>
                                        <a href="{{ route('contestants.index') }}" class="btn btn-primary">Lihat Peserta</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
