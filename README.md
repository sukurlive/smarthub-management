## 📘 Smart-Hub Management System - README

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-13-red?style=flat-square&logo=laravel)
![InertiaJS](https://img.shields.io/badge/InertiaJS-Vue3-emerald?style=flat-square&logo=inertia)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-v4-blue?style=flat-square&logo=tailwindcss)
![PostgreSQL](https://img.shields.io/badge/Supabase-PostgreSQL-blueviolet?style=flat-square&logo=supabase)
![Git](https://img.shields.io/badge/Git-F05032?style=flat-square&logo=git&logoColor=white)

**Sistem Manajemen Peminjaman Ruang Kerja dan Peralatan Studio untuk Komunitas Kreatif**

[Fitur](#-fitur-utama) • [Teknologi](#-teknologi-yang-digunakan) • [Arsitektur Git](#-arsitektur-version-control-git) • [Instalasi](#-instalasi--setup) • [API Documentation](#-api-documentation)

</div>

---

## Tentang Aplikasi

**Smart-Hub Management System** adalah aplikasi web modern responsif (*mobile-responsive*) yang dirancang untuk membantu komunitas kreatif lokal mengelola peminjaman ruang kerja dan inventaris peralatan studio. Aplikasi ini mendukung dua jenis pengguna:
- **Admin** → Mengelola inventaris peralatan (Master Data CRUD) dan memonitor seluruh transaksi peminjaman.
- **Member** → Melakukan peminjaman alat yang tersedia dan melakukan *check-in* pengembalian secara mandiri melalui antarmuka responsif tablet/handheld.

---

## 🚀 Fitur Utama

### 1. Antarmuka Premium & Responsif (Web Client)
- **Desain Modern**: Antarmuka berbasis *dark mode* premium dengan visual *glassmorphism* dan ornamen animasi halus.
- **Mobile Responsive**: Tata letak adaptif khusus untuk tablet (*handheld*) dan smartphone. Layout tabel pada desktop bertransformasi otomatis menjadi layout kartu pada layar kecil.
- **Dashboard Interaktif**: Statistik real-time mengenai jumlah peralatan tersedia, sedang dipinjam, maintenance, dan riwayat peminjaman aktif.

### 2. Autentikasi & Token API
- Login terintegrasi langsung dengan API backend Laravel.
- Sistem mengamankan otorisasi dengan membangkitkan **Sanctum Token** setelah login, menyimpannya di browser `sessionStorage`, dan mengirimkannya otomatis sebagai header `Bearer Token` pada setiap request API (Axios).

### 3. Modul Data Master (Admin Only)
- **List Peralatan**: Menampilkan semua inventaris lengkap dengan badge status (`available`, `borrowed`, `maintenance`).
- **Create Peralatan**: Menambah peralatan baru via modal form interaktif dengan validasi input.
- **Delete Peralatan**: Menghapus item inventaris dengan konfirmasi modal untuk mencegah penghapusan tidak sengaja.

### 4. Modul Transaksi Peminjaman & Check-in
- **Pinjam Alat**: Member membuat transaksi peminjaman dengan memilih alat berstatus *Tersedia (available)* dan mengisi tanggal pinjam.
- **Check-in Pengembalian**: Mengembalikan alat yang sedang dipinjam secara langsung.
- **Validasi Transaksi**:
  - Mencegah *double check-in* pada transaksi yang statusnya sudah kembali.
  - Otomatis mengubah status alat kembali menjadi `available` agar siap dipinjam kembali.

---

## 🛠️ Teknologi yang Digunakan

| Kategori | Teknologi | Keterangan |
|----------|-----------|------------|
| **Backend Framework** | Laravel 13 | Core backend API & routing web |
| **Frontend Framework**| Inertia.js (Vue 3) | Single Page Application (SPA) bridge |
| **Styling Engine**    | Tailwind CSS v4 & Custom CSS | Desain premium, responsif, & glassmorphism |
| **Database Cloud**    | Supabase (PostgreSQL) | Serverless relational database hosting |
| **Authentication**    | Laravel Sanctum | Token-based API Authentication |
| **Version Control**   | Git | Manajemen kode & strategi kolaborasi |

---

## 📂 Arsitektur Version Control (Git)

Untuk mencegah bentrokan kode (*merge conflict*) antara tim Frontend dan Backend, aplikasi ini dirancang agar siap dipisahkan menggunakan **Dual-Repository Strategy**:
1. **Repositori Backend API**: Berisi migrasi database, model data, seeder, unit test API, dan controller REST API (`routes/api.php`).
2. **Repositori Web Frontend**: Berisi views utama (`resources/views/app.blade.php`), file JS/Vue (`resources/js/Pages` & `resources/js/Layouts`), dan rute antarmuka (`routes/web.php`) yang memanggil backend controller untuk merender halaman Inertia.

---

## 📥 Instalasi & Setup

### Langkah 1: Klon Repositori
```bash
git clone https://github.com/sukurlive/smarthub-management.git
cd smarthub-management
```

### Langkah 2: Instalasi Dependensi
Instal dependensi Composer (PHP) dan NPM (JavaScript):
```bash
# Instal dependensi backend
composer install

# Instal dependensi frontend
npm install
```

### Langkah 3: Konfigurasi Environment `.env`
Salin file `.env.example` menjadi `.env` dan generates key aplikasi:
```bash
cp .env.example .env
php artisan key:generate
```
Hubungkan database ke serverless **Supabase PostgreSQL** dengan memperbarui variabel koneksi berikut di file `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=aws-0-ap-northeast-1.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.[project-reference]
DB_PASSWORD=[password-database-supabase]
DB_SSLMODE=require
```

### Langkah 4: Migrasi & Seeding Database
Jalankan migrasi tabel beserta pengisian data awal dummy (Admin, Member, & Equipment):
```bash
php artisan migrate
php artisan db:seed
```

### Langkah 5: Jalankan Aplikasi secara Lokal
Jalankan development server Laravel dan kompilasi Vite secara paralel:
```bash
# Jalankan Vite dev server
npm run dev

# Jalankan server lokal Laravel
php artisan serve
```
Buka peramban browser Anda ke alamat `http://127.0.0.1:8000`.

---

## 📊 API Documentation

### Base URL
```bash
http://localhost:8000/api
```

### Autentikasi API
Seluruh endpoint API (kecuali login) memerlukan bearer token yang didapatkan dari respons login:
```bash
Authorization: Bearer {your_access_token}
Accept: application/json
```

### Daftar Endpoint API

#### 1. Endpoint Autentikasi
| Method | Endpoint | Deskripsi | Status |
|--------|----------|-----------|--------|
| `POST` | `/api/login` | Login user untuk mendapat token Sanctum | Public |
| `POST` | `/api/logout` | Logout user dan menghapus token | Token Required |
| `GET`  | `/api/me` | Mengambil data user yang sedang login | Token Required |

#### 2. Endpoint Equipment (Inventaris)
| Method | Endpoint | Deskripsi | Akses |
|--------|----------|-----------|-------|
| `GET`  | `/api/equipment` | Mengambil daftar semua equipment | Member & Admin |
| `GET`  | `/api/equipment/available` | Mengambil daftar alat berstatus `available` | Member & Admin |
| `GET`  | `/api/equipment/{id}` | Mengambil detail alat berdasarkan ID | Member & Admin |
| `POST` | `/api/equipment` | Menambahkan data alat baru | Admin Only |
| `PUT`  | `/api/equipment/{id}` | Mengubah data alat | Admin Only |
| `DELETE`| `/api/equipment/{id}` | Menghapus alat dari inventaris | Admin Only |

#### 3. Endpoint Transaksi Peminjaman (Loans)
| Method | Endpoint | Deskripsi | Akses |
|--------|----------|-----------|-------|
| `GET`  | `/api/loans` | Mengambil semua transaksi peminjaman | Admin Only |
| `GET`  | `/api/my-loans` | Mengambil riwayat peminjaman user aktif | Member & Admin |
| `POST` | `/api/loans` | Membuat transaksi peminjaman baru | Member & Admin |
| `POST` | `/api/loans/{id}/checkin` | Mengembalikan peralatan (check-in) | Member & Admin |
