@extends('layouts.app')

@push('styles')
    <style>
        .feature-icon-1 {
            font-size: 40px;
            margin-bottom: 1rem;
        }

        .report-card {
            border-radius: 16px;
            transition: all 0.3s ease;
            border: none;
            overflow: hidden;
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1) !important;
        }

        .report-card .report-main-icon {
            width: 54px;
            height: 54px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .report-card.bg-primary-subtle .report-main-icon { background: var(--primary-color); }
        .report-card.bg-success-subtle .report-main-icon { background: #2ec4b6; }
        .report-card.bg-danger-subtle .report-main-icon { background: #e71d36; }

        .quick-menu-card {
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .quick-menu-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.05) !important;
        }

        .welcome-card {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            color: white;
            border-radius: 16px;
        }

    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <!-- Page-Title -->
        <div class="row mb-4">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title fw-bold">Dashboard Overview</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Kuis</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card welcome-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8 text-light">
                                <h2 class="fw-bold mb-1">Selamat Datang Kembali, {{ Auth::user()->name }}! 👋</h2>
                                <p class="opacity-75 mb-0">Kelola acara, soal, dan peserta kuis Anda dari satu tempat yang efisien.</p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="{{ route('events.create') }}" class="btn btn-light text-primary fw-bold">
                                    <i class="fas fa-plus-circle me-1"></i> Mulai Acara Baru
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-4">
                <div class="card report-card bg-primary-subtle shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-primary mb-1 fw-bold text-uppercase small">Total Acara</p>
                                <h2 class="m-0 fw-bold text-dark">10</h2>
                            </div>
                            <div class="report-main-icon shadow-sm">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card report-card bg-success-subtle shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-success mb-1 fw-bold text-uppercase small">Total Soal</p>
                                <h2 class="m-0 fw-bold text-dark">150</h2>
                            </div>
                            <div class="report-main-icon shadow-sm">
                                <i class="fas fa-book text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card report-card bg-danger-subtle shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-danger mb-1 fw-bold text-uppercase small">Total Peserta</p>
                                <h2 class="m-0 fw-bold text-dark">340</h2>
                            </div>
                            <div class="report-main-icon shadow-sm">
                                <i class="fas fa-users text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <h5 class="fw-bold mb-3"><i class="fas fa-rocket me-2"></i>Akses Cepat</h5>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card text-center quick-menu-card h-100 shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="feature-icon-1 text-primary">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Kelola Acara</h5>
                        <p class="text-muted small mb-4">Buat, pantau, dan selesaikan acara kuis yang sedang berlangsung.</p>
                        <a href="{{ route('events.index') }}" class="btn btn-outline-primary w-100">Buka Manajemen Acara</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card text-center quick-menu-card h-100 shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="feature-icon-1 text-success">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Bank Soal</h5>
                        <p class="text-muted small mb-4">Atur koleksi pertanyaan untuk berbagai tingkat kesulitan.</p>
                        <a href="{{ route('questions.index') }}" class="btn btn-outline-success w-100">Lihat Semua Soal</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card text-center quick-menu-card h-100 shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="feature-icon-1 text-danger">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Manajemen Peserta</h5>
                        <p class="text-muted small mb-4">Daftarkan peserta baru atau edit informasi yang sudah ada.</p>
                        <a href="{{ route('contestants.index') }}" class="btn btn-outline-danger w-100">Kelola Daftar Peserta</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

