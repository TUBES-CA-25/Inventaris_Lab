# SOP Pengerjaan Project Menggunakan Git

## Struktur Branch

* **main** : Branch utama (hasil akhir / stabil)
* **ahsan** : Backend Developer
* **rifki** : Frontend Developer
* **farah** : Frontend Developer

## Aturan Utama

1. Tidak boleh mengerjakan atau commit langsung di branch `main`.
2. Setiap developer hanya bekerja di branch masing-masing.
3. Branch `main` hanya diisi hasil merge dari branch `ahsan`, `rifki`, dan `farah`.
4. Selalu update dari `main` sebelum mulai mengerjakan fitur.

## Pembagian Tugas

### Backend (ahsan)

* Controller
* Model
* Database
* Logic dan validasi

### Frontend (rifki & farah)

* View
* UI / UX
* CSS
* JavaScript

## Alur Kerja Harian (WAJIB)

### 1. Update Branch dari `main` (Sebelum Ngoding)

Backend:

```bash
git checkout ahsan
git fetch origin
git merge origin/main
```

Frontend:

```bash
git checkout rifki   # atau farah
git fetch origin
git merge origin/main
```

### 2. Proses Development

* Kerjakan fitur di branch masing-masing.
* Jangan mengedit file milik branch lain tanpa koordinasi.
* Lakukan commit kecil dengan pesan yang jelas.

Contoh commit:

```bash
git add .
git commit -m "feat: tambah fitur peminjaman"
git commit -m "ui: perbaikan tampilan halaman"
```

### 3. Push ke Branch Masing-Masing

```bash
git push origin ahsan
git push origin rifki
git push origin farah
```

## Proses Penggabungan ke `main`

### 1. Pastikan Branch Sudah Update dari `main`

```bash
git checkout ahsan   # atau rifki / farah
git fetch origin
git merge origin/main
```

### 2. Merge ke `main` (Dilakukan Bergantian)

```bash
git checkout main
git merge ahsan
git merge rifki
git merge farah
```

### 3. Push Branch `main`

```bash
git push origin main
```

## Aturan Konflik

* Konflik diselesaikan oleh pemilik file.
* Jangan asal memilih versi.
* Jika ragu, diskusikan terlebih dahulu.

## Larangan

* Commit langsung ke `main`.
* Push tanpa update dari `main`.
* Commit besar tanpa pesan jelas.
* Mengubah file milik developer lain tanpa izin.

## SOP Database (WAJIB DIIKUTI)

### Kesepakatan Database

* Port MySQL: **3306**
* Nama database: **inventori_db**
* Database dijalankan secara lokal (masing-masing developer)

### Prinsip Utama Database

1. Database **tidak di-push ke GitHub**.
2. Yang disinkronkan adalah **struktur database (schema)**, bukan isi data.
3. Semua perubahan database harus dicatat dalam file SQL.
4. Backend (**ahsan**) bertanggung jawab atas perubahan database.

### Folder Database (WAJIB ADA DI REPO)

```
(inventori_lab.sql berada di root repository)
```

database/
├── ```

### Aturan Perubahan Database

* Tambah tabel → **tidak perlu hapus database**
* Tambah kolom → **tidak perlu hapus database**
* Ubah tipe kolom → **tidak perlu hapus database**
* Hapus tabel / kolom → **harus diskusi tim**
* Perubahan besar (reset struktur) → **boleh drop database dengan kesepakatan**

### Cara Sinkron Database

Jika ada update database:

1. Pull repository terbaru
2. Import file `inventori_lab.sql` melalui phpMyAdmin
3. Jika disepakati reset total:

```sql
DROP DATABASE IF EXISTS inventori_db;
CREATE DATABASE inventori_db;
USE inventori_db;
```

### File Database Utama

File database utama yang digunakan adalah **satu file saja** dan berada di root repository:

```
inventori_lab.sql
```

File ini menjadi **satu-satunya sumber database** untuk project.

```sqlsql
-- RESET DATABASE (hanya jika disepakati)
DROP DATABASE IF EXISTS inventori_db;
CREATE DATABASE inventori_db;
USE inventori_db;

-- ======================
-- TABEL USERS
-- ======================
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin','user') DEFAULT 'user'
);

-- ======================
-- TABEL TRANSAKSI PEMINJAMAN
-- ======================
CREATE TABLE trx_peminjaman (
    id_trx INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    tanggal_pinjam DATE,
    status ENUM('dipinjam','dikembalikan') DEFAULT 'dipinjam',
    FOREIGN KEY (id_user) REFERENCES users(id_user)
);

-- ======================
-- DATA ADMIN (LOGIN)
-- ======================
-- Password sudah di-hash (bcrypt)
INSERT INTO users (nama, email, password, role) VALUES
('Julisa', 'julisa@gmail.com', '$2y$10$8QX5p3ZqQZJ4fM1GkZyJHu4QJZpZpQ9z7xZ6eZQ4h5xC6WJ3JvQpW', 'admin'),
('Dewi Ernita Rahma', 'dewiernitarahma@gmail.com', '$2y$10$9FJZpKZl1p1ZK3XQvGzYHu7Fh1kQ9R8yZJkQ2kJpJ5VZ6XGZ1mZ9G', 'admin');
```

Catatan:

* Login menggunakan **email + password**
* Password di atas adalah hasil `password_hash()` PHP
* Verifikasi login wajib menggunakan `password_verify()`

### Akun Admin Sementara (UNTUK DEVELOPMENT)

Untuk keperluan testing dan development, sementara dapat login sebagai **admin** menggunakan akun berikut:

* Email: `julisa@gmail.com`
  Password: `julisa123`

* Email: `dewiernitarahma@gmail.com`
  Password: `Dewicomel28`

Catatan:

* Akun ini **hanya untuk development**, bukan production
* Password **boleh diganti** setelah fitur login stabil()`

Aturan Penting

* Jangan mengubah database langsung tanpa update `- Jangan export database penuh dari phpMyAdmin lalu push ke repo.
* Jangan drop database tanpa kesepakatan tim.

## Ringkasan Alur

Pull dari `main` → kerja di branch masing-masing → commit → push → merge ke `main` → sinkron database bila ada perubahan.
