# Dokumentasi Proyek: Helpdesk WiFi Gratis RT

## 1. Gambaran Umum

**Helpdesk WiFi Gratis RT** adalah aplikasi web berbasis Laravel 13 untuk sistem pelaporan dan penanganan gangguan WiFi di lingkungan RT/RW. Aplikasi ini memungkinkan warga melaporkan masalah WiFi, dan petugas (Admin/Teknisi) mengelola serta menyelesaikan laporan tersebut melalui dashboard.

- **Framework:** Laravel 13 (PHP 8.3+)
- **Frontend:** Blade Template + Tailwind CSS 4 + Alpine.js + Lucide Icons
- **Build Tool:** Vite 8
- **PWA Support:** Service Worker + Manifest JSON
- **Database:** MySQL
- **Auth Guard:** Model `Petugas` (bukan `User` bawaan Laravel)

---

## 2. Struktur Direktori

```
helpdesk-wifi-gratis-rt/
├── app/
│   ├── Http/Controllers/       # Controller (logika bisnis)
│   ├── Models/                 # Eloquent Model
│   └── Providers/              # Service Provider
├── config/                     # Konfigurasi aplikasi
├── database/
│   ├── migrations/             # Skema database
│   └── seeders/                # Data awal (seed)
├── public/
│   ├── sw.js                   # Service Worker (PWA)
│   └── manifest.json           # PWA Manifest
├── resources/
│   ├── css/app.css             # Tailwind CSS entry
│   ├── js/app.js               # JS entry (minimal)
│   └── views/                  # Blade templates
│       ├── auth/               # Halaman login
│       ├── dashboard/          # Halaman dashboard
│       │   ├── tiket/          # Manajemen tiket
│       │   └── petugas/        # Manajemen petugas
│       ├── layouts/            # Layout utama
│       └── public/             # Halaman publik (warga)
├── routes/
│   ├── web.php                 # Route utama
│   └── console.php             # Artisan command
├── vite.config.js              # Konfigurasi Vite
└── composer.json               # Dependency PHP
```

---

## 3. Skema Database

### 3.1 Tabel `petugas`

| Kolom       | Tipe              | Keterangan                        |
|-------------|-------------------|-----------------------------------|
| id_petugas  | BIGINT (PK)       | Primary key auto-increment        |
| nama        | VARCHAR(100)      | Nama lengkap petugas              |
| role        | ENUM('Admin','Teknisi') | Peran/hak akses             |
| username    | VARCHAR(50)       | Username login (unique)           |
| password    | VARCHAR(255)      | Password (hashed bcrypt)          |
| created_at  | TIMESTAMP         |                                   |
| updated_at  | TIMESTAMP         |                                   |

**File Migration:** `database/migrations/2026_04_21_074309_create_petugas_table.php`

### 3.2 Tabel `tiket`

| Kolom           | Tipe                          | Keterangan                            |
|-----------------|-------------------------------|---------------------------------------|
| id_tiket        | BIGINT (PK)                   | Primary key auto-increment            |
| nama_pelapor    | VARCHAR(100)                  | Nama warga pelapor                    |
| no_whatsapp     | VARCHAR(20)                   | Nomor WhatsApp pelapor                |
| rt              | VARCHAR(10)                   | Nomor RT                              |
| rw              | VARCHAR(10), nullable         | Nomor RW                              |
| kelurahan       | VARCHAR(50), nullable         | Nama kelurahan                        |
| kecamatan       | VARCHAR(50), nullable         | Nama kecamatan                        |
| kategori        | VARCHAR(50), nullable         | Kategori masalah                      |
| deskripsi       | TEXT, nullable                | Deskripsi keluhan                     |
| status          | ENUM('Open','Proses','Selesai') | Status tiket, default 'Open'        |
| tgl_lapor       | TIMESTAMP                     | Tanggal lapor, default current        |
| id_petugas      | BIGINT (FK), nullable         | Foreign key ke petugas (set null)     |
| is_read         | BOOLEAN, default false        | Status notifikasi sudah dibaca        |
| catatan_teknisi | TEXT, nullable                | Catatan hasil perbaikan               |
| foto_keluhan    | VARCHAR, nullable             | Path foto bukti keluhan               |
| latitude        | DECIMAL(10,8), nullable       | Koordinat GPS lintang                 |
| longitude       | DECIMAL(11,8), nullable       | Koordinat GPS bujur                   |
| created_at      | TIMESTAMP                     |                                       |
| updated_at      | TIMESTAMP                     |                                       |

**File Migration:**
- `database/migrations/2026_04_21_074310_create_tiket_table.php` (tabel utama)
- `database/migrations/2026_04_21_083013_add_is_read_to_tiket_table.php` (tambah kolom is_read)
- `database/migrations/2026_04_21_092248_add_advanced_fields_to_tiket_table.php` (tambah catatan_teknisi, foto_keluhan, latitude, longitude)

**Relasi:** `tiket.id_petugas` -> `petugas.id_petugas` (onDelete: SET NULL)

---

## 4. Model

### 4.1 `App\Models\Petugas` (`app/Models/Petugas.php`)

Model autentikasi utama. Meng-extend `Authenticatable` sehingga bisa digunakan untuk login.

```php
class Petugas extends Authenticatable
{
    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas';
    protected $fillable = ['nama', 'role', 'username', 'password'];
    protected $hidden = ['password'];

    public function tikets() // hasMany Tiket
}
```

**Catatan Penting:** Model `Petugas` digunakan sebagai auth provider (bukan `User`). Konfigurasi ada di `config/auth.php:67`:
```php
'model' => env('AUTH_MODEL', \App\Models\Petugas::class),
```

### 4.2 `App\Models\Tiket` (`app/Models/Tiket.php`)

```php
class Tiket extends Model
{
    protected $table = 'tiket';
    protected $primaryKey = 'id_tiket';
    protected $fillable = [...]; // semua kolom kecuali id dan timestamps
    protected $casts = [
        'tgl_lapor' => 'datetime',
        'is_read' => 'boolean',
    ];

    public function petugas()           // belongsTo Petugas
    public function getFormattedWhatsappAttribute() // accessor: format no WA ke 62xxx
    public function getTicketNoAttribute()          // accessor: format #TKT-00001
}
```

**Accessor:**
- `formatted_whatsapp` — Mengubah format nomor WA (0xxx -> 62xxx, 8xxx -> 62xxx) untuk link WhatsApp API.
- `ticket_no` — Menghasilkan nomor tiket terformat, contoh: `#TKT-00001`.

### 4.3 `App\Models\User` (`app/Models/User.php`)

Model bawaan Laravel, **tidak digunakan** dalam aplikasi ini. Hanya tersedia sebagai scaffolding default.

---

## 5. Controller

### 5.1 `PublicTiketController` (`app/Http/Controllers/PublicTiketController.php`)

Menangani halaman publik untuk warga.

| Method   | Route              | Fungsi                                                                 |
|----------|--------------------|------------------------------------------------------------------------|
| `landing()` | `GET /`         | Menampilkan halaman landing page (`public.landing`)                   |
| `index()`   | `GET /tiket/create` | Menampilkan form pembuatan tiket (`public.tiket_create`)            |
| `store()`   | `POST /tiket/store` | Menyimpan tiket baru ke database                                     |

**Detail `store()`:**
1. Validasi input: nama_pelapor, no_whatsapp, rt, kelurahan, kecamatan, latitude, longitude (wajib), foto_keluhan (opsional, maks 5MB, jpeg/png/jpg).
2. Upload foto ke `storage/app/public/keluhan/`.
3. Simpan data ke tabel `tiket` dengan status `Open`.
4. Redirect kembali dengan pesan sukses berisi nomor tiket.

### 5.2 `AuthController` (`app/Http/Controllers/AuthController.php`)

Menangani autentikasi petugas.

| Method         | Route           | Fungsi                                             |
|----------------|-----------------|----------------------------------------------------|
| `login()`      | `GET /login`    | Menampilkan form login (`auth.login`)              |
| `authenticate()` | `POST /login` | Memvalidasi kredensial (username + password)       |
| `logout()`     | `POST /logout`  | Logout dan invalidate session, redirect ke `/`     |

### 5.3 `DashboardController` (`app/Http/Controllers/DashboardController.php`)

Menampilkan halaman dashboard utama setelah login.

| Method    | Route            | Fungsi                                       |
|-----------|------------------|-----------------------------------------------|
| `index()` | `GET /dashboard` | Menampilkan statistik ringkasan tiket        |

**Data yang dikirim ke view:**
- `stats` — Array berisi jumlah tiket: total, open, proses, selesai.
  - Untuk Teknisi: hanya menghitung tiket yang ditugaskan ke mereka (kecuali `open` tetap global agar mereka bisa melihat pekerjaan yang tersedia).
- `recent_tickets` — 5 tiket terbaru (dengan relasi petugas).
- `all_tickets` — Semua tiket yang memiliki koordinat GPS (untuk peta sebaran).

### 5.4 `DashboardTiketController` (`app/Http/Controllers/DashboardTiketController.php`)

Mengelola CRUD tiket di dashboard.

| Method          | Route                                | Fungsi                                          | Akses         |
|-----------------|--------------------------------------|-------------------------------------------------|---------------|
| `index()`       | `GET /dashboard/tiket`               | Daftar tiket dengan filter status & search      | Semua         |
| `assign()`      | `POST /dashboard/tiket/{id}/assign`  | Menugaskan teknisi ke tiket, status -> Proses   | Admin         |
| `updateStatus()`| `POST /dashboard/tiket/{id}/status`  | Mengubah status tiket (Open/Proses/Selesai)     | Yang ditugaskan |
| `claim()`       | `POST /dashboard/tiket/{id}/claim`   | Teknisi mengambil tiket Open, status -> Proses  | Teknisi       |
| `markRead()`    | `POST /dashboard/tiket/{id}/mark-read` | Menandai notifikasi tiket sebagai dibaca      | Semua         |
| `destroy()`     | `DELETE /dashboard/tiket/{id}`       | Menghapus tiket                                 | Admin saja    |

**Detail `index()`:**
- Filter berdasarkan `status` (query parameter).
- Pencarian berdasarkan `nama_pelapor`, `kategori`, `deskripsi`, `no_whatsapp`.
- Teknisi hanya melihat tiket yang ditugaskan ke mereka ATAU tiket berstatus Open.
- Pagination: 10 item per halaman.
- Mengirim daftar teknisi untuk fitur assign (Admin).

### 5.5 `PetugasController` (`app/Http/Controllers/PetugasController.php`)

Mengelola data petugas (hanya Admin).

| Method            | Route                                   | Fungsi                                    |
|-------------------|-----------------------------------------|-------------------------------------------|
| `index()`         | `GET /dashboard/petugas`                | Daftar semua petugas                      |
| `store()`         | `POST /dashboard/petugas`               | Tambah petugas baru                       |
| `resetPassword()` | `POST /dashboard/petugas/{id}/reset`    | Reset password ke `password123`           |
| `destroy()`       | `DELETE /dashboard/petugas/{id}`        | Hapus petugas (tidak bisa hapus diri sendiri) |

**Validasi `store()`:** nama (wajib, maks 100), username (wajib, unique), password (wajib, default rules), role (wajib, Admin/Teknisi).

### 5.6 `SettingsController` (`app/Http/Controllers/SettingsController.php`)

Pengaturan profil dan password petugas yang sedang login.

| Method             | Route                                   | Fungsi                               |
|--------------------|-----------------------------------------|--------------------------------------|
| `index()`          | `GET /dashboard/settings`               | Tampilkan halaman pengaturan         |
| `updateProfile()`  | `POST /dashboard/settings/profile`      | Update nama dan username             |
| `updatePassword()` | `POST /dashboard/settings/password`     | Ganti password (verifikasi password lama) |

---

## 6. Routing

**File:** `routes/web.php`

### 6.1 Route Publik (Tanpa Autentikasi)

| Method | URL              | Nama Route      | Controller                  |
|--------|------------------|-----------------|-----------------------------|
| GET    | `/`              | `landing`       | `PublicTiketController@landing` |
| GET    | `/tiket/create`  | `tiket.create`  | `PublicTiketController@index`   |
| POST   | `/tiket/store`   | `tiket.store`   | `PublicTiketController@store`   |

### 6.2 Route Autentikasi

| Method | URL        | Nama Route     | Controller                  |
|--------|------------|----------------|-----------------------------|
| GET    | `/login`   | `login`        | `AuthController@login`      |
| POST   | `/login`   | `authenticate` | `AuthController@authenticate` |
| POST   | `/logout`  | `logout`       | `AuthController@logout`     |

### 6.3 Route Dashboard (Autentikasi Wajib)

Semua route dalam group middleware `auth`.

| Method  | URL                                      | Nama Route                       | Controller                          |
|---------|------------------------------------------|----------------------------------|-------------------------------------|
| GET     | `/dashboard`                             | `dashboard`                      | `DashboardController@index`         |
| GET     | `/dashboard/tiket`                       | `dashboard.tiket.index`          | `DashboardTiketController@index`    |
| POST    | `/dashboard/tiket/{id}/assign`           | `dashboard.tiket.assign`         | `DashboardTiketController@assign`   |
| POST    | `/dashboard/tiket/{id}/status`           | `dashboard.tiket.update-status`  | `DashboardTiketController@updateStatus` |
| POST    | `/dashboard/tiket/{id}/mark-read`        | `dashboard.tiket.mark-read`      | `DashboardTiketController@markRead` |
| POST    | `/dashboard/tiket/{id}/claim`            | `dashboard.tiket.claim`          | `DashboardTiketController@claim`    |
| DELETE  | `/dashboard/tiket/{id}`                  | `dashboard.tiket.destroy`        | `DashboardTiketController@destroy`  |
| GET     | `/dashboard/settings`                    | `dashboard.settings`             | `SettingsController@index`          |
| POST    | `/dashboard/settings/profile`            | `dashboard.settings.profile`     | `SettingsController@updateProfile`  |
| POST    | `/dashboard/settings/password`           | `dashboard.settings.password`    | `SettingsController@updatePassword` |
| GET     | `/dashboard/petugas`                     | `dashboard.petugas.index`        | `PetugasController@index`           |
| POST    | `/dashboard/petugas`                     | `dashboard.petugas.store`        | `PetugasController@store`           |
| POST    | `/dashboard/petugas/{id}/reset`          | `dashboard.petugas.reset`        | `PetugasController@resetPassword`   |
| DELETE  | `/dashboard/petugas/{id}`                | `dashboard.petugas.destroy`      | `PetugasController@destroy`         |

---

## 7. Views (Blade Template)

### 7.1 Layout

| File | Fungsi |
|------|--------|
| `layouts/app.blade.php` | Layout utama untuk halaman publik (navbar, footer, PWA meta tags, Vite, Lucide Icons, Alpine.js, Chart.js, loading bar, service worker). Font: Outfit (Google Fonts). |
| `layouts/dashboard.blade.php` | Layout dashboard dengan sidebar navigasi (collapsible via Alpine.js & localStorage), header sticky dengan breadcrumb & notifikasi, dropdown user. Menggunakan SweetAlert2 untuk konfirmasi aksi. Notifikasi badge menampilkan jumlah tiket Open yang belum dibaca. |

### 7.2 Halaman Publik

| File | Fungsi |
|------|--------|
| `public/landing.blade.php` | Landing page hero section dengan animasi. Terdapat tombol "Buat Tiket Sekarang" dan link tersembunyi ke panel login petugas (muncul saat hover). Terdapat section fitur: Respon Cepat, Pelacakan Lokasi, Notifikasi WA. |
| `public/tiket_create.blade.php` | Form pembuatan tiket warga. Terdiri dari: (1) Sidebar FAQ/Bantuan Cepat (accordion: WiFi Lambat, Lampu Router Merah, Ganti Password), (2) Form utama: identitas (nama, WA), lokasi & alamat (RT/RW, kelurahan, kecamatan), peta Leaflet dengan GPS wajib (pin lokasi via `navigator.geolocation`), detail masalah (kategori dropdown, deskripsi, upload foto). Validasi: lokasi GPS wajib sebelum submit. Peta menggunakan Google Maps tiles via Leaflet. |

### 7.3 Halaman Autentikasi

| File | Fungsi |
|------|--------|
| `auth/login.blade.php` | Form login petugas dengan input username dan password, checkbox "Ingat saya", link kembali ke form laporan. |

### 7.4 Halaman Dashboard

| File | Fungsi |
|------|--------|
| `dashboard/index.blade.php` | Dashboard overview. Menampilkan: (1) Grid statistik (Total, Open, Proses, Selesai) dengan warna berbeda, (2) Bar chart status tiket (Chart.js), (3) Daftar 5 laporan terbaru dengan foto thumbnail dan lightbox, (4) Sidebar aksi cepat, (5) Peta sebaran gangguan WiFi (Leaflet + Google Maps tiles, circle marker berwarna sesuai status). |
| `dashboard/tiket/index.blade.php` | Manajemen tiket. Fitur: filter status (Semua/Open/Proses/Selesai), pencarian, tabel tiket dengan detail warga, lokasi (link Google Maps), foto (dengan lightbox), status badge. Aksi per tiket: hubungi WA, assign teknisi (Admin), ambil & kerjakan (Teknisi), update status + catatan perbaikan, hapus (Admin). Terdapat 2 tombol WA: chat manual dan kirim update status otomatis. Pagination kustom. |
| `dashboard/settings.blade.php` | Pengaturan akun. 2 form: (1) Update profil (nama, username), (2) Ganti password (password lama, password baru + konfirmasi). |
| `dashboard/petugas/index.blade.php` | Manajemen petugas (Admin only). Tabel daftar petugas dengan badge role. Aksi: reset password, hapus. Modal tambah petugas baru (Alpine.js dispatch event). |

---

## 8. Service Provider

### `AppServiceProvider` (`app/Providers/AppServiceProvider.php`)

Pada method `boot()`, mendaftarkan **View Composer** untuk layout dashboard:

```php
View::composer('layouts.dashboard', function ($view) {
    $view->with('unassigned_tickets', Tiket::where('status', 'Open')->where('is_read', false)->latest()->take(5)->get());
    $view->with('unassigned_count', Tiket::where('status', 'Open')->where('is_read', false)->count());
});
```

Ini memastikan setiap kali layout dashboard di-render, variabel `$unassigned_tickets` dan `$unassigned_count` selalu tersedia untuk notifikasi di header.

---

## 9. Database Seeder

### `PetugasSeeder` (`database/seeders/PetugasSeeder.php`)

Membuat 2 akun default:

| Nama          | Username  | Password   | Role     |
|---------------|-----------|------------|----------|
| Administrator | admin     | password   | Admin    |
| Teknisi 1     | teknisi   | password   | Teknisi  |

### `DatabaseSeeder` (`database/seeders/DatabaseSeeder.php`)

Memanggil `PetugasSeeder` saja.

---

## 10. Konfigurasi

### 10.1 Autentikasi (`config/auth.php`)

Provider auth menggunakan model `Petugas` (bukan `User`):
```php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => env('AUTH_MODEL', \App\Models\Petugas::class),
    ],
],
```

### 10.2 Database (`.env.example`)

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=helpdesk_wifi_gratis_rt
DB_USERNAME=root
DB_PASSWORD=
```

### 10.3 Session & Queue

- Session driver: `database`
- Queue connection: `database`
- Cache store: `database`

---

## 11. Frontend & Assets

### 11.1 CSS (`resources/css/app.css`)

- Menggunakan Tailwind CSS 4 (`@import 'tailwindcss'`).
- Font default: **Outfit** (Google Fonts).
- Custom animation: `fade-in`, `slide-up` untuk transisi halaman dan kartu.
- Global transition pada semua elemen (0.2s).
- Custom scrollbar styling untuk sidebar.

### 11.2 JavaScript (`resources/js/app.js`)

File minimal (hanya komentar `//`). Semua JS ditulis inline di Blade templates menggunakan Alpine.js.

### 11.3 Library CDN yang Digunakan

| Library | Versi | Kegunaan |
|---------|-------|----------|
| Tailwind CSS | 4.x | Utility-first CSS framework |
| Alpine.js | 3.x | Reactive UI (dropdown, modal, sidebar, form state) |
| Lucide Icons | latest | Ikon SVG |
| Chart.js | 4.4.2 | Bar chart statistik dashboard |
| Leaflet | 1.9.4 | Peta interaktif (Google Maps tiles) |
| SweetAlert2 | 11 | Dialog konfirmasi (hapus, logout, reset password) |
| Google Fonts (Outfit) | - | Tipografi |

### 11.4 Build Tool (`vite.config.js`)

```js
plugins: [
    laravel({ input: ['resources/css/app.css', 'resources/js/app.js'], refresh: true }),
    tailwindcss(),
],
server: { watch: { ignored: ['**/storage/framework/views/**'] } }
```

---

## 12. PWA (Progressive Web App)

### 12.1 Manifest (`public/manifest.json`)

- Nama: "Helpdesk WiFi RT"
- Display: standalone
- Theme color: #2563eb
- Icon: `/icon.svg`

### 12.2 Service Worker (`public/sw.js`)

- Cache name: `wifi-rt-v3`
- Strategy: **Cache-first** untuk GET request.
- **Tidak di-cache:** request ke `/storage/`, `/dashboard`, `/logout`, dan non-GET.
- Otomatis menghapus cache lama saat activate.

---

## 13. Alur Kerja Aplikasi

### 13.1 Alur Pelaporan Warga

```
Warga mengakses landing page (/)
  → Klik "Buat Tiket Sekarang"
  → Isi form: identitas, lokasi, ambil GPS, pilih kategori, deskripsi, foto
  → Submit tiket (status: Open)
  → Mendapat nomor tiket (#TKT-XXXXX)
```

### 13.2 Alur Penanganan Tiket

```
Tiket masuk (status: Open, is_read: false)
  → Muncul notifikasi di dashboard petugas
  → Admin: Assign tiket ke teknisi (status -> Proses)
     ATAU
  → Teknisi: Claim tiket sendiri (status -> Proses)
  → Teknisi mengerjakan, bisa kirim update WA ke warga
  → Teknisi selesai: klik "Selesaikan", isi catatan perbaikan (status -> Selesai)
  → Admin bisa hapus tiket jika diperlukan
```

### 13.3 Integrasi WhatsApp

- Format nomor otomatis dikonversi ke format internasional (62xxx).
- Link `wa.me` digenerate otomatis dengan pesan template.
- 2 jenis pesan: chat manual dan update status otomatis (berubah sesuai status tiket).

---

## 14. Hak Akses (Role-Based)

| Fitur                      | Admin | Teknisi |
|----------------------------|-------|---------|
| Melihat dashboard          | Ya    | Ya (hanya tiket sendiri) |
| Daftar semua tiket         | Ya    | Ya (tiket sendiri + Open) |
| Assign teknisi             | Ya    | Tidak |
| Claim tiket                | Tidak | Ya |
| Update status tiket        | Ya    | Ya (hanya tiket sendiri) |
| Hapus tiket                | Ya    | Tidak |
| Manajemen petugas          | Ya    | Tidak |
| Pengaturan profil sendiri  | Ya    | Ya |
| Reset password petugas     | Ya    | Tidak |

---

## 15. Perintah Composer/Artisan

| Perintah | Fungsi |
|----------|--------|
| `composer run setup` | Install dependency, generate key, migrate, build assets |
| `composer run dev` | Jalankan server, queue listener, dan Vite secara concurrent |
| `composer run test` | Clear config + jalankan test suite (Pest) |
| `php artisan serve` | Jalankan development server |
| `php artisan migrate` | Jalankan database migration |
| `php artisan db:seed` | Jalankan database seeder (buat akun default) |

---

## 16. Koneksi Peta & GPS

Aplikasi menggunakan **Leaflet.js** dengan **Google Maps tiles** (bukan OpenStreetMap) untuk 2 fitur:

1. **Form tiket warga** — Peta untuk mengambil lokasi GPS. Pin hanya bisa diambil via tombol "Ambil Lokasi GPS" (manual click pada peta dinonaktifkan untuk mencegah fake GPS).
2. **Dashboard** — Peta sebaran gangguan WiFi dengan circle marker berwarna: merah (Open), kuning (Proses), hijau (Selesai). Default center: Balikpapan (-1.2654, 116.8312).
