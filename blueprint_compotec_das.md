# BLUEPRINT DOKUMEN
## Diecast Application System (DAS)
### PT Compotec

---

> **Versi Dokumen :** 1.0.0
> **Tanggal Dibuat :** 24 September 2026
> **Status :** Draft — Internal Use Only
> **Pemilik Dokumen :** Tim IT PT Compotec

---

## DAFTAR ISI

1. [Pendahuluan](#1-pendahuluan)
2. [Tujuan & Ruang Lingkup](#2-tujuan--ruang-lingkup)
3. [Arsitektur Sistem](#3-arsitektur-sistem)
4. [Teknologi Stack](#4-teknologi-stack)
5. [Struktur Database](#5-struktur-database)
6. [Modul & Fitur Sistem](#6-modul--fitur-sistem)
7. [Role & Hak Akses (RBAC)](#7-role--hak-akses-rbac)
8. [Keamanan Sistem](#8-keamanan-sistem)
9. [Infrastruktur & Deployment](#9-infrastruktur--deployment)
10. [Alur Kerja Pengguna (User Flow)](#10-alur-kerja-pengguna-user-flow)
11. [Roadmap Pengembangan](#11-roadmap-pengembangan)
12. [Standar Pengkodean](#12-standar-pengkodean)
13. [Glosarium](#13-glosarium)

---

## 1. Pendahuluan

### 1.1 Latar Belakang

PT Compotec adalah perusahaan manufaktur yang bergerak di bidang produksi komponen *Diecast*. Dalam proses produksinya, PT Compotec mengoperasikan sejumlah mesin *Diecast* yang memerlukan pemantauan komponen (*Dieset* dan *Spare Parts*) secara berkelanjutan untuk memastikan keberlangsungan produksi yang optimal.

Selama ini, pencatatan data produksi, pemantauan stok *spare parts*, dan riwayat inspeksi mesin masih dilakukan secara manual atau melalui spreadsheet yang terpencar, sehingga menimbulkan:

- **Keterlambatan informasi** — Status ketersediaan *spare parts* tidak diketahui secara *real-time*.
- **Risiko *downtime* produksi** — Keterlambatan penggantian part yang aus menyebabkan mesin berhenti beroperasi.
- **Kesulitan audit trail** — Tidak ada catatan terstruktur tentang siapa yang melakukan inspeksi, kapan, dan apa yang ditemukan.
- **Inefisiensi pelaporan** — Laporan ke Supervisor harus disusun manual dan memakan waktu.

### 1.2 Solusi yang Dibangun

Untuk menjawab tantangan tersebut, PT Compotec membangun **Diecast Application System (DAS)** — sebuah sistem informasi berbasis web yang terpusat, *real-time*, dan terintegrasi, dengan arsitektur yang siap dikembangkan ke aplikasi *mobile* di masa depan.

---

## 2. Tujuan & Ruang Lingkup

### 2.1 Tujuan Umum

Membangun sistem informasi manajemen produksi berbasis web untuk PT Compotec yang mampu:

1. Memantau status dan stok komponen mesin *Diecast* secara *real-time*.
2. Merekam seluruh riwayat inspeksi dan perawatan mesin secara terstruktur.
3. Memberikan notifikasi otomatis kepada Supervisor ketika stok *spare parts* menipis.
4. Menyediakan jejak audit (*audit trail*) yang lengkap atas setiap perubahan data di sistem.
5. Menghasilkan laporan dalam format Excel sesuai standar perusahaan.

### 2.2 Ruang Lingkup Sistem

| No | Modul | Deskripsi | Status |
|----|-------|-----------|--------|
| 1 | Autentikasi & Manajemen User | Login, profil user, manajemen role | ✅ Selesai |
| 2 | Dashboard | Ringkasan statistik & indikator kritis | ✅ Selesai |
| 3 | Dieset Status (Monitoring Stok) | Pantau stok part per mesin Dieset | ✅ Selesai |
| 4 | Inspection Monitor | Riwayat inspeksi & filter waktu | ✅ Selesai |
| 5 | Parts Stock & Email Notifikasi | Stok part + kirim laporan via email | ✅ Selesai |
| 6 | Audit Logs | Jejak seluruh perubahan data | ✅ Selesai |
| 7 | Master Data (Dieset, Parts, Inspection) | CRUD data master | 🚧 In Progress |
| 8 | Administrator Settings (Operator & Email) | Kelola operator & email SPV | 🚧 In Progress |
| 9 | API Endpoint (Mobile App) | RESTful API untuk app lapangan | 📋 Planned |

### 2.3 Batasan Sistem

- Sistem ini **tidak** mencakup modul akuntansi atau integrasi dengan sistem ERP.
- Pada Phase 1, integrasi dengan *mobile app* bersifat satu arah (data dari *mobile* ke *web*); fitur dua arah direncanakan pada Phase 2.
- Data yang di-input melalui *mobile app* (Operator di lapangan) hanya mencakup modul Inspeksi.

---

## 3. Arsitektur Sistem

### 3.1 Gambaran Umum Arsitektur

Sistem DAS PT Compotec dibangun dengan arsitektur **Monolitik Modular** (Monolithic Modular Architecture) berbasis framework Laravel 12, dengan pemisahan tanggung jawab yang jelas antar lapisan.

```
┌─────────────────────────────────────────────────────────────────┐
│                        CLIENT LAYER                             │
│         Browser (Admin, SPV, Maintenance, Operator)             │
│              +  Mobile App (Operator — Phase 2)                 │
└───────────────────────────┬─────────────────────────────────────┘
                            │  HTTPS
┌───────────────────────────▼─────────────────────────────────────┐
│                     WEB SERVER LAYER                            │
│              Nginx (Reverse Proxy + SSL Termination)            │
│              Domain: compotec.arsyi-project.web.id              │
└───────────────────────────┬─────────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────────────┐
│                   APPLICATION LAYER                             │
│               Laravel 12.x (PHP 8.3-FPM)                       │
│  ┌──────────┐ ┌──────────────┐ ┌────────────┐ ┌─────────────┐  │
│  │ Routes   │ │  Controllers │ │  Services  │ │  Observers  │  │
│  │ (web.php)│ │  (12 buah)   │ │  (Mail,    │ │  (Audit Log)│  │
│  │ (auth.php│ │              │ │   Export)  │ │             │  │
│  └──────────┘ └──────────────┘ └────────────┘ └─────────────┘  │
│  ┌──────────┐ ┌──────────────┐ ┌────────────┐ ┌─────────────┐  │
│  │  Models  │ │  Middleware  │ │  Policies  │ │    Views    │  │
│  │ (8 buah) │ │  (Role-Based)│ │ (Dieset)   │ │  (Blade)    │  │
│  └──────────┘ └──────────────┘ └────────────┘ └─────────────┘  │
└───────────────────────────┬─────────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────────────┐
│                      DATA LAYER                                 │
│                MySQL 8.x Database                               │
│            Database: compotech2026                              │
│                  (17 Tabel Migration)                           │
└─────────────────────────────────────────────────────────────────┘
```

### 3.2 Pola Desain yang Diterapkan

| Pola | Implementasi di PT Compotec DAS |
|------|---------------------------------|
| **MVC (Model-View-Controller)** | Pola utama Laravel — semua modul mengikuti pola ini |
| **Observer Pattern** | `AuditObserver` otomatis mencatat setiap Create/Update/Delete pada model master |
| **Repository / Policy Pattern** | `MasterDiesetPolicy` mengontrol otorisasi akses Admin-only |
| **Middleware Pattern** | `RoleMiddleware` mengamankan route berdasarkan role pengguna |
| **Eager Loading (Anti N+1)** | Semua relasi Eloquent dimuat dengan `with()` untuk mencegah N+1 Query |
| **Soft Deletes** | Model `MasterDieset`, `MasterPart`, `MasterInspection` menggunakan `SoftDeletes` |

---

## 4. Teknologi Stack

### 4.1 Backend

| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| Language | PHP | 8.2+ (Production: 8.3-FPM) |
| Framework | Laravel | 12.x |
| Authentication | Laravel Breeze | ^2.3 |
| Excel Export | Maatwebsite / Laravel Excel | ^3.1 |
| ORM | Eloquent ORM | (bundled Laravel) |
| Queue | Sync Queue | (Phase 1 — tanpa Redis) |
| Mail | SMTP / Log Driver | Konfigurasi via `.env` |

### 4.2 Frontend

| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| CSS Framework | TailwindCSS | ^3.x |
| JavaScript | Alpine.js | (bundled via Breeze) |
| Template Engine | Laravel Blade Components | (bundled Laravel) |
| Build Tool | Vite | ^6.x |
| Bundler | Node.js + npm | — |

> **Catatan Desain PT Compotec:** Frontend dibangun **tanpa jQuery** untuk memastikan performa yang ringan dan modern. Seluruh interaktivitas UI dikelola oleh Alpine.js.

### 4.3 Infrastructure

| Komponen | Teknologi | Detail |
|----------|-----------|--------|
| Web Server | Nginx | Reverse proxy + PHP-FPM |
| Database | MySQL | 8.x |
| OS Server | Linux (Ubuntu) | Server produksi |
| Domain | compotec.arsyi-project.web.id | IP: 103.126.116.44 |
| Storage | Local Filesystem | `storage/app/public` (via symlink) |

---

## 5. Struktur Database

### 5.1 Diagram Relasi Entitas (ERD)

```
USERS (1) ────────────────────────── (M) ROLE_USER (pivot) ────── (M) ROLES
  │                                                                      
  │ (1)                                                                  
  │                                                                      
  ▼ (M)                                                                  
AUDIT_LOGS                                                               
                                                                          
MASTER_DIESETS (1) ──────────────────────────────── (M) MASTER_PARTS
                                                              │          
                                                              │ (1)      
                                                              ▼ (M)      
                                                    MASTER_INSPECTIONS   
                                                              │          
                                                              │ (1)      
                                                              ▼ (M)      
                                                    INSPECTION_HISTORIES 
                                                    (operator_id → USERS)
```

### 5.2 Detail Tabel

#### Tabel `users`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | BIGINT PK | Primary Key |
| `name` | VARCHAR | Nama lengkap pengguna |
| `email` | VARCHAR UNIQUE | Email login |
| `nik` | VARCHAR | Nomor Induk Karyawan |
| `department` | VARCHAR | Departemen di PT Compotec |
| `password` | VARCHAR | Password (bcrypt hashed) |
| `created_at` | TIMESTAMP | — |
| `updated_at` | TIMESTAMP | — |

#### Tabel `roles`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | BIGINT PK | Primary Key |
| `name` | VARCHAR UNIQUE | Nama role (`Admin`, `Supervisor`, `Maintenance`, `Operator`) |
| `description` | TEXT | Deskripsi role |
| `created_at` | TIMESTAMP | — |
| `updated_at` | TIMESTAMP | — |

#### Tabel `role_user` (Pivot)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | BIGINT PK | Primary Key |
| `user_id` | BIGINT FK | Referensi ke `users.id` |
| `role_id` | BIGINT FK | Referensi ke `roles.id` |
| *(unique composite)* | | `user_id + role_id` unik |

#### Tabel `audit_logs`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | BIGINT PK | Primary Key |
| `user_id` | BIGINT FK | Siapa yang melakukan aksi |
| `action` | VARCHAR | `created`, `updated`, `deleted` |
| `table_name` | VARCHAR | Nama tabel yang dimodifikasi |
| `record_id` | BIGINT | ID record yang dimodifikasi |
| `old_values` | JSON | Data sebelum perubahan |
| `new_values` | JSON | Data sesudah perubahan |
| `created_at` | TIMESTAMP | Waktu kejadian |

#### Tabel `master_diesets`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | BIGINT PK | Primary Key |
| `name` | VARCHAR | Nama mesin Dieset |
| `product_code` | VARCHAR | Kode produk |
| `production_year` | YEAR | Tahun produksi mesin |
| `total_shoot` | INT | Total shoot mesin |
| `description` | TEXT | Keterangan tambahan |
| `status` | ENUM | Status aktif/nonaktif |
| `created_at` | TIMESTAMP | — |
| `updated_at` | TIMESTAMP | — |
| `deleted_at` | TIMESTAMP | Soft delete |

#### Tabel `master_parts`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | BIGINT PK | Primary Key |
| `name` | VARCHAR | Nama part |
| `part_code` | VARCHAR | Kode part |
| `description` | TEXT | Deskripsi part |
| `dieset_id` | BIGINT FK | Referensi ke `master_diesets.id` |
| `cavity_number` | VARCHAR | Nomor cavity |
| `current_stock` | INT | Stok saat ini |
| `image_path` | VARCHAR | Path gambar part |
| `status` | ENUM | Status part |
| `created_at` | TIMESTAMP | — |
| `updated_at` | TIMESTAMP | — |
| `deleted_at` | TIMESTAMP | Soft delete |

#### Tabel `inspection_histories`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | BIGINT PK | Primary Key |
| `part_id` | BIGINT FK | Referensi ke `master_parts.id` |
| `operator_id` | BIGINT FK | Referensi ke `users.id` (operator) |
| `inspection_date` | DATETIME | Tanggal & waktu inspeksi |
| `condition` | VARCHAR | Kondisi part saat inspeksi |
| `damage_details` | TEXT | Detail kerusakan yang ditemukan |
| `action_taken` | TEXT | Tindakan perbaikan yang dilakukan |
| `reason` | TEXT | Alasan tindakan |
| `evidence_photo_path` | VARCHAR | Path foto bukti inspeksi |
| `report_sent` | BOOLEAN | Status apakah laporan sudah dikirim |
| `created_at` | TIMESTAMP | — |
| `updated_at` | TIMESTAMP | — |

#### Tabel `master_inspections`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | BIGINT PK | Primary Key |
| `name` | VARCHAR | Nama checklist inspeksi |
| `description` | TEXT | Deskripsi item inspeksi |
| `part_id` | BIGINT FK | Referensi ke `master_parts.id` |
| `status` | ENUM | Status aktif/nonaktif |
| `deleted_at` | TIMESTAMP | Soft delete |

#### Tabel `email_reports`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | BIGINT PK | Primary Key |
| `email` | VARCHAR | Alamat email Supervisor penerima |
| `created_at` | TIMESTAMP | — |
| `updated_at` | TIMESTAMP | — |

---

## 6. Modul & Fitur Sistem

### 6.1 Modul Autentikasi

**Deskripsi:** Mengelola proses login dan sesi pengguna di sistem DAS PT Compotec.

**Fitur:**
- Login dengan email dan password (Laravel Breeze).
- Proteksi CSRF pada semua form.
- Redirect otomatis ke Dashboard setelah login berhasil.
- Manajemen profil pengguna (ubah nama, email, password).
- Logout dan pembersihan sesi.

**Route terkait:**
```
GET  /login         → Halaman login
POST /login         → Proses autentikasi
POST /logout        → Proses logout
GET  /profile       → Halaman edit profil
PATCH /profile      → Simpan perubahan profil
```

---

### 6.2 Modul Dashboard

**Deskripsi:** Halaman utama yang menampilkan ringkasan kondisi mesin dan stok PT Compotec.

**Fitur:**
- Statistik total Dieset aktif.
- Jumlah part dengan stok kritis (*Low Stock*).
- Jumlah inspeksi dalam 30 hari terakhir.
- Indikator visual (warna) untuk kondisi kritis vs aman.

**Controller:** `DashboardController`

---

### 6.3 Modul Dieset Status (Monitoring Stok)

**Deskripsi:** Halaman pemantauan utama yang menampilkan status setiap mesin *Dieset* beserta kondisi seluruh *parts*-nya. Ini adalah halaman inti operasional harian PT Compotec.

**Fitur:**
- Daftar semua Dieset aktif beserta indicator stok.
- **Auto-Indicator Warna:**
  - 🔴 **Merah/Pink** — Ada satu atau lebih part di dalam Dieset dengan stok ≤ 2 (kritis).
  - 🟢 **Hijau** — Semua part memiliki stok aman (> 2).
- **Modal Detail:** Klik baris Dieset → modal muncul menampilkan daftar part berdasarkan kategori, *actual shoot*, *max shoot*, dan gambar part.
- **Export Excel:** Ekspor data status Dieset ke format Excel sesuai standar PT Compotec.

**Route terkait:**
```
GET  /dieset-status          → Daftar Dieset + status stok
GET  /dieset-status/{id}     → Detail parts per Dieset (modal)
GET  /dieset-status/export   → Export Excel
```

**Controller:** `DiesetStatusController`

---

### 6.4 Modul Inspection Monitor (Histori Perawatan)

**Deskripsi:** Menampilkan seluruh riwayat inspeksi yang dicatat oleh Mekanik/Operator dari lantai produksi.

**Fitur:**
- Daftar riwayat inspeksi dengan detail: kondisi, kerusakan, tindakan, parts yang diganti.
- **Filter Tanggal:** Filter data berdasarkan rentang tanggal (Tanggal Awal & Tanggal Akhir).
- **Bukti Foto:** Tampilkan foto bukti (*evidence photo*) yang diupload saat inspeksi.
- **Export Excel:** Ekspor data riwayat inspeksi untuk kebutuhan laporan.

**Role yang Dapat Mengakses:** Admin, Supervisor (monitoring); Admin, Maintenance (CRUD); Admin, Operator, Maintenance (input baru).

**Route terkait:**
```
GET  /inspections              → Daftar inspeksi (semua role)
GET  /inspections/{id}         → Detail inspeksi
GET  /inspections/create       → Form input inspeksi (Operator, Maintenance)
POST /inspections              → Simpan inspeksi baru
PUT  /inspections/{id}         → Update inspeksi (Maintenance)
DELETE /inspections/{id}       → Hapus inspeksi (Maintenance)
GET  /monitoring               → Inspection Monitor — SPV view
GET  /export                   → Export laporan inspeksi
```

**Controller:** `InspectionController`

---

### 6.5 Modul Parts Stock & Email Notifikasi

**Deskripsi:** Menampilkan resume stok seluruh *spare parts* dan menyediakan fitur pengiriman laporan otomatis via email ke Supervisor.

**Fitur:**
- Tab navigasi: **All Stock** | **Low Stock** | **Safe Stock**.
- Tampilan visual stok dengan indikator warna per batas kritis.
- **Mail to SPV:**
  - Sistem mengumpulkan semua part dengan stok rendah (*Low Stock*).
  - Menghasilkan lampiran Excel otomatis berisi daftar part kritis.
  - Mengirimkan email ke seluruh alamat Supervisor yang terdaftar di `email_reports`.

**Route terkait:**
```
GET  /parts-stock          → Halaman stok parts
POST /parts-stock/mail-spv → Kirim email laporan ke SPV
```

**Controller:** `PartsStockController`

---

### 6.6 Modul Master Data (Admin Only)

**Deskripsi:** Pengelolaan data referensi (master) yang menjadi fondasi seluruh operasional sistem DAS PT Compotec.

#### 6.6.1 Master Dieset
- CRUD lengkap data mesin Dieset.
- Generate parts secara batch untuk satu Dieset.
- Update dan hapus individual part dari dalam halaman Dieset.
- Proteksi hapus: Dieset tidak bisa dihapus jika masih memiliki parts aktif (`MasterDiesetPolicy`).

#### 6.6.2 Master Parts
- CRUD lengkap data spare parts.
- Upload gambar part.
- **Sync Wings:** Sinkronisasi data dari sistem "Wings" (sistem internal PT Compotec).

#### 6.6.3 Master Inspections
- CRUD lengkap untuk checklist item inspeksi (kamus inspeksi per part).

**Route terkait:**
```
Resource /master-diesets          → CRUD Dieset
POST     /master-diesets/{id}/generate-parts → Batch generate parts
PUT      /master-diesets/{did}/parts/{pid}   → Update part
DELETE   /master-diesets/{did}/parts/{pid}   → Hapus part
Resource /master-parts            → CRUD Parts
POST     /master-parts/sync-wings → Sinkronisasi Wings
Resource /master-inspections      → CRUD Inspections
```

---

### 6.7 Modul Audit Logs

**Deskripsi:** Rekaman jejak (*audit trail*) otomatis atas seluruh perubahan data master di sistem PT Compotec.

**Cara Kerja:**
- Menggunakan **Eloquent Observer Pattern** (`AuditObserver`).
- Observer dilampirkan ke model: `MasterDieset`, `MasterPart`, `MasterInspection`.
- Setiap operasi `Create`, `Update`, dan `Delete` akan secara otomatis mencatat:
  - **Siapa** yang melakukan (user ID & nama).
  - **Kapan** operasi dilakukan (timestamp).
  - **Modul apa** yang dimodifikasi (nama tabel).
  - **Data lama vs data baru** (JSON diff).

**Route terkait:**
```
GET /audit-logs       → Daftar log (Admin)
GET /audit-logs/{id}  → Detail log
```

**Controller:** `AuditLogController`

---

### 6.8 Modul Administrator Settings

**Deskripsi:** Pengaturan sistem yang hanya dapat diakses oleh Admin PT Compotec.

#### 6.8.1 Manajemen Operator
- Daftar dan pengelolaan akun Operator (untuk akses Mobile App di masa depan).
- CRUD Operator: tambah, edit, hapus (tanpa halaman create/show/edit terpisah).

#### 6.8.2 Manajemen Email Report
- Kelola daftar alamat email Supervisor yang menjadi penerima laporan stok kritis.
- CRUD Email: tambah, edit, hapus (inline management).

---

## 7. Role & Hak Akses (RBAC)

Sistem DAS PT Compotec mengimplementasikan **Role-Based Access Control (RBAC)** secara dinamis menggunakan `RoleMiddleware`.

### 7.1 Daftar Role

| Role | Deskripsi |
|------|-----------|
| **Admin** | Super akses — bypass semua middleware, akses ke seluruh modul |
| **Supervisor** | Akses monitoring, laporan, dan notifikasi email |
| **Maintenance** | Akses CRUD penuh pada data inspeksi (kecuali index/show) |
| **Operator** | Hanya dapat menginput data inspeksi baru (create/store) |

### 7.2 Matriks Hak Akses

| Modul / Fitur | Admin | Supervisor | Maintenance | Operator |
|---|:---:|:---:|:---:|:---:|
| Dashboard | ✅ | ✅ | ✅ | ✅ |
| Dieset Status (Lihat) | ✅ | ✅ | ✅ | ✅ |
| Dieset Status (Export) | ✅ | ✅ | ✅ | ✅ |
| Inspection Monitor | ✅ | ✅ | ❌ | ❌ |
| Export Laporan Inspeksi | ✅ | ✅ | ❌ | ❌ |
| Parts Stock (Lihat) | ✅ | ✅ | ❌ | ❌ |
| Mail to SPV | ✅ | ✅ | ❌ | ❌ |
| Input Inspeksi Baru | ✅ | ❌ | ✅ | ✅ |
| Edit / Hapus Inspeksi | ✅ | ❌ | ✅ | ❌ |
| Master Dieset (CRUD) | ✅ | ❌ | ❌ | ❌ |
| Master Parts (CRUD) | ✅ | ❌ | ❌ | ❌ |
| Master Inspections (CRUD) | ✅ | ❌ | ❌ | ❌ |
| Audit Logs | ✅ | ❌ | ❌ | ❌ |
| Manajemen Operator | ✅ | ❌ | ❌ | ❌ |
| Manajemen Email Report | ✅ | ❌ | ❌ | ❌ |

### 7.3 Super Admin Bypass

Role **Admin** di PT Compotec DAS memiliki kemampuan **Super Admin Bypass**, yaitu seluruh pengecekan middleware diabaikan. Admin dapat mengakses semua route tanpa hambatan apapun.

---

## 8. Keamanan Sistem

Sistem DAS PT Compotec dirancang dengan standar keamanan **DevSecOps** yang mencakup:

### 8.1 Autentikasi & Sesi
| Mekanisme | Implementasi |
|-----------|-------------|
| Password Hashing | Bcrypt (Laravel default) |
| CSRF Protection | Laravel CSRF Token (semua form POST/PUT/DELETE) |
| Session | File-based session, lifetime 120 menit |
| Middleware Auth | Semua route dilindungi `auth` middleware |

### 8.2 Otorisasi
| Mekanisme | Implementasi |
|-----------|-------------|
| Role-Based Middleware | `RoleMiddleware` — validasi role per route group |
| Policy | `MasterDiesetPolicy` — otorisasi level record |
| Route Protection | Semua route bisnis di dalam `auth` middleware group |

### 8.3 Web Security Headers (Nginx)
| Header | Nilai | Tujuan |
|--------|-------|--------|
| `X-Frame-Options` | `SAMEORIGIN` | Cegah Clickjacking |
| `X-Content-Type-Options` | `nosniff` | Cegah MIME-type sniffing |

### 8.4 Database Security
- Seluruh query menggunakan **Eloquent ORM** dengan parameter binding — terlindungi dari SQL Injection.
- Database credential disimpan di file `.env` dan **tidak** di-commit ke repository Git (`.gitignore`).
- User database MySQL terpisah (`compotech`) dengan privilege terbatas.

### 8.5 File Upload Security
- File upload (gambar part & bukti inspeksi) disimpan di `storage/app/public` yang tidak dapat diakses langsung via URL tanpa symlink.
- Validasi tipe file dan ukuran dilakukan di layer `FormRequest`.

### 8.6 Audit Trail
- Seluruh perubahan data master direkam otomatis oleh `AuditObserver`.
- Log mencakup: siapa, kapan, apa yang berubah (old vs new values dalam JSON).
- Log bersifat *append-only* (tidak dapat dihapus melalui UI).

---

## 9. Infrastruktur & Deployment

### 9.1 Konfigurasi Server Produksi

| Komponen | Detail |
|----------|--------|
| **IP Server** | 103.126.116.44 |
| **Domain** | compotec.arsyi-project.web.id |
| **Web Server** | Nginx |
| **PHP Version** | PHP 8.3 (php8.3-fpm) |
| **Document Root** | `/var/www/compotech2026/public` |
| **Database** | MySQL — `compotech2026` |

### 9.2 Konfigurasi Nginx

File konfigurasi Nginx untuk PT Compotec (`nginx_compotech.conf`):

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name compotec.arsyi-project.web.id 103.126.116.44;
    root /var/www/compotech2026/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html index.htm;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 9.3 Langkah Deployment (Production)

```bash
# 1. Upload dan ekstrak arsip
tar -xzf deploy.tar.gz -C /var/www/compotech2026

# 2. Install PHP dependencies
composer install --no-dev --optimize-autoloader

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Build frontend assets
npm install
npm run build

# 5. Jalankan migrasi
php artisan migrate --force

# 6. Buat symlink storage
php artisan storage:link

# 7. Atur permission
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data /var/www/compotech2026

# 8. Reload Nginx
sudo nginx -t && sudo systemctl reload nginx
```

### 9.4 Prosedur Setup Akun Admin Pertama

Setelah deployment, buat akun Admin pertama menggunakan script:

```bash
php create_admin.php
```

Script ini akan membuat user Admin pertama dengan role `Admin` yang dapat digunakan untuk login ke sistem DAS PT Compotec.

---

## 10. Alur Kerja Pengguna (User Flow)

### 10.1 Alur Login

```
User buka browser
     ↓
Akses URL: compotec.arsyi-project.web.id
     ↓
Redirect ke halaman Login
     ↓
Input email & password
     ↓
Autentikasi Laravel Breeze
     ↓
[Gagal] → Error message, kembali ke form login
[Berhasil] → Redirect ke Dashboard
     ↓
Dashboard menampilkan ringkasan sesuai role user
```

### 10.2 Alur Monitoring Stok (Peran: Semua Role)

```
Login → Dashboard
     ↓
Klik menu "Dieset Status"
     ↓
Lihat daftar Dieset dengan indikator warna:
  🔴 Merah = Ada part kritis (stok ≤ 2)
  🟢 Hijau = Semua stok aman
     ↓
Klik baris Dieset untuk melihat detail
     ↓
Modal muncul: daftar parts per kategori
(nama, kode, stok saat ini, gambar)
     ↓
[Opsional] Klik "Export Excel" untuk unduh laporan
```

### 10.3 Alur Input Inspeksi (Peran: Operator / Maintenance)

```
Login → Dashboard
     ↓
Klik menu "Inspeksi"
     ↓
Klik tombol "Tambah Inspeksi Baru"
     ↓
Pilih Dieset → Pilih Part → Isi form inspeksi:
  - Tanggal inspeksi
  - Kondisi part
  - Detail kerusakan
  - Tindakan yang diambil
  - Upload foto bukti
     ↓
Submit → Data tersimpan ke tabel inspection_histories
     ↓
AuditObserver TIDAK mencatat (hanya untuk tabel master)
     ↓
Kembali ke daftar inspeksi
```

### 10.4 Alur Kirim Notifikasi Email (Peran: Supervisor / Admin)

```
Login → Dashboard
     ↓
Klik menu "Parts Stock"
     ↓
Lihat tab "Low Stock" — daftar part kritis
     ↓
Klik tombol "Mail to SPV"
     ↓
Sistem query semua parts dengan stok rendah
     ↓
Generate file Excel lampiran secara otomatis
     ↓
Kirim email ke seluruh alamat di tabel email_reports
     ↓
Konfirmasi sukses / pesan error ditampilkan
```

### 10.5 Alur Perubahan Data Master (Peran: Admin)

```
Login (Admin) → Dashboard
     ↓
Klik menu "Master Data" → Pilih (Dieset / Parts / Inspections)
     ↓
Lakukan operasi CRUD (Create / Update / Delete)
     ↓
Validasi FormRequest dijalankan
     ↓
[Gagal validasi] → Error message, kembali ke form
[Berhasil] → Data disimpan ke database
     ↓
AuditObserver otomatis mencatat log ke tabel audit_logs:
  {user_id, action, table_name, record_id, old_values, new_values}
     ↓
Flash message sukses ditampilkan
```

---

## 11. Roadmap Pengembangan

### Phase 1 — Core System ✅ (Selesai)

| # | Fitur | Status |
|---|-------|--------|
| 1.1 | Autentikasi & Multi-Role RBAC | ✅ Done |
| 1.2 | Audit Logs (Observer Pattern) | ✅ Done |
| 1.3 | Dashboard & Dieset Status Monitor | ✅ Done |
| 1.4 | Inspection Monitor + Filter + Export | ✅ Done |
| 1.5 | Parts Stock + Auto-Indicator + Mail to SPV | ✅ Done |
| 1.6 | Infrastruktur Produksi (Nginx + PHP-FPM) | ✅ Done |

### Phase 2 — Master Data & Admin Settings 🚧 (Sedang Berjalan)

| # | Fitur | Target |
|---|-------|--------|
| 2.1 | Master Dieset CRUD lengkap + Batch Generate Parts | Q4 2026 |
| 2.2 | Master Parts CRUD + Upload Gambar + Sync Wings | Q4 2026 |
| 2.3 | Master Inspections CRUD (Dictionary) | Q4 2026 |
| 2.4 | Manajemen Operator (Mobile App User) | Q4 2026 |
| 2.5 | Manajemen Email Report | Q4 2026 |

### Phase 3 — Mobile App API 📋 (Direncanakan)

| # | Fitur | Target |
|---|-------|--------|
| 3.1 | RESTful API endpoint untuk inspeksi dari lapangan | Q1 2027 |
| 3.2 | API upload gambar bukti inspeksi | Q1 2027 |
| 3.3 | API update *actual shoot* mesin | Q1 2027 |
| 3.4 | API autentikasi Mobile (Token-based) | Q1 2027 |
| 3.5 | Pengembangan Aplikasi Mobile (Android/iOS) | Q2 2027 |

### Phase 4 — Enhancement & Integrasi 🔭 (Visi Jangka Panjang)

| # | Fitur | Target |
|---|-------|--------|
| 4.1 | Integrasi sistem "Wings" (full sync) | Q2 2027 |
| 4.2 | Dashboard analytics & grafik tren produksi | Q3 2027 |
| 4.3 | Notifikasi push (Mobile App) untuk stok kritis | Q3 2027 |
| 4.4 | SSL/HTTPS (Let's Encrypt) untuk domain PT Compotec | Q4 2026 |
| 4.5 | Queue & Redis untuk pengiriman email asinkron | Q1 2027 |

---

## 12. Standar Pengkodean

### 12.1 Konvensi Penamaan

| Elemen | Konvensi | Contoh |
|--------|----------|--------|
| Class (PHP) | PascalCase | `MasterDiesetController` |
| Method (PHP) | camelCase | `storeGeneratedParts()` |
| Variable (PHP) | camelCase | `$diecastMachine` |
| Table (DB) | snake_case (plural) | `master_diesets` |
| Column (DB) | snake_case | `dieset_id`, `current_stock` |
| Route Name | kebab-case dengan titik | `master-diesets.generate-parts` |
| Blade View | kebab-case | `dieset-status/index.blade.php` |

### 12.2 Prinsip Kode

1. **Anti N+1 Query** — Semua relasi wajib di-*eager load* menggunakan `with()`.
2. **FormRequest Validation** — Semua input user wajib divalidasi via dedicated `FormRequest` class.
3. **Soft Deletes** — Data master **tidak boleh** dihapus secara fisik (*hard delete*). Gunakan `SoftDeletes`.
4. **Observer untuk Side Effects** — Logic audit, notifikasi, dan side effect lain diletakkan di Observer, bukan di Controller.
5. **Policy untuk Otorisasi Level Record** — Gunakan Laravel Policy untuk otorisasi yang memerlukan pengecekan data spesifik.
6. **No jQuery** — Seluruh interaktivitas frontend menggunakan Alpine.js murni.

### 12.3 Struktur Direktori

```
compotech2026/
├── app/
│   ├── Exports/          # Kelas Export Excel (Maatwebsite)
│   ├── Http/
│   │   ├── Controllers/  # 12 Controller
│   │   ├── Middleware/   # RoleMiddleware
│   │   └── Requests/     # FormRequest classes
│   ├── Mail/             # Mailable classes (email ke SPV)
│   ├── Models/           # 8 Eloquent Model
│   ├── Observers/        # AuditObserver
│   ├── Policies/         # MasterDiesetPolicy
│   ├── Providers/        # Service Providers
│   └── View/             # View Composers
├── database/
│   ├── migrations/       # 17 file migrasi
│   ├── seeders/          # Role seeders
│   └── factories/        # Model factories (testing)
├── resources/
│   └── views/            # Blade templates
├── routes/
│   ├── web.php           # Route utama aplikasi
│   └── auth.php          # Route autentikasi
├── public/               # Document root (Nginx)
├── storage/              # File upload & logs
└── nginx_compotech.conf  # Konfigurasi Nginx produksi
```

---

## 13. Glosarium

| Istilah | Definisi |
|---------|----------|
| **DAS** | Diecast Application System — nama sistem informasi yang dibangun untuk PT Compotec |
| **Dieset** | Nama mesin/komponen utama dalam proses produksi *Diecast* di PT Compotec |
| **Parts / Spare Parts** | Komponen pengganti di dalam mesin Dieset yang memiliki stok dan batas usia pakai |
| **Inspection** | Proses pemeriksaan kondisi fisik part mesin yang dilakukan oleh Mekanik di lapangan |
| **Actual Shoot** | Jumlah tembakan (*shot*) aktual yang sudah dilakukan mesin hingga saat ini |
| **Max Shoot** | Batas maksimum tembakan sebelum sebuah part harus diganti |
| **Low Stock** | Kondisi di mana stok sebuah part ≤ 2 unit (batas kritis PT Compotec) |
| **Wings** | Sistem internal PT Compotec yang menjadi sumber data referensi parts |
| **RBAC** | Role-Based Access Control — sistem kontrol akses berdasarkan role/jabatan pengguna |
| **Audit Log** | Catatan otomatis yang merekam semua perubahan data di sistem (siapa, kapan, apa) |
| **Observer Pattern** | Pola desain perangkat lunak di mana objek secara otomatis bereaksi terhadap event |
| **Soft Delete** | Metode "penghapusan lunak" — data ditandai sebagai terhapus tetapi tetap ada di database |
| **Eager Loading** | Teknik memuat relasi database sekaligus untuk mencegah masalah N+1 Query |
| **N+1 Query** | Masalah performa di mana 1 query utama menghasilkan N query tambahan yang tidak perlu |
| **SPV** | Supervisor — jabatan yang berwenang menerima laporan stok kritis via email |
| **DevSecOps** | Pendekatan pengembangan yang mengintegrasikan keamanan (Security) ke dalam proses DevOps |
| **PHP-FPM** | PHP FastCGI Process Manager — cara menjalankan PHP di server produksi bersama Nginx |

---

*Dokumen ini merupakan properti PT Compotec. Seluruh informasi bersifat rahasia dan hanya untuk keperluan internal.*

---
**© 2026 PT Compotec — Diecast Application System (DAS)**
*Blueprint Versi 1.0.0 | Disusun: 24 September 2026*
