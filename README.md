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
