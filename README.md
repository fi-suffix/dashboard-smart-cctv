# FaceGuard AI — Dashboard (Laravel)

Dashboard web untuk mengelola sistem **face recognition CCTV**: CRUD kamera, CRUD karyawan (employee + foto + embedding), rekam deteksi (detection logs), live monitoring, akun admin, dan settings.

Berpasangan dengan service Python di folder **`../Facial-recognition-cctv`** (lihat README-nya).

## Dependensi

| Komponen | Versi / Spesifikasi |
| --- | --- |
| PHP | ^8.3 |
| Composer | 2.x |
| Node.js + npm | 20+ (untuk Vite & Tailwind CSS v4) |
| MySQL / MariaDB | database `dashboard-cctv` |
| Service Python | FastAPI di `http://localhost:8001` (opsional tapi dibutuhkan untuk live video & embedding) |

Library PHP utama (`composer.json`): `laravel/framework ^13.17`, `laravel/tinker ^3.0`.
Dev: `laravel/pint`, `pestphp/pest`, dll.

## Persyaratan sistem

- git, Composer, PHP 8.3+, Node.js 20+
- MySQL berjalan (`DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_DATABASE=dashboard-cctv`, `DB_USERNAME=root`, `DB_PASSWORD=` kosong)

## Setup

```powershell
cd D:\PKL-project\dashboard-face-recognition

# 1. Install dependency
composer install

# 2. File konfigurasi
Copy-Item .env.example .env
#   -> sesuaikan DB_CONNECTION/DB_DATABASE/DB_USERNAME/DB_PASSWORD
#   -> pastikan PYTHON_SERVICE_URL dan FACE_RECOGNITION_API_KEY terisi

# 3. Generate app key
php artisan key:generate

# 4. Migrasi database
php artisan migrate --force

# 5. Seed data
php artisan db:seed --force                                # data contoh (employees, cameras, logs)
php artisan db:seed --class=AdminUserSeeder --force        # akun admin pertama

# 6. Frontend (Tailwind / Vite)
npm install
npm run build          # build produksi
# npm run dev          # atau mode development
```

## Akun login default

| Username | Password | Role |
| --- | --- | --- |
| `admin` | `admin123` | Super Admin |

**Segera ganti password setelah login pertama** (menu **Admin Accounts**).

## Cara menjalankan

Mode development gabungan (server + queue + vite):

```powershell
composer run dev
```

Atau manual:

```powershell
# Terminal 1 - web server
php artisan serve                     # http://localhost:8000

# Terminal 2 - vite (hanya saat development styling)
npm run dev                           # http://localhost:5173
```

Login halaman: <http://localhost:8000/login>

## Konfigurasi integrasi dengan service Python

Variabel di `.env`:

| Key | Nilai contoh | Fungsi |
| --- | --- | --- |
| `PYTHON_SERVICE_URL` | `http://localhost:8001` | Base URL service FastAPI |
| `FACE_RECOGNITION_API_KEY` | `your-secret-api-key-here` | API key dipakai service Python untuk memanggil API Laravel |

Service Python memanggil endpoint `/api/face-recognition/*` yang dilindungi middleware `api.key`
(cek header `Authorization: Bearer <FACE_RECOGNITION_API_KEY>`). CSRF dikecualikan untuk URL tersebut di `bootstrap/app.php`.

Alur:
1. Dashboard mengirim daftar kamera aktif & embeddings karyawan (via `FaceRecognitionController`).
2. Service Python membaca stream, mendeteksi + mengenali wajah.
3. Python POST `detection-logs` + menyimpan snapshot ke `Facial-recognition-cctv/snapshots/`.

## Snapshots

Folder `public/snapshots` adalah **junction** ke `../Facial-recognition-cctv/snapshots`.
Snapshot yang ditulis service Python otomatis bisa diakses web melalui `http://localhost:8000/snapshots/...`.

Folder ini **tidak ikut git** (tidak di-commit).

## Cleanup storage

Halaman **Settings → Storage & Cleanup**:

- Menampilkan pemakaian saat ini (jumlah log, jumlah file snapshot, total ukuran).
- `Run Cleanup Now` menghapus:
  - log deteksi yang lebih tua dari **retention** (default 30 hari) beserta file snapshot-nya;
  - file snapshot *yatim* (tidak lagi direferensikan log mana pun) yang lebih tua dari **snapshot retention** (default 7 hari).

Implementasi: `SettingsController@index` dan `@cleanup` (`POST /dashboard/setting/cleanup`).

## Halaman utama

- `/login` — login admin (email atau username)
- `/dashboard` — ringkasan statistik
- `/dashboard/live_monitoring/{camera}` — video live + deteksi real-time
- `/dashboard/camera` — CRUD kamera (otomatis memulai/menghentikan stream di Python)
- `/dashboard/employee` — CRUD karyawan + foto + embedding
- `/dashboard/detection_history` — riwayat deteksi + filter + statistik
- `/dashboard/admin` — kelola akun admin
- `/dashboard/setting` — settings & cleanup storage

## Struktur penting

```
routes/web.php                  # semua route dashboard + auth
app/Http/Controllers/Auth/      # login & logout
app/Http/Controllers/Dashboard/ # controller halaman dashboard
app/Http/Controllers/Api/       # API untuk service Python
app/Http/Middleware/ApiKeyAuth.php
resources/views/auth/           # halaman login
resources/views/dashboard/      # halaman dashboard
resources/views/layouts/        # layout dashboard + navbar
database/seeders/               # DatabaseSeeder + AdminUserSeeder
public/snapshots                # junction -> ../Facial-recognition-cctv/snapshots
```