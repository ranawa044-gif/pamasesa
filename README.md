# PAMASESA

Proyek Akhir : Manajemen Seminar dan Sidang Akhir untuk Program Studi D3 Sistem Informasi.

## Stack

- Laravel 12
- MySQL
- Blade Template
- Bootstrap 5
- Laravel Authentication
- Role Based Access Control

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Atur koneksi MySQL di `.env` sebelum menjalankan migrasi.

## Akun Seeder

- Admin: `admin@pamasesa.local` / `password`
- Mahasiswa: `mahasiswa@pamasesa.local` / `password`
- Dosen: `dosen@pamasesa.local` / `password`

## Alur MVP

1. Mahasiswa login dan mengajukan judul sesuai format Google Form seleksi awal: identitas, kontak, judul, objek/lokasi, masalah nyata, usulan pembimbing, rencana sistem, metode, teknologi, pengujian, dan pernyataan.
2. Admin melakukan ACC, revisi, atau tolak judul.
3. Admin menentukan Pembimbing 1 dan Pembimbing 2 setelah judul ACC.
4. Mahasiswa mengirim progress PA.
5. Dosen pembimbing melihat detail PA dan mereview progress.
