# 🎓 PNJ PRIME — Sistem E-Commerce & Booking Jasa (Hilirisasi Riset)

**Politeknik Negeri Jakarta**  
*Platform Hilirisasi Produk Riset, Prototipe Fisik, dan Layanan Pengujian Laboratorium Terpadu.*

---

## 👥 Tim Pengembang

- **Ketua Tim:** Rifa
- **Anggota:** Anil, Rifqi, Adib, Boaz

---

## 📌 Tentang Proyek

**PNJ PRIME** adalah platform digital yang dirancang untuk menjembatani hasil riset inovatif sivitas akademika Politeknik Negeri Jakarta (Dosen dan Mahasiswa) dengan kebutuhan masyarakat umum dan mitra industri. 

Sistem ini mendukung tata kelola hilirisasi riset terintegrasi:
1. **Otentikasi Ganda:** Integrasi SSO (*Single Sign-On*) untuk Sivitas Akademika PNJ dan registrasi mandiri berbasis NIK/NPWP untuk mitra eksternal.
2. **Alur Kurasi Berjenjang:** Evaluasi kesiapan inovasi oleh Unit **P3M** (*Pusat Penelitian dan Pengabdian kepada Masyarakat*).
3. **Penetapan Harga Resmi:** Pengesahan HPP dan Harga Pasar SK resmi oleh **Pimpinan Kampus**.
4. **Pengelolaan Etalase & Jadwal Layanan:** Tata kelola publikasi etalase dan alokasi penjadwalan laboratorium oleh **RTPU** (*Unit Layanan Usaha Kampus*).
5. **Katalog Publik & Transaksi BLU:** Penyaluran seluruh penerimaan transaksi secara transparan ke rekening resmi Badan Layanan Umum (BLU) PNJ.

---

## 🔄 Alur Bisnis Hilirisasi Inovasi

```
[1. Dosen / Mahasiswa]
       │  (Ajukan Produk Fisik / Jasa Riset)
       ▼
[2. Kurasi P3M] ──────(Minta Revisi / Ditolak)───▶ Dikembalikan ke Pengaju
       │  (Lolos Validasi)
       ▼
[3. Pimpinan PNJ]
       │  (Penetapan HPP & Harga Jual Resmi SK)
       ▼
[4. RTPU (Unit Usaha)]
       │  (Input Narasi Promosi & Publikasikan ke Etalase)
       ▼
[5. Etalase Publik PNJ PRIME]
       │  (Dapat diakses Masyarakat & Mitra Industri)
       ▼
[6. Booking Jasa & Penjadwalan Lab oleh RTPU]
```

---

## 🎭 Peran Pengguna (Roles) & Hak Akses

| Peran (Role) | Akses & Tanggung Jawab Utama |
|---|---|
| **Dosen / Mahasiswa** | Mengajukan produk prototipe fisik atau jasa pengujian lab, merevisi ajuan, dan memantau status kurasi. |
| **P3M** | Memvalidasi kelayakan inovasi riset, menyetujui, meminta revisi perbaikan, atau menolak usulan. |
| **Pimpinan** | Memantau seluruh hasil kurasi inovasi dan menetapkan Harga Pokok Penjualan (HPP) serta Harga Jual pasar. |
| **RTPU (Admin Usaha)** | Memverifikasi keabsahan dokumen akun eksternal (KTP/NPWP), mengelola publikasi etalase promosi, dan mengatur jadwal layanan lab. |
| **Eksternal (Industri/Umum)** | Mendaftar akun dengan NIK/NPWP dan menjelajahi etalase katalog produk/jasa riset terapan kampus. |

---

## 🛠️ Tech Stack & Arsitektur Database

- **Backend Framework:** [Laravel](https://laravel.com/) (PHP 8.2+)
- **Database Engine:** [PostgreSQL 16+](https://www.postgresql.org/)
  - **Primary Key:** UUID v4 (`gen_random_uuid()` via ekstensi `pgcrypto`)
  - **Audit Logging:** Pencatatan jejak rekam aksi krusial menggunakan tipe data `JSONB` immutable
  - **Zona Waktu:** Seluruh kolom waktu menggunakan `TIMESTAMP WITH TIME ZONE`
  - **Data Integrity:** Restriksi foreign key relasional (`RESTRICT` / `CASCADE`) dan PostgreSQL Custom `ENUM`
- **Frontend / Styling:** [Tailwind CSS](https://tailwindcss.com/) & Blade Templating

---

## 🚀 Panduan Instalasi & Menjalankan Proyek

### 1. Prasyarat Sistem
- PHP >= 8.2 (dengan ekstensi `pdo_pgsql`, `pgsql`, `mbstring`, `openssl`, `curl`)
- Composer
- PostgreSQL Server 16/17/18
- Git

### 2. Kloning Repository
```bash
git clone https://github.com/pompomv/pnj-prime.git
cd pnj-prime
```

### 3. Instalasi Dependency
```bash
composer install
```

### 4. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi koneksi PostgreSQL pada file `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=pnjprime_db
DB_USERNAME=postgres
DB_PASSWORD=password_postgres_anda
```

### 5. Setup Database & Seeding
Jalankan migrasi database dan pengisian data uji coba:
```bash
php artisan migrate:fresh --seed
```

### 6. Menjalankan Server Aplikasi
```bash
php artisan serve
```
Akses aplikasi melalui browser di: **`http://127.0.0.1:8000`**

---

## 🔑 Akun Demo Pengujian (Seed Data)

Pada halaman login (`/login`), Anda dapat langsung mengklik baris akun demo untuk mengisi form secara instan:

| Peran (Role) | Email | Password | Keterangan |
|---|---|---|---|
| **Dosen** | `dosen@pnj.ac.id` | `password123` | Pengaju Inovasi & Prototipe Riset |
| **Mahasiswa** | `mahasiswa@pnj.ac.id` | `password123` | Pengaju Inovasi Mahasiswa |
| **P3M** | `p3m@pnj.ac.id` | `password123` | Tim Kurator & Validasi Kelayakan |
| **Pimpinan** | `pimpinan@pnj.ac.id` | `password123` | Penetapan HPP & Harga Jual Resmi |
| **RTPU** | `rtpu@pnj.ac.id` | `password123` | Admin Verifikasi, Etalase & Penjadwalan |
| **Eksternal** | `eksternal@gmail.com` | `password123` | Akun Mitra Industri (Status Disetujui) |

---

## 📂 Struktur Direktori Utama

```
pnj-prime/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # Otentikasi & session login
│   │   │   ├── DashboardController.php     # Distribusi tampilan sesuai peran
│   │   │   ├── InovasiController.php       # Manajemen ajuan produk/jasa riset
│   │   │   ├── KurasiController.php        # Validasi, revisi & tolak (P3M)
│   │   │   ├── HargaController.php         # Penetapan HPP & Harga SK (Pimpinan)
│   │   │   ├── RtpuController.php          # Verifikasi akun, publikasi & jadwal
│   │   │   └── MarketplaceController.php   # Katalog etalase produk/jasa publik
│   │   └── Middleware/
│   │       └── CheckRole.php               # Guard proteksi role-based access
│   └── Models/
│       ├── User.php                        # Sivitas internal & pengguna eksternal
│       ├── Inovasi.php                     # Entitas produk fisik & jasa riset
│       ├── RekeningBlu.php                 # Rekening terpusat kampus PNJ
│       ├── Transaksi.php                   # Header transaksi pemesanan
│       ├── DetailTransaksi.php             # Snapshot harga & item pesanan
│       ├── PenjadwalanJasa.php             # Penjadwalan layanan uji lab
│       ├── AuditLog.php                    # Jejak rekam aksi krusial (JSONB)
│       ├── MediaInovasi.php                # Galeri foto pendukung
│       └── Notifikasi.php                  # Notifikasi in-app
├── database/
│   ├── migrations/                         # DDL migrasi PostgreSQL (11 tabel)
│   ├── seeders/                            # Seeder akun, rekening & contoh inovasi
│   └── pnjprime_schema.sql                 # Skrip DDL PostgreSQL murni siap eksekusi
└── resources/views/
    ├── auth/login.blade.php                # Halaman login interaktif + quick fill
    ├── dashboard/                          # Dashboard spesifik tiap peran
    ├── inovasi/                            # Form pengajuan & detail riset
    ├── kurasi/                             # Antarmuka review & keputusan P3M
    ├── harga/                              # Form kalkulasi HPP & margin Pimpinan
    ├── rtpu/                               # Manajemen publikasi & jadwal lab
    └── marketplace/                        # Katalog etalase publik PNJ PRIME
```

---

## 📜 Lisensi & Hak Cipta

Dikembangkan untuk kebutuhan hilirisasi riset di lingkungan **Politeknik Negeri Jakarta (PNJ)**. Seluruh hak cipta dilindungi.
