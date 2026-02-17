# Inventaris Lab

**Inventaris Lab** adalah aplikasi berbasis web untuk manajemen peminjaman dan inventarisasi aset laboratorium. Aplikasi ini memudahkan pengelolaan barang, peminjaman oleh mahasiswa/dosen, serta validasi dan pelaporan oleh kepala laboratorium dan laboran.

---

## 🌟 Fitur Utama

*   **Manajemen Aset**: Pencatatan barang masuk, kondisi barang (baik/rusak), dan lokasi penyimpanan.
*   **Peminjaman & Pengembalian**: Alur peminjaman yang terstruktur mulai dari pengajuan, validasi, hingga pengembalian.
*   **Validasi Berjenjang**: Persetujuan peminjaman oleh Laboran dan Kepala Laboratorium.
*   **Notifikasi Otomatis**: Integrasi WhatsApp (Fonnte) dan Email (PHPMailer) untuk notifikasi status peminjaman.
*   **Laporan & Cetak**: Cetak bukti peminjaman (PDF) dan laporan inventaris (Excel/PDF).
*   **Log Aktivitas**: Rekam jejak aktivitas pengguna untuk keamanan dan audit.
*   **Role-Based Access Control**: Hak akses yang berbeda untuk setiap role (Admin/Kepala Lab, Laboran, Dosen, Mahasiswa).

---

## 🛠️ Teknologi yang Digunakan

*   **Bahasa Pemrograman**: PHP Native (Konsep MVC)
*   **Frontend**:
    *   HTML5, CSS3, JavaScript
    *   Bootstrap 4 & Tailwind CSS
    *   jQuery & DataTables
*   **Database**: MySQL
*   **Library Pendukung**:
    *   `dompdf/dompdf`: Untuk export PDF.
    *   `phpoffice/phpword`: Untuk manipulasi dokumen Word.
    *   `phpmailer/phpmailer`: Untuk pengiriman email.

---

## � Struktur Project

Berikut adalah struktur direktori utama aplikasi:

```
Inventaris_Lab1/
├── app/                    # Core Logic Aplikasi (MVC)
│   ├── config/             # Konfigurasi Database & Konstanta
│   ├── controllers/        # Controller (Menangani Request)
│   ├── core/               # Core System (App, Controller, Database Wrapper)
│   ├── models/             # Model (Interaksi ke Database)
│   ├── services/           # Service Tambahan (Cron Job, Scheduler)
│   └── views/              # View (Tampilan Antarmuka)
├── public/                 # File Publik yang Dapat Diakses Langsung
│   ├── css/                # Stylesheet
│   ├── img/                # Gambar & Aset Statis
│   ├── js/                 # JavaScript Client-side
│   ├── uploads/            # File Upload User (Foto, Dokumen)
│   └── index.php           # Entry Point Aplikasi
├── vendor/                 # Library Composer
├── inventori_db12.sql      # Database Schema
├── cron_job.php            # Script Cron Job
└── README.md               # Dokumentasi Project
```

---

## �💾 Struktur Database

Aplikasi ini menggunakan database `inventori_db12` dengan beberapa tabel utama:

### 1. **Pengguna & Autentikasi**
*   **`users`**: Menyimpan akun login (email, password hash, role).
*   **`trx_data_user`**: Menyimpan profil detail pengguna (NIM/NIP, No HP, Alamat, Foto).
*   **`mst_role`**: Daftar hak akses (Kepala Lab, Laboran, Mahasiswa, dll).

### 2. **Master Data Barang**
*   **`mst_jenis_barang`**: Kategori barang (e.g., Laptop, Kamera).
*   **`mst_merek_barang`**: Merek barang (e.g., ASUS, Lenovo).
*   **`mst_spesifikasi`**: Detail spesifikasi barang yang bersifat umum (Master Spesifikasi).
*   **`trx_barang`**: Unit fisik barang (Unique ID per unit/QR Code) yang mengacu pada spesifikasi.

### 3. **Transaksi**
*   **`trx_peminjaman`**: Header transaksi peminjaman (Tanggal pinjam, status, validasi).
*   **`trx_detail_peminjaman`**: Detail barang yang dipinjam dalam satu transaksi.
*   **`trx_pengembalian`**: Data pengembalian barang.
*   **`trx_detail_pengembalian`**: Mencatat kondisi barang saat dikembalikan (Baik/Rusak).

---

## 🚀 Panduan Instalasi

### Prasyarat
*   Web Server (Apache/XAMPP) dengan PHP >= 7.4.
*   MySQL Database.
*   Composer (untuk manajemen dependensi).

### Langkah Instalasi
1.  **Clone Repository**
    ```bash
    git clone https://github.com/username/Inventaris_Lab1.git
    cd Inventaris_Lab1
    ```

2.  **Install Dependensi**
    Jalankan perintah berikut di terminal root project:
    ```bash
    composer install
    ```

3.  **Setup Database**
    *   Buat database baru bernama `inventori_db12` di phpMyAdmin.
    *   Import file `inventori_db12.sql` yang ada di root direktori project.

4.  **Konfigurasi Aplikasi**
    *   Buka file `app/config/config.php`.
    *   Sesuaikan `BASEURL` dengan URL lokal Anda.
    *   Sesuaikan konfigurasi database (`DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`).
    *   (Opsional) Atur konfigurasi Email dan WhatsApp Gateway jika diperlukan.

---

## 📖 Panduan Penggunaan

### 1. Login
Masuk menggunakan akun yang sudah terdaftar. Berikut adalah akun default untuk administrator:

| Role | Email | Password (Default) |
| :--- | :--- | :--- |
| **Kepala Lab** | `keplab@gmail.com` | *Tanya Admin* |
| **Laboran** | `laboran@gmail.com` | *Tanya Admin* |
| **Koordinator Lab** | `korlab@gmail.com` | *Tanya Admin* |

*(Catatan: Password di-hash menggunakan bcrypt. Untuk reset, gunakan fitur lupa password atau hubungi administrator database)*

### 2. Alur Peminjaman (Mahasiswa)
1.  Login sebagai Mahasiswa.
2.  Masuk ke menu **Peminjaman**.
3.  Pilih barang yang tersedia, masukkan ke keranjang peminjaman.
4.  Isi formulir peminjaman (Tanggal pinjam, Tanggal kembali, Keperluan).
5.  Upload **Surat Permohonan** (PDF).
6.  Klik **Ajukan Peminjaman**. Status akan menjadi `Diproses`.

### 3. Alur Validasi (Admin/Laboran)
1.  Login sebagai **Laboran** atau **Kepala Lab**.
2.  Masuk ke menu **Validasi Peminjaman**.
3.  Lihat detail pengajuan.
4.  Klik **Setujui** jika syarat lengkap, atau **Tolak** jika tidak sesuai.
5.  Jika disetujui, Mahasiswa akan mendapat notifikasi dan bisa mengambil barang.

### 4. Alur Pengembalian
1.  Saat barang dikembalikan, Laboran masuk ke menu **Pengembalian**.
2.  Cari transaksi peminjaman terkait.
3.  Cek kondisi fisik barang satu per satu.
4.  Update status barang di sistem (Baik/Rusak).
5.  Selesaikan transaksi pengembalian.

---

## 🤝 SOP Development & Kontribusi

Berikut adalah aturan pengerjaan project (Diambil dari SOP Internal Tim):

### Struktur Branch
*   **main**: Branch utama (Production/Stabil).
*   **ahsan**: Backend Developer.
*   **rifki/farah**: Frontend Developer.

### Workflow
1.  **Selalu Update**: Lakukan `git pull origin main` sebelum mulai bekerja.
2.  **Kerja di Branch Sendiri**: Jangan commit langsung ke `main`.
    ```bash
    git checkout ahsan  # Ganti dengan nama branch Anda
    git merge main      # Sinkronisasi dengan main terbaru
    ```
3.  **Commit Pesan Jelas**: Gunakan format `feat: ...` atau `fix: ...`.
4.  **Push & Merge**: Push ke branch masing-masing, lalu merge ke `main` secara bergantian.

### Aturan Database
1.  **Jangan Push Database**: File database `.sql` di-ignore atau dikelola manual.
2.  **Update Struktur**: Jika mengubah struktur tabel, update file `inventori_db12.sql` di root folder.
3.  **Sinkronisasi**: Beritahu tim jika ada perubahan struktur database agar mereka bisa import ulang.

---

**Inventaris Lab Team &copy; 2026**
