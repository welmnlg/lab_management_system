# Lab Management System

Sistem informasi berbasis web untuk membantu pengelolaan laboratorium, dibangun menggunakan **Laravel**. <!-- TODO: sesuaikan 1-2 kalimat ini dengan tujuan aplikasi Anda, misalnya: pengelolaan jadwal, inventaris alat, peminjaman, dan laporan praktikum. -->

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-06B6D4?logo=tailwindcss&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-646CFF?logo=vite&logoColor=white)

## Daftar Isi

- [Fitur](#fitur)
- [Teknologi](#teknologi)
- [Prasyarat](#prasyarat)
- [Instalasi](#instalasi)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Pengujian](#pengujian)
- [Struktur Proyek](#struktur-proyek)
- [Kontribusi](#kontribusi)
- [Lisensi](#lisensi)

## Fitur

<!-- TODO: ganti dengan fitur yang benar-benar ada di aplikasi Anda. Contoh di bawah hanya kerangka. -->

- [ ] Autentikasi dan manajemen peran pengguna (admin, dosen/asisten, mahasiswa)
- [ ] Manajemen data laboratorium
- [ ] Manajemen inventaris alat dan bahan
- [ ] Penjadwalan dan peminjaman laboratorium
- [ ] Laporan dan rekapitulasi

## Teknologi

| Bagian | Teknologi |
| --- | --- |
| Backend | Laravel (PHP) |
| Frontend | Blade, Tailwind CSS |
| Build tool | Vite |
| Database | MySQL / MariaDB <!-- sesuaikan --> |
| Pengujian | PHPUnit |

## Prasyarat

Pastikan perangkat Anda sudah terpasang:

- PHP >= 8.2 dan [Composer](https://getcomposer.org/)
- Node.js >= 18 dan npm
- Database server (MySQL/MariaDB)
- Git

> Versi PHP yang tepat dapat dilihat pada `composer.json`.

## Instalasi

1. **Clone repositori**

   ```bash
   git clone https://github.com/welmnlg/lab_management_system.git
   cd lab_management_system
   ```

2. **Pasang dependensi PHP dan JavaScript**

   ```bash
   composer install
   npm install
   ```

3. **Salin berkas environment dan buat application key**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Atur koneksi database** pada berkas `.env`

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=lab_management_system
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   Lalu buat database dengan nama yang sama di server database Anda.

5. **Jalankan migrasi (dan seeder jika tersedia)**

   ```bash
   php artisan migrate --seed
   ```

6. **Hubungkan folder storage**

   ```bash
   php artisan storage:link
   ```

## Menjalankan Aplikasi

Buka dua terminal:

```bash
# Terminal 1 - server Laravel
php artisan serve

# Terminal 2 - Vite (kompilasi aset)
npm run dev
```

Aplikasi dapat diakses di <http://localhost:8000>.

Untuk build produksi:

```bash
npm run build
```

### Akun Default

<!-- TODO: isi jika seeder membuat akun bawaan, lalu ingatkan untuk mengganti kata sandi. -->

| Peran | Email | Kata Sandi |
| --- | --- | --- |
| Admin | admin@example.com | password |

## Pengujian

```bash
php artisan test
```

## Struktur Proyek

```
lab_management_system/
├── app/          # Logika aplikasi (model, controller, dll.)
├── bootstrap/    # Bootstrap framework
├── config/       # Konfigurasi aplikasi
├── database/     # Migrasi, seeder, dan factory
├── docs/         # Dokumentasi proyek
├── public/       # Entry point dan aset publik
├── resources/    # View (Blade), CSS, dan JavaScript
├── routes/       # Definisi rute
├── storage/      # Berkas log, cache, dan unggahan
└── tests/        # Pengujian otomatis
```

## Kontribusi

Kontribusi sangat disambut.

1. Fork repositori ini
2. Buat branch fitur: `git checkout -b fitur/nama-fitur`
3. Commit perubahan: `git commit -m "Menambahkan fitur ..."`
4. Push ke branch: `git push origin fitur/nama-fitur`
5. Buka Pull Request

## Lisensi

Proyek ini dirilis di bawah lisensi [MIT](https://opensource.org/licenses/MIT). <!-- TODO: tambahkan berkas LICENSE jika belum ada. -->

## Tim Pengembang

Proyek ini dikembangkan secara berkelompok sebagai tugas <!-- TODO: nama mata kuliah, dosen, kampus, dan tahun -->.

| No | Nama | Peran | GitHub |
| --- | --- | --- | --- |
| 1 | Nurul Aini | Project Manager & Front End | [@nurulaini7](https://github.com/nurulaini7) |
| 2 | Inda Azara | Front End | [@indaazara](https://github.com/indaazara) |
| 3 | Cut Nabilah P.  | Back End | [@cutnabilahp](https://github.com/cutnabilahp) |
| 4 | Said Mazaya | Back End | [@saidmazaya](https://github.com/saidmazaya) |
| 5 | Imanuel Manullang | Back End | [@welmnlg](https://github.com/welmnlg) |

Daftar lengkap kontributor dapat dilihat di halaman [Contributors](https://github.com/welmnlg/lab_management_system/graphs/contributors).