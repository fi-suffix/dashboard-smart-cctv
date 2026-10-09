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
- `/dashboard/camera` — CRUD kamera (otomatis memulai/menghentikan stream di Python); halaman create/edit punya tombol **Test Connection** untuk memverifikasi URL & kredensial RTSP (probe ke Python `/test-rtsp`, timeout 25 detik)
- `/dashboard/employee` — CRUD karyawan + foto + embedding
- `/dashboard/detection_history` — riwayat deteksi + filter + statistik
- `/dashboard/admin` — kelola akun admin
- `/dashboard/setting` — settings & cleanup storage

## go2rtc (restream video via WebRTC/MSE)

Video live ditayangkan lewat **go2rtc** (`D:\PKL-project\go2rtc`) agar browser bisa memutar RTSP (H.264/H.265) tanpa decode CPU — jalur video terpisah dari service Python/AI.

Perubahan di DB kamera (migrasi `add_rtsp_sub_main_to_cameras_table`) menambah dua kolom:

- `rtsp_url_main` — URL substream utama (bila kosong, fallback ke `rtsp_url`)
- `rtsp_url_sub` — URL substream tambahan/grid (bila kosong, fallback ke `rtsp_url`)

Aksesor model: `Camera::effective_main_url` / `effective_sub_url`. Naming dua stream per kamera: `cam{ID}_main` dan `cam{ID}_sub`.

### Setup singkat (sekali ini)

```powershell
php artisan go2rtc:sync          # tulis D:\PKL-project\go2rtc\go2rtc.yaml dari tabel cameras
D:\PKL-project\go2rtc\start-go2rtc.bat   # jalankan go2rtc (WebUI http://<LAN-IP>:1984)
```

- Jalankan ulang `go2rtc:sync` setelah ubah tabel `cameras` atau `.env` variabel `GO2RTC_*`.
- Variabel `.env`: `GO2RTC_HOST` (auto-detect bila kosong), `GO2RTC_API_PORT=1984`, `GO2RTC_RTSP_PORT=8554`, `GO2RTC_WEBRTC_PORT=8555`, `GO2RTC_CONFIG_PATH=../go2rtc/go2rtc.yaml`.
- RTSP restream lokal `rtsp://127.0.0.1:8554/cam{ID}_sub` bisa dipakai pekerja AI bila ingin sumber stream diambil dari go2rtc.
- Firewall: inbound TCP/UDP untuk `go2rtc.exe` sudah dibuat otomatis.

### Catatan keamanan

- `go2rtc.yaml` memuat URL RTSP **beserta kredensial kamera** → file ini **TIDAK boleh di-commit** (sudah di `.gitignore`).
- WebUI/API go2rtc di `:1984` terbuka di LAN. Set `GO2RTC_API_USERNAME`/`GO2RTC_API_PASSWORD` di `.env` untuk Basic Auth, atau proxy `/api` lewat Laravel (TODO). Jalankan `go2rtc:sync` ulang setelah menambah kredensial.
- `rtsp.listen` sengaja dibatasi `127.0.0.1:8554` agar restream internal tidak terekspos ke jaringan.

## Recognition Events (Phase 2)

Peristiwa pengenalan wajah (known/unknown) disimpan di tabel `recognition_events`
(uuid idempoten, camera_id, employee_id nullable, type, similarity, track_id,
snapshot_path, bbox, occurred_at UTC). Objek terkait: `RecognitionEvent`.

### Ingest dari service Python

Contoh `curl` (multipart, JSON-fields + file JPEG opsional):

```bash
curl -X POST http://localhost:8000/api/internal/recognition-events \
  -H "X-Internal-Token: dev-internal-token-2026" \
  -F "event_uuid=3f7d0c4e-a1b2-4c3d-9e8f-000000000001" \
  -F "camera_id=17" \
  -F "type=known" \
  -F "employee_id=9" \
  -F "similarity=0.618" \
  -F "track_id=cam17-track-1" \
  -F "bbox[]=10" -F "bbox[]=20" -F "bbox[]=60" -F "bbox[]=80" \
  -F "occurred_at=2026-09-21T05:00:00Z" \
  -F "snapshot=@snapshot.jpg"
```

- Respon `201` (baru) atau `200` + `"duplicate":true` (event_uuid yang sama tidak ganda).
- Token di header `X-Internal-Token` = `.env` `AI_INTERNAL_TOKEN` (constant-time compare,
  middleware `internal.token`). Rate limit internal: 120/menit (`throttle:internal-events`).
- Snapshot disimpan di `storage/app/public/events/{Y}/{m}/{d}/` (folder ini tidak ikut git)
  dan hanya bisa diakses lewat route terautentikasi `GET /dashboard/recognition_events/{event}/snapshot`.

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