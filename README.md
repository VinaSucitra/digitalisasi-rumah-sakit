<<<<<<< HEAD
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======
# 🏥 Digitalisasi Rumah Sakit  
**Tugas Final Praktikum Pemrograman Web 2025**

---

## 📄 Deskripsi Proyek
Sistem **Digitalisasi Rumah Sakit** adalah aplikasi berbasis web yang dirancang untuk membantu pengelolaan operasional rumah sakit secara digital.  
Proyek ini dibuat sebagai tugas akhir praktikum pemrograman web dan mengimplementasikan konsep **Laravel Framework**, **CRUD**, **multi-role authentication**, dan **database relasional**.

Aplikasi ini memungkinkan **admin, dokter, dan pasien** untuk berinteraksi dalam satu platform terintegrasi, dengan fitur utama seperti manajemen poli, jadwal dokter, janji temu online, rekam medis, dan pengelolaan obat.

---

## 👤 Role Pengguna

| Role | Deskripsi |
|------|------------|
| **Admin** | Mengelola data pengguna, poli, obat, dan memverifikasi janji temu. |
| **Dokter** | Menentukan jadwal praktek, memvalidasi janji temu, dan membuat rekam medis pasien. |
| **Pasien** | Mendaftar akun, membuat janji temu online, dan melihat riwayat pemeriksaan serta resep. |
| **Guest (Publik)** | Dapat melihat informasi umum seperti daftar poli dan jadwal dokter tanpa login. |

---

## ⚙️ Fitur Utama

### 🧑‍💼 Admin
- CRUD data pengguna (Admin, Dokter, Pasien)
- CRUD data Poli (nama, deskripsi)
- CRUD data Obat (nama, tipe, stok)
- Lihat & verifikasi semua janji temu
- Dashboard berisi ringkasan aktivitas rumah sakit

### 🩺 Dokter
- CRUD jadwal praktik  
- Melihat daftar janji temu pasien  
- Menyetujui/menolak janji temu  
- Membuat rekam medis pasien dan resep obat  
- Melihat riwayat pasien yang pernah diperiksa  

### 👨‍🦱 Pasien
- Registrasi akun pasien  
- Melihat daftar poli & dokter yang tersedia  
- Membuat janji temu berdasarkan jadwal dokter  
- Melihat status janji temu (Pending, Approved, Rejected)  
- Melihat riwayat konsultasi dan hasil pemeriksaan  

### 👀 Guest
- Melihat daftar poli dan dokter  
- Login/Register untuk mengakses fitur lebih lanjut  

---

## 🗄️ Struktur Database (Ringkasan)

| Tabel | Deskripsi |
|-------|------------|
| `users` | Data semua pengguna (admin, dokter, pasien) |
| `polis` | Data poli rumah sakit |
| `schedules` | Jadwal praktik dokter |
| `appointments` | Data janji temu pasien dan dokter |
| `medical_records` | Rekam medis pasien |
| `medicines` | Data obat |
| `prescriptions` | Relasi resep dengan obat dan rekam medis |

---
>>>>>>> 967aadea67e490e6e2ad648bab14e39e75089019
