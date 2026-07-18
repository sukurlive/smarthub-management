## 📘 Smart-Hub Management System - README

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-13-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue?style=flat-square&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange?style=flat-square&logo=mysql)
![Git](https://img.shields.io/badge/Git-F05032?style=flat-square&logo=git&logoColor=white)

**Sistem Manajemen Peminjaman Ruang Kerja dan Peralatan Studio untuk Komunitas Kreatif**

[Fitur](#-fitur-utama) • [Instalasi](#-instalasi) • [API Documentation](#-api-documentation) • [Testing](#-testing-dengan-postman) • [Contributing](#-contributing)

</div>

---

## Tentang Aplikasi

**Smart-Hub Management System** adalah aplikasi fullstack yang dibangun untuk membantu komunitas kreatif lokal dalam mengelola peminjaman ruang kerja dan peralatan studio secara mandiri. Aplikasi ini melayani dua jenis pengguna:

- **Admin** → Mengelola data inventaris dan peminjaman melalui dashboard web
- **Member** → Melakukan check-in/check-out peralatan melalui aplikasi tablet menggunakan REST API

---

## Fitur Utama

### Admin (Web Dashboard)
- Dashboard statistik real-time
- CRUD inventaris peralatan studio
- Monitoring peminjaman equipment
- Manajemen data member

### Member (API / Tablet)
- Autentikasi menggunakan token (Laravel Sanctum)
- Lihat daftar equipment yang tersedia
- Pinjam equipment
- Check-in equipment
- Riwayat peminjaman pribadi

### Fitur Tambahan
- Notifikasi email konfirmasi check-in equipment (branch terpisah)

---

## Teknologi yang Digunakan

| Kategori | Teknologi | Versi |
|----------|-----------|-------|
| Backend Framework | Laravel | 13 |
| Frontend | Blade + Bootstrap | 5.3 |
| Database | MySQL | 5.7+ |
| API Authentication | Laravel Sanctum | 4.x |
| Email | Laravel Mail | - |
| Version Control | Git | - |
| Asset Bundling | Vite | - |

---

## Kebutuhan Sistem

```bash
PHP >= 8.2
Composer >= 2.0
MySQL >= 5.7
Node.js >= 18.0
NPM >= 9.0
Git >= 2.0
```

## Instalasi

### Langkah 1: Clone Repository
```bash
git clone https://github.com/sukurlive/smarthub-management.git
cd smart-hub-management
```

### Langkah 2: Install Dependencies
```bash
composer install
npm install
npm run dev && npm run build
```
```bash
composer require laravel/sanctum
composer require laravel/ui
```

### Langkah 3: Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```

```bash
# Setup Supabase di .env
# Edit DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
```

### Langkah 4: Setup Database
```bash
php artisan migrate
php artisan db:seed
```

### Langkah 5: Jalankan Aplikasi
```bash
php artisan serve
```
## API Documentation

### Base URL
```bash
http://localhost:8000/api
```

### Authentication

Semua endpoint (kecuali login) memerlukan Bearer Token:
```bash
Authorization: Bearer {your_access_token}
Content-Type: application/json
```

### Endpoint List

Auth Endpoints
```bash
Method	  Endpoint	  Deskripsi	    Auth
POST      /login	    Login	        Public
POST	    /logout	    Logout	      Required
GET	      /me	        Data user	    Required
```

Contoh Request Login:

```bash
POST /api/login
Content-Type: application/json
```bash
{
    "email": "member@example.com",
    "password": "password"
}
```

Response:

```bash
{
    "access_token": "1|abc123def456...",
    "token_type": "Bearer",
    "user": {
        "id": 2,
        "name": "Member User",
        "email": "member@example.com",
        "role": "member"
    }
}
```

Equipment Endpoints:

```bash
Method	 Endpoint				        Deskripsi				      Auth
GET		   /equipment				      Semua equipment			  Required
GET		   /equipment/available	  Equipment tersedia	  Required
GET		   /equipment/{id}			  Detail equipment		  Required
POST	   /equipment				      Tambah equipment		  Admin
PUT		   /equipment/{id}			  Update equipment		  Admin
DELETE	 /equipment/{id}			  Hapus equipment			  Admin
```
Loan    Endpoints:

```bash
Method	Endpoint				         Deskripsi	          Auth
GET		   /loans					         Semua peminjaman		  Required
GET		   /my-loans				       Peminjaman sendiri		Required
POST	   /loans					         Pinjam equipment		  Required
POST	   /loans/{id}/checkin		 Kembalikan equipment	Required
```

Contoh Request Pinjam Equipment:

POST /api/loans

```bash
Authorization: Bearer {token}
Content-Type: application/json
```

```bash
{
    "equipment_id": 1,
    "loan_date": "2026-05-16"
}
```

Response:

```bash
{
    "success": true,
    "message": "Equipment borrowed successfully",
    "data": {
        "id": 1,
        "user_id": 2,
        "equipment_id": 1,
        "loan_date": "2026-05-16",
        "status": "checked_out"
    }
}
```

Response Status Codes

```bash
Code			Description
200				Success
201				Created
400				Bad Request
401				Unauthorized
403				Forbidden
404				Not Found
500				Server Error
```

Testing dengan Postman


Buat file Smart-Hub-API.postman_collection.json:

```bash
{
  "info": {
    "name": "Smart Hub Management API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "1. Authentication",
      "item": [
        {
          "name": "Login Member",
          "request": {
            "method": "POST",
            "url": "http://localhost:8000/api/login",
            "header": [{"key": "Content-Type", "value": "application/json"}],
            "body": {
              "mode": "raw",
              "raw": "{\"email\":\"member@example.com\",\"password\":\"password\"}"
            }
          }
        },
        {
          "name": "Login Admin",
          "request": {
            "method": "POST",
            "url": "http://localhost:8000/api/login",
            "header": [{"key": "Content-Type", "value": "application/json"}],
            "body": {
              "mode": "raw",
              "raw": "{\"email\":\"admin@example.com\",\"password\":\"password\"}"
            }
          }
        },
        {
          "name": "Logout",
          "request": {
            "method": "POST",
            "url": "http://localhost:8000/api/logout",
            "header": [{"key": "Authorization", "value": "Bearer {{token}}"}]
          }
        }
      ]
    },
    {
      "name": "2. Equipment",
      "item": [
        {
          "name": "Get All Equipment",
          "request": {
            "method": "GET",
            "url": "http://localhost:8000/api/equipment",
            "header": [{"key": "Authorization", "value": "Bearer {{token}}"}]
          }
        },
        {
          "name": "Get Available Equipment",
          "request": {
            "method": "GET",
            "url": "http://localhost:8000/api/equipment/available",
            "header": [{"key": "Authorization", "value": "Bearer {{token}}"}]
          }
        },
        {
          "name": "Create Equipment (Admin)",
          "request": {
            "method": "POST",
            "url": "http://localhost:8000/api/equipment",
            "header": [
              {"key": "Authorization", "value": "Bearer {{token}}"},
              {"key": "Content-Type", "value": "application/json"}
            ],
            "body": {
              "mode": "raw",
              "raw": "{\"name\":\"New Equipment\",\"description\":\"Test\",\"status\":\"available\"}"
            }
          }
        }
      ]
    },
    {
      "name": "3. Loans",
      "item": [
        {
          "name": "Get All Loans",
          "request": {
            "method": "GET",
            "url": "http://localhost:8000/api/loans",
            "header": [{"key": "Authorization", "value": "Bearer {{token}}"}]
          }
        },
        {
          "name": "Get My Loans",
          "request": {
            "method": "GET",
            "url": "http://localhost:8000/api/my-loans",
            "header": [{"key": "Authorization", "value": "Bearer {{token}}"}]
          }
        },
        {
          "name": "Borrow Equipment",
          "request": {
            "method": "POST",
            "url": "http://localhost:8000/api/loans",
            "header": [
              {"key": "Authorization", "value": "Bearer {{token}}"},
              {"key": "Content-Type", "value": "application/json"}
            ],
            "body": {
              "mode": "raw",
              "raw": "{\"equipment_id\":1,\"loan_date\":\"2026-05-16\"}"
            }
          }
        },
        {
          "name": "Checkin Equipment",
          "request": {
            "method": "POST",
            "url": "http://localhost:8000/api/loans/1/checkin",
            "header": [{"key": "Authorization", "value": "Bearer {{token}}"}]
          }
        }
      ]
    }
  ],
  "variable": [
    {
      "key": "token",
      "value": ""
    }
  ]
}
```

Testing Checklist

- Login Member → Dapat token
- Get All Equipment → Status 200
- Get Available Equipment → Hanya available
- Borrow Equipment → Status borrowed
- Get My Loans → Menampilkan peminjaman
- Checkin Equipment → Status checked_in
- Login Admin → Dapat token admin
- Create Equipment (Admin) → Equipment baru
- Logout → Token tidak valid
