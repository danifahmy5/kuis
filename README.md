# Aplikasi Kuis Berbasis Admin

Ini adalah aplikasi kuis yang dioperasikan oleh admin/operator, dibangun dengan Laravel.

## Petunjuk Instalasi

1.  **Clone repositori**

2.  **Instal dependensi**
    ```bash
    composer install
    ```

3.  **Buat file environment**
    ```bash
    cp .env.example .env
    ```

4.  **Hasilkan kunci aplikasi**
    ```bash
    php artisan key:generate
    ```

5.  **Konfigurasikan database Anda** di file `.env`. Secara default, aplikasi ini menggunakan MySQL.

6.  **Jalankan migrasi dan seeder database**
    ```bash
    php artisan migrate:fresh --seed
    ```

7.  **Jalankan server pengembangan**
    ```bash
    php artisan serve
    ```

## Cara Penggunaan

1.  **Akses panel admin** dengan membuka `/admin/login` di browser Anda. Ini akan secara otomatis membuat Anda masuk sebagai pengguna admin pertama.

2.  **Kelola acara** dari halaman `/admin/events`. Anda dapat membuat, melihat, mengedit, dan menghapus acara.

3.  **Kontrol sebuah acara** dari halaman detail acara. Anda dapat memulai/menghentikan acara, menampilkan pertanyaan, dan menandai jawaban.

4.  **Lihat tampilan publik** untuk sebuah acara dengan membuka `/display/{id_acara}`.
