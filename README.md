<div align="center">

# 🚀 GO Business

### Sistem Informasi Manajemen UMKM Berbasis Web

**GO Business** adalah aplikasi manajemen bisnis digital yang dirancang khusus untuk pelaku UMKM agar dapat mengelola transaksi harian, stok produk, dan laporan keuangan secara efisien, terstruktur, dan berbasis data.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-4.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Vite](https://img.shields.io/badge/Vite-7.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
[![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

</div>

---

## 📋 Daftar Isi

- [Tentang Aplikasi](#-tentang-aplikasi)
- [Fitur Utama](#-fitur-utama)
- [Tech Stack](#-tech-stack)
- [Arsitektur Sistem](#-arsitektur-sistem)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [Struktur Direktori](#-struktur-direktori)
- [Modul Aplikasi](#-modul-aplikasi)
- [Kontribusi](#-kontribusi)

---

## 🎯 Tentang Aplikasi

**GO Business** adalah Sistem Informasi Manajemen (SIM) yang dirancang untuk membantu pelaku Usaha Mikro, Kecil, dan Menengah (UMKM) dalam mendigitalisasi proses bisnis mereka. Aplikasi ini menyediakan ekosistem terintegrasi yang mencakup pencatatan transaksi, manajemen stok, pelaporan keuangan, hingga analitik visual berbasis data.

Sistem ini dibangun berdasarkan **tiga level hierarki manajemen**:

| Level | Fokus |
|-------|-------|
| 🔵 **Operasional** | Kelancaran arus barang dan transaksi harian |
| 🟡 **Taktis** | Evaluasi performa produk dan pencapaian bulanan |
| 🔴 **Strategis** | Analisis tren jangka panjang untuk pertumbuhan bisnis |

---

## ✨ Fitur Utama

### 🔐 Autentikasi & Keamanan
- Login, Registrasi, dan Logout yang aman
- Reset password mandiri (tanpa email) via verifikasi identitas
- Enkripsi password dengan Bcrypt (12 rounds)
- Isolasi data penuh per akun pengguna

### 🏪 Pemilihan Tipe Bisnis
- Pengguna memilih jenis usaha saat pertama kali masuk
- Tampilan dan fitur disesuaikan dengan tipe bisnis yang dipilih

### 📊 Dashboard Utama
- Ringkasan omzet hari ini vs kemarin
- Statistik total transaksi, produk, dan stok rendah
- Grafik tren penjualan mingguan (real-time)
- Notifikasi produk mendekati kedaluwarsa

### 💳 Manajemen Transaksi
- Input penjualan produk secara real-time
- Dukungan berbagai metode pembayaran (Tunai, Transfer, QRIS)
- Upload bukti pembayaran
- Stok berkurang otomatis setelah transaksi berhasil

### 📦 Manajemen Stok (Produk)
- CRUD produk lengkap (Nama, Kategori, Harga, Stok, Gambar)
- Pengaturan harga diskon dan pajak per produk
- Alert otomatis untuk stok rendah & hampir habis
- Manajemen tanggal kedaluwarsa produk

### 📈 Laporan Keuangan
- Rekap penjualan harian dan bulanan
- Ranking produk terlaris (Best Seller)
- Filter laporan berdasarkan rentang tanggal
- **Export laporan ke CSV/Excel**

### 📉 Analitik & Visualisasi
- Grafik tren penjualan (Line Chart & Bar Chart)
- Distribusi produk per kategori (Pie Chart)
- Analisis performa bulanan berbasis data historis

### 👤 Profil & Pengaturan
- Update data profil (nama, email, nama bisnis)
- Ganti password
- Opsi hapus akun

---

## 🛠 Tech Stack

| Layer | Teknologi |
|-------|-----------|
| **Backend Framework** | Laravel 12.x (PHP 8.2+) |
| **Frontend Styling** | Tailwind CSS v4 |
| **Build Tool** | Vite 7.x |
| **Database** | MySQL 8.x / SQLite (development) |
| **ORM** | Eloquent Laravel |
| **HTTP Client** | Axios |
| **Dev Tools** | Laravel Pail, Laravel Pint, Laravel Sail |
| **Testing** | PHPUnit 11.x |

---

## 🏗 Arsitektur Sistem

```
GO Business
│
├── Entry Point (Login / Registrasi)
│    └── Verifikasi identitas & reset password mandiri
│
├── Business Selection
│    └── Pilih tipe usaha → Redirect ke Dashboard
│
├── Dashboard (Pusat Kendali)
│    ├── Ringkasan harian & tren mingguan
│    └── Alert stok rendah & produk kedaluwarsa
│
├── Empat Modul Inti
│    ├── 💳 Transaksi  → Input penjualan + update stok otomatis
│    ├── 📦 Stok       → CRUD produk, diskon, pajak, expiry
│    ├── 📋 Laporan    → Rekap omzet, ranking, export CSV
│    └── 📊 Analitik  → Grafik tren (Line, Bar, Pie)
│
└── Output → Pengambilan Keputusan Berbasis Data
```

---

## ⚙️ Persyaratan Sistem

Pastikan lingkungan pengembangan Anda memenuhi persyaratan berikut:

- **PHP** >= 8.2
- **Composer** >= 2.x
- **Node.js** >= 18.x & **NPM** >= 9.x
- **MySQL** >= 8.0 (atau SQLite untuk development)
- **Git**

---

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/EkaRizqiRomadhon/gobusiness.git
cd gobusiness
```

### 2. Instalasi Dependensi PHP

```bash
composer install
```

### 3. Instalasi Dependensi Node.js

```bash
npm install
```

### 4. Salin File Environment

```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Jalankan Migrasi Database

```bash
php artisan migrate
```

> **Catatan:** Jika ingin menggunakan SQLite untuk development, pastikan file `database/database.sqlite` sudah ada (buat jika belum). Ubah `DB_CONNECTION=sqlite` di `.env`.

### ⚡ Instalasi Otomatis (Satu Perintah)

Alternatifnya, gunakan script setup yang sudah tersedia:

```bash
composer run setup
```

Script ini akan otomatis menjalankan `composer install`, `key:generate`, `migrate`, `npm install`, dan `npm run build`.

---

## 🔧 Konfigurasi

Edit file `.env` sesuai lingkungan Anda:

```env
# Identitas Aplikasi
APP_NAME="GO Business"
APP_ENV=local
APP_URL=http://127.0.0.1:8000

# Database (MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=go_business
DB_USERNAME=root
DB_PASSWORD=

# Queue (opsional, untuk background jobs)
QUEUE_CONNECTION=database
```

---

## ▶️ Menjalankan Aplikasi

### Mode Development (Direkomendasikan)

Jalankan semua layanan sekaligus (server, queue, log watcher, dan Vite):

```bash
composer run dev
```

Atau jalankan secara terpisah:

```bash
# Terminal 1 - Laravel Server
php artisan serve

# Terminal 2 - Vite (HMR)
npm run dev
```

Akses aplikasi di: **http://127.0.0.1:8000**

### Build untuk Produksi

```bash
npm run build
```

---

## 📁 Struktur Direktori

```
GO_Business/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── BusinessSelectionController.php
│   │   │   ├── ProductController.php
│   │   │   ├── TransactionController.php
│   │   │   ├── ReportController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── CategoryController.php
│   │   │   └── SimplePasswordResetController.php
│   │   └── Middleware/
│   └── Models/
│       ├── User.php
│       ├── Product.php
│       ├── Category.php
│       ├── Transaction.php
│       └── TransactionItem.php
├── database/
│   └── migrations/          # 14 migration files
├── resources/
│   ├── views/
│   │   ├── auth/            # Login, Register, Reset Password
│   │   ├── layouts/         # Layout utama aplikasi
│   │   └── pages/
│   │       ├── dashboard.blade.php
│   │       ├── transactions/
│   │       ├── stock/
│   │       ├── expiry/
│   │       ├── reports/
│   │       ├── analytics/
│   │       └── profile/
│   ├── css/
│   └── js/
├── routes/
│   └── web.php              # Semua definisi route aplikasi
├── PRD_GO_Business.md       # Product Requirement Document
└── vite.config.js
```

---

## 📦 Modul Aplikasi

### Route Map

| Method | URI | Fungsi |
|--------|-----|--------|
| `GET` | `/` | Halaman Login |
| `POST` | `/login` | Proses Login |
| `GET/POST` | `/register` | Registrasi akun baru |
| `GET/POST` | `/lupa-password` | Reset password mandiri |
| `GET/POST` | `/select-business` | Pemilihan tipe bisnis |
| `GET` | `/dashboard` | Dashboard utama |
| `GET/POST` | `/transactions` | Daftar & input transaksi |
| `GET` | `/stock` | Manajemen produk/stok |
| `GET` | `/stock/expiry` | Produk mendekati kedaluwarsa |
| `GET` | `/reports` | Laporan penjualan |
| `GET` | `/reports/export` | Export laporan (CSV) |
| `GET` | `/analytics` | Grafik & analitik |
| `GET/PUT` | `/profile` | Profil pengguna |
| `GET/DELETE` | `/settings` | Pengaturan akun |

---

## 🤝 Kontribusi

Proyek ini merupakan bagian dari tugas mata kuliah **Sistem Informasi Manajemen** di **Universitas Negeri Surabaya (UNESA)**.

Jika Anda ingin berkontribusi atau melaporkan bug, silakan buat *issue* atau *pull request* melalui repository ini.

---

<div align="center">

**Dibuat dengan ❤️ untuk UMKM Indonesia**

*GO Business v1.0 — Sistem Informasi Manajemen UMKM*

© 2026 Eka Rizqi Romadhon — UNESA

</div>
