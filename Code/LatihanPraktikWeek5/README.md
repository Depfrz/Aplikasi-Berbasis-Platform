# Sistem Akademik Laravel

Aplikasi akademik sederhana berbasis Laravel 9.x dengan fitur pengelolaan Mahasiswa, Dosen, dan Mata Kuliah.

## Fitur Utama

- **Mahasiswa**: CRUD dengan pagination, validasi NIM unik, dan filter urutan NIM.
- **Dosen**: CRUD dengan Soft Deletes, otomatis generate username, dan lazy/eager loading.
- **Mata Kuliah**: CRUD dengan relasi many-to-many ke Dosen, DB Transaction, dan API Resource.
- **Security**: Laravel Sanctum untuk API, Policy/Gate untuk otorisasi, CSRF & XSS protection.
- **UI/UX**: Layout Blade konsisten dengan Bootstrap 5 via Vite.
- **Testing**: Unit & Feature test dengan coverage yang baik.

## Persyaratan Sistem

- PHP >= 8.1
- Composer
- Node.js & NPM
- SQLite (atau MySQL 8.0)

## Instalasi

1. Clone repository:
   ```bash
   git clone <repository-url>
   cd LatihanPraktikWeek5
   ```

2. Install dependensi PHP:
   ```bash
   composer install
   ```

3. Install dependensi JS:
   ```bash
   npm install
   ```

4. Konfigurasi environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Catatan: Ubah `DB_CONNECTION` ke `sqlite` atau sesuaikan dengan MySQL Anda.*

5. Migrasi dan Seeding:
   ```bash
   touch database/database.sqlite # Jika menggunakan SQLite
   php artisan migrate:fresh --seed
   ```

6. Build assets:
   ```bash
   npm run build
   ```

7. Jalankan aplikasi:
   ```bash
   php artisan serve
   ```

## Akun Default

- **Email**: admin@akademik.test
- **Password**: password

## API Endpoints (v1)

Semua endpoint API memerlukan header `Authorization: Bearer <token>`.

- `GET /api/v1/matakuliah`: Daftar semua mata kuliah.
- `POST /api/v1/matakuliah`: Tambah mata kuliah baru.
- `GET /api/v1/matakuliah/{id}`: Detail mata kuliah.
- `PUT /api/v1/matakuliah/{id}`: Update mata kuliah.
- `DELETE /api/v1/matakuliah/{id}`: Hapus mata kuliah.

## Testing

Jalankan semua test:
```bash
php artisan test
```

## Linting

Aplikasi ini mengikuti standar PSR-12 menggunakan Laravel Pint:
```bash
./vendor/bin/pint
```
