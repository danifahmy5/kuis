<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Aplikasi Kuis') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <!-- Scripts -->
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'sans-serif'],
                    },
                },
            },
        }
    </script>
</head>
<body class="antialiased bg-gray-50 text-slate-800 font-sans">
    <div class="relative min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav class="w-full bg-white shadow-sm z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">K</div>
                        <span class="font-bold text-xl tracking-tight text-slate-900">Aplikasi Kuis</span>
                    </div>
                    <div class="flex items-center gap-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/home') }}" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-sm">
                                    Log in
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <header class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-extrabold text-slate-900 sm:text-5xl sm:tracking-tight lg:text-6xl">
                    Aplikasi Kuis <span class="text-blue-600">Berbasis Admin</span>
                </h1>
                <p class="mt-4 max-w-xl mx-auto text-xl text-slate-500">
                    Aplikasi kuis interaktif yang dioperasikan oleh admin/operator, dibangun dengan performa dan kemudahan penggunaan menggunakan Laravel.
                </p>
                <div class="mt-8 flex justify-center gap-4">
                    @auth
                         <a href="{{ url('/home') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:text-lg transition-all shadow-md hover:shadow-lg">
                            Masuk ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:text-lg transition-all shadow-md hover:shadow-lg">
                            Mulai Sekarang
                        </a>
                    @endauth
                    <a href="#guide" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-slate-700 bg-white hover:bg-gray-50 md:text-lg transition-all">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main id="guide" class="flex-grow bg-gray-50">
            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    
                    <!-- Installation Guide -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                        <div class="p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900">Petunjuk Instalasi</h2>
                            </div>
                            
                            <ol class="relative border-l border-gray-200 ml-3 space-y-6">
                                <li class="mb-10 ml-6">
                                    <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white">
                                        <span class="text-blue-800 text-xs font-bold">1</span>
                                    </span>
                                    <h3 class="flex items-center mb-1 text-lg font-semibold text-slate-900">Clone & Install</h3>
                                    <p class="mb-2 text-base font-normal text-gray-500">Clone repositori ini dan instal dependensi.</p>
                                    <div class="bg-slate-800 rounded-md p-3 text-sm text-blue-300 font-mono overflow-x-auto">
                                        composer install
                                    </div>
                                </li>
                                <li class="mb-10 ml-6">
                                    <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white">
                                        <span class="text-blue-800 text-xs font-bold">2</span>
                                    </span>
                                    <h3 class="mb-1 text-lg font-semibold text-slate-900">Environment & Key</h3>
                                    <p class="mb-2 text-base font-normal text-gray-500">Salin file env dan generate key aplikasi.</p>
                                    <div class="bg-slate-800 rounded-md p-3 text-sm text-blue-300 font-mono overflow-x-auto space-y-1">
                                        <div>cp .env.example .env</div>
                                        <div>php artisan key:generate</div>
                                    </div>
                                </li>
                                <li class="mb-10 ml-6">
                                    <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white">
                                        <span class="text-blue-800 text-xs font-bold">3</span>
                                    </span>
                                    <h3 class="mb-1 text-lg font-semibold text-slate-900">Database Setup</h3>
                                    <p class="mb-2 text-base font-normal text-gray-500">Konfigurasi database di .env lalu jalankan migrasi.</p>
                                    <div class="bg-slate-800 rounded-md p-3 text-sm text-blue-300 font-mono overflow-x-auto">
                                        php artisan migrate:fresh --seed
                                    </div>
                                </li>
                                <li class="ml-6">
                                    <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white">
                                        <span class="text-blue-800 text-xs font-bold">4</span>
                                    </span>
                                    <h3 class="mb-1 text-lg font-semibold text-slate-900">Jalankan Server</h3>
                                    <div class="bg-slate-800 rounded-md p-3 text-sm text-blue-300 font-mono overflow-x-auto mt-2">
                                        php artisan serve
                                    </div>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <!-- Usage Guide -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                        <div class="p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900">Cara Penggunaan</h2>
                            </div>

                            <div class="space-y-6">
                                <div class="flex gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 font-bold">
                                            1
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-slate-900">Akses Panel Admin</h4>
                                        <p class="text-slate-600 mt-1">
                                            Buka <code class="bg-gray-100 px-1 py-0.5 rounded text-sm text-slate-800">/admin/login</code> untuk masuk sebagai admin. Akun admin pertama dibuat otomatis saat seeding.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 font-bold">
                                            2
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-slate-900">Kelola Acara</h4>
                                        <p class="text-slate-600 mt-1">
                                            Masuk ke menu <code class="bg-gray-100 px-1 py-0.5 rounded text-sm text-slate-800">/admin/events</code>. Di sini Anda dapat membuat, mengedit, dan menghapus acara kuis.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 font-bold">
                                            3
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-slate-900">Kontrol Acara</h4>
                                        <p class="text-slate-600 mt-1">
                                            Dari halaman detail acara, Anda memegang kendali penuh. Mulai/hentikan kuis, tampilkan pertanyaan ke layar publik, dan validasi jawaban peserta.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 font-bold">
                                            4
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-slate-900">Tampilan Publik</h4>
                                        <p class="text-slate-600 mt-1">
                                            Akses <code class="bg-gray-100 px-1 py-0.5 rounded text-sm text-slate-800">/display/{id_acara}</code> untuk menampilkan pertanyaan di proyektor atau layar besar bagi peserta.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-8 p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                                <h5 class="flex items-center font-bold text-yellow-800 gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                    Catatan Penting
                                </h5>
                                <p class="text-sm text-yellow-700 mt-2">
                                    Pastikan koneksi internet stabil jika memuat aset eksternal, atau jalankan <code>npm run build</code> untuk aset lokal.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 mt-auto">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-center text-sm text-slate-400">
                    &copy; {{ date('Y') }} Aplikasi Kuis. All rights reserved.
                </p>
                <div class="flex gap-6 text-sm text-slate-500">
                    <span class="hover:text-blue-600 cursor-pointer transition-colors">Privacy Policy</span>
                    <span class="hover:text-blue-600 cursor-pointer transition-colors">Terms of Service</span>
                    <span class="hover:text-blue-600 cursor-pointer transition-colors">Contact Support</span>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
