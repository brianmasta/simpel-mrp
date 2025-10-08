# 🏛️ SIMPEL-MRP
**Sistem Informasi Pelayanan Majelis Rakyat Papua (SIMPEL-MRP)**  
Aplikasi berbasis web untuk mendukung pelayanan administrasi dan pengaduan masyarakat secara digital di lingkungan **Majelis Rakyat Papua Provinsi Papua Tengah**.

---

## 📌 Deskripsi
SIMPEL-MRP dikembangkan untuk meningkatkan kualitas pelayanan publik melalui sistem informasi terpadu yang mencakup:
- Pengajuan surat keterangan Orang Asli Papua (OAP)
- Pengajuan dan verifikasi marga
- Pengelolaan pengaduan masyarakat
- Manajemen arsip digital
- Dashboard statistik pelayanan
- Manajemen pengguna berbasis **role (Admin & Petugas)**
- Integrasi **QR Code** dan **tanda tangan digital**
- Otomatisasi dokumen PDF dengan format surat resmi MRP

Aplikasi ini dibangun menggunakan **Laravel 12**, **Livewire 3**, **Tailwind CSS**, dan **MySQL** sebagai basis data utama.

---

## ⚙️ Teknologi yang Digunakan
| Komponen | Teknologi |
|-----------|------------|
| Backend Framework | Laravel 12 |
| Frontend Interaktif | Livewire 3 |
| Desain & Layout | Tailwind CSS |
| Database | MySQL / MariaDB |
| Library PDF | Barryvdh DomPDF |
| QR Code Generator | Simple QrCode |
| Authentication | Laravel Breeze (Multi-role) |
| Editor | Quill.js / Trix Editor |
| Server | Apache / Nginx |
| PHP Version | ≥ 8.2 |

---

## 🧩 Fitur Utama
### 🧾 1. Pengajuan Surat OAP
- Form pengajuan online dengan validasi otomatis.
- Integrasi API **NIK Parser** untuk pengisian data otomatis.
- Cetak PDF surat keterangan OAP lengkap dengan **QR Code dan tanda tangan digital.**

### 👨‍👩‍👧 2. Pengajuan Marga
- Pencarian marga berdasarkan wilayah adat.
- Sistem persetujuan marga oleh admin.
- Notifikasi hasil pengajuan.

### 📂 3. Manajemen Arsip Digital
- Upload dokumen digital (PDF, JPG, DOCX).
- Klasifikasi dan pencarian arsip berdasarkan jenis surat.

### 🗳️ 4. Pengaduan Masyarakat
- Form pengaduan online.
- Proses tindak lanjut dan status pelaporan.

### 📊 5. Dashboard & Statistik
- Grafik jumlah pengajuan surat, pengaduan, dan arsip.
- Laporan kegiatan pelayanan publik.

### 🔐 6. Autentikasi & Role Management
- Role: **Admin, Petugas, dan Masyarakat.**
- Dashboard dan akses data sesuai hak pengguna.

---

## 🧰 Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/username/simpel-mrp.git
cd simpel-mrp
