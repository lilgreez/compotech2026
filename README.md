# 🏭 Compotech - Diecast Application System (DAS)

**Diecast Application System (DAS)** adalah sistem informasi berbasis web dan mobile untuk mengelola, merekam, dan memonitor data produksi serta *maintenance* komponen *Dieset* dan *Spare Parts* mesin Diecast. 

Proyek ini dirancang dengan arsitektur **Clean Code, Performa Tinggi (Anti N+1 Query), dan Keamanan Standar DevSecOps**.

---

## 🚀 Teknologi yang Digunakan (Tech Stack)

*   **Framework:** Laravel 12.x (PHP 8.2+)
*   **Frontend:** TailwindCSS, Alpine.js, Blade Components (Tanpa jQuery)
*   **Database:** MySQL (Direkomendasikan menggunakan DBngin + DBeaver untuk Local Development)
*   **Excel Export:** Maatwebsite / Laravel Excel
*   **Authentication:** Laravel Breeze
*   **Security & Tracking:** Custom Audit Logs (Observer Pattern) & Multi-Role Middleware

---

## 🎯 Modul & Fitur yang Sudah Selesai (Phase 1)

### 1. Keamanan & RBAC (Role-Based Access Control)
- Multi-Role Middleware dinamis (`Admin`, `Supervisor`, `Maintenance`, `Operator`).
- **Super Admin Bypass:** Role `Admin` memiliki akses absolut ke seluruh sistem tanpa hambatan *Middleware*.
- **Audit Logs:** Perekaman otomatis (Siapa, Kapan, Modul Apa, dan Data Lama vs Data Baru) untuk operasi Create/Update/Delete menggunakan Eloquent Observer.

### 2. Dieset Status (Monitoring Stok)
- Menampilkan status setiap *Dieset* beserta relasi *Parts*-nya.
- **Auto-Indicator:** Baris tabel otomatis berwarna **Pink/Merah** jika ada *part* di dalam *dieset* tersebut yang stoknya menipis (<= 2), dan **Hijau** jika stok aman.
- View Detail memunculkan modal daftar part berdasarkan **kategori**, *actual shoot*, *max shoot*, dan gambar part.
- Fitur *Export to Excel* pixel-perfect sesuai format perusahaan.

### 3. Inspection Monitor (Histori Perawatan)
- Menampilkan riwayat inspeksi dari mekanik (Terintegrasi ke *Mobile App* di masa depan).
- Filter data berdasarkan rentang waktu (*Date Range: Tanggal Awal & Tanggal Akhir*).
- Menampilkan detail kerusakan, tindakan perbaikan, pergantian parts, dan bukti foto (*Evidence Photo*).
- Fitur *Export to Excel* untuk laporan inspeksi.

### 4. Parts Stock & Email Notification
- Resume stok *spare parts* dengan UI Tab Navigasi: **All Stock, Low Stock, Safe Stock**.
- Fitur **Mail To SPV**: Mengumpulkan data parts dengan stok rendah (*Low Stock*), men-*generate* lampiran Excel otomatis, dan mengirimkannya via Email ke Supervisor yang terdaftar di sistem.

---

## 🚧 Modul Selanjutnya (Next To Do)
- [ ] **Master Data:** CRUD lengkap untuk Master Dieset, Master Parts (Integrasi data "Wings"), dan Master Inspection.
- [ ] **Administrator Settings:** Manajemen Operator (Mobile App Login) dan Email Report (Penerima Notifikasi).
- [ ] **API Endpoint (Mobile App):** RESTful API untuk fitur inspeksi, *update actual shoot*, dan *upload image evidence* dari lantai produksi.

---

## 💻 Cara Instalasi untuk Developer (Local Setup)

Ikuti langkah-langkah berikut untuk menjalankan project ini di komputer Anda:

1. **Clone Repository:**
   ```bash
   git clone <url-repository-github-ini>
   cd compotech

1. Install Dependencies (PHP & Node.js):

composer install
npm install

2. Konfigurasi Environment:

Copy file .env.example menjadi .env.

Buka .env dan sesuaikan koneksi database Anda:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=compotech_2026
DB_USERNAME=root
DB_PASSWORD=

# Settingan email lokal (Masuk ke storage/logs/laravel.log)
MAIL_MAILER=log

Generate App Key:

php artisan key:generate

3. Migrasi Database & Seeding (PENTING):
Jalankan perintah ini untuk membuat struktur tabel dan mengisi Role dasar sistem:

php artisan migrate --seed

Symlink Storage (Untuk Gambar/File Upload):
Agar gambar parts dan inspeksi bisa dirender di UI:

php artisan storage:link

5. Jalankan Aplikasi:
Buka 2 tab terminal terpisah dan jalankan:

# Terminal 1: Build asset frontend
npm run dev

# Terminal 2: Jalankan server PHP
php artisan serve

Akses Sistem:
Buka http://127.0.0.1:8000 di browser Anda.