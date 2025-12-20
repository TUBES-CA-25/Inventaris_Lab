-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Des 2025 pada 08.21
-- Versi server: 10.4.32-MariaDB-log
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventori_db`
--

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `detail_barang`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `detail_barang` (
`id_barang` int(11)
,`foto_barang` text
,`sub_barang` varchar(50)
,`nama_merek_barang` varchar(50)
,`kondisi_barang` varchar(30)
,`jumlah_barang` int(3)
,`nama_satuan` varchar(30)
,`deskripsi_barang` text
,`tgl_pengadaan_barang` date
,`kode_barang` varchar(26)
,`keterangan_label` enum('Sudah','Belum')
,`nama_lokasi_penyimpanan` varchar(50)
,`deskripsi_detail_lokasi` text
,`status` varchar(30)
,`status_peminjaman` enum('Bisa','Tidak Bisa')
,`qr_code` text
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mst_jenis_barang`
--

CREATE TABLE `mst_jenis_barang` (
  `id_jenis_barang` int(11) NOT NULL,
  `sub_barang` varchar(50) DEFAULT NULL,
  `grup_sub` char(1) DEFAULT NULL,
  `kode_sub` varchar(3) DEFAULT NULL,
  `kode_jenis_barang` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mst_jenis_barang`
--

INSERT INTO `mst_jenis_barang` (`id_jenis_barang`, `sub_barang`, `grup_sub`, `kode_sub`, `kode_jenis_barang`) VALUES
(1, 'monitor', 'C', 'MOZ', 'C/MOZ'),
(2, 'keyboard', 'C', 'KEZ', 'C/KEZ'),
(4, 'laptop', 'C', 'LAP', 'C/LAP'),
(8, 'Pads', 'C', 'asa', 'C/asa');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mst_kondisi_barang`
--

CREATE TABLE `mst_kondisi_barang` (
  `id_kondisi_barang` int(11) NOT NULL,
  `kondisi_barang` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mst_kondisi_barang`
--

INSERT INTO `mst_kondisi_barang` (`id_kondisi_barang`, `kondisi_barang`) VALUES
(1, 'Baik'),
(2, 'Rusak - dapat diperbaiki '),
(3, 'Rusak - sedang diperbaiki'),
(4, 'Rusak total'),
(5, 'Sudah terpakai');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mst_lokasi_penyimpanan`
--

CREATE TABLE `mst_lokasi_penyimpanan` (
  `id_lokasi_penyimpanan` int(11) NOT NULL,
  `nama_lokasi_penyimpanan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mst_lokasi_penyimpanan`
--

INSERT INTO `mst_lokasi_penyimpanan` (`id_lokasi_penyimpanan`, `nama_lokasi_penyimpanan`) VALUES
(1, 'Lab Iot'),
(2, 'Lab StartUp'),
(3, 'Lab Neetworking'),
(4, 'Lab Multimedia'),
(5, 'Lab Computer Vision'),
(6, 'Lab Data Since'),
(7, 'Lab Micro Controller'),
(8, 'Rg PC I'),
(9, 'Rg PC II'),
(10, 'Rg Server'),
(11, 'Gudang'),
(12, 'Rg Laboran'),
(13, 'Rg Asisten Lab'),
(14, 'Rg Riset I'),
(15, 'Rg Riset II'),
(16, 'Rg Riset III'),
(17, 'Rg Kepala Lab I'),
(18, 'Rg Kepala Lab II');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mst_merek_barang`
--

CREATE TABLE `mst_merek_barang` (
  `id_merek_barang` int(11) NOT NULL,
  `nama_merek_barang` varchar(50) DEFAULT NULL,
  `kode_merek_barang` char(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mst_merek_barang`
--

INSERT INTO `mst_merek_barang` (`id_merek_barang`, `nama_merek_barang`, `kode_merek_barang`) VALUES
(2, 'fantech', '002'),
(5, 'hp', '004'),
(6, 'NoBrand', '001'),
(7, 'Logitech', '003'),
(8, 'Samsung', '005'),
(9, 'Intel', '006'),
(15, 'IPON', '101');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mst_role`
--

CREATE TABLE `mst_role` (
  `id_role` int(11) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mst_role`
--

INSERT INTO `mst_role` (`id_role`, `role`) VALUES
(1, 'KEPLAB'),
(2, 'LABORAN'),
(3, 'KORLAB'),
(4, 'ASISTEN'),
(5, 'CA'),
(6, 'CCA'),
(7, 'MHS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mst_satuan`
--

CREATE TABLE `mst_satuan` (
  `id_satuan` int(11) NOT NULL,
  `nama_satuan` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mst_satuan`
--

INSERT INTO `mst_satuan` (`id_satuan`, `nama_satuan`) VALUES
(1, 'Buah'),
(2, 'Lusin'),
(3, 'Dus'),
(4, 'Rangkaian'),
(5, 'Kotak'),
(6, 'Pack'),
(7, 'Box'),
(8, 'Roll'),
(9, 'Pasang');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mst_status`
--

CREATE TABLE `mst_status` (
  `id_status` int(11) NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mst_status`
--

INSERT INTO `mst_status` (`id_status`, `status`) VALUES
(1, 'Dipinjam'),
(2, 'Dipindahkan'),
(3, 'Stay'),
(4, 'Rusak'),
(5, 'Bagus'),
(6, 'Baru diganti');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mst_template_surat`
--

CREATE TABLE `mst_template_surat` (
  `id_template` int(11) NOT NULL,
  `nama_template` varchar(100) NOT NULL,
  `jenis_surat` enum('Peminjaman','Pengembalian','Bebas Lab') DEFAULT 'Peminjaman',
  `file_template` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `trx_barang`
--

CREATE TABLE `trx_barang` (
  `id_barang` int(11) NOT NULL,
  `foto_barang` text NOT NULL,
  `id_jenis_barang` int(11) DEFAULT NULL,
  `id_merek_barang` int(11) DEFAULT NULL,
  `id_kondisi_barang` int(11) DEFAULT NULL,
  `jumlah_barang` int(3) NOT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `deskripsi_barang` text DEFAULT NULL,
  `tgl_pengadaan_barang` date NOT NULL,
  `keterangan_label` enum('Sudah','Belum') NOT NULL,
  `id_lokasi_penyimpanan` int(11) DEFAULT NULL,
  `deskripsi_detail_lokasi` text DEFAULT NULL,
  `id_status` int(11) DEFAULT NULL,
  `status_peminjaman` enum('Bisa','Tidak Bisa') NOT NULL,
  `kode_barang` varchar(26) NOT NULL,
  `qr_code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `trx_barang`
--

INSERT INTO `trx_barang` (`id_barang`, `foto_barang`, `id_jenis_barang`, `id_merek_barang`, `id_kondisi_barang`, `jumlah_barang`, `id_satuan`, `deskripsi_barang`, `tgl_pengadaan_barang`, `keterangan_label`, `id_lokasi_penyimpanan`, `deskripsi_detail_lokasi`, `id_status`, `status_peminjaman`, `kode_barang`, `qr_code`) VALUES
(80, '../public/img/foto-barang/MacBook Pro 16 (1).png', 1, 5, 5, 1, 1, '', '2024-06-11', 'Sudah', 5, 'meja 7', 5, 'Bisa', '2024/VI/C/MON/004/1/10', '../public/img/qr-code/code_666a5b9421738.png'),
(81, '../public/img/foto-barang/logho.jpg', 2, 2, 1, 0, 7, '', '2025-02-11', 'Sudah', 6, '', 5, 'Bisa', '2025/II/C/MON/004/6/7', '../public/img/qr-code/code_67a9d456934ef.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trx_data_user`
--

CREATE TABLE `trx_data_user` (
  `id_data_user` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `foto` text DEFAULT NULL,
  `nama_user` varchar(100) NOT NULL,
  `no_hp_user` varchar(15) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `alamat` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `trx_data_user`
--

INSERT INTO `trx_data_user` (`id_data_user`, `id_user`, `foto`, `nama_user`, `no_hp_user`, `jenis_kelamin`, `alamat`) VALUES
(5, 6, '../public/img/foto-profile/user.svg', 'Furqon Fatahillah', '085240153953', 'Laki-laki', 'Borong raya'),
(11, 12, '../public/img/foto-profile/WhatsApp Image 2024-02-02 at 19.05.56_a1d84076.jpg', 'Nurul Azmi', '082292704208', 'Perempuan', 'pampang'),
(21, 22, '../public/img/foto-profile/Vectto.jpeg', 'akbar', '0834326473434', 'Laki-laki', 'makassar'),
(25, 26, '../public/img/foto-profile/f.jpg', 'Dewi Ernita Rahma', '085216090040', 'Perempuan', 'Jl. Kakaktua II'),
(26, 27, '../public/img/foto-profile/Picture1 biru.png', 'Julisa', '085216090048', 'Perempuan', 'Pampang'),
(27, 28, '../public/img/foto-profile/', 'Ahsan', '09090909090', 'Laki-laki', 'masalae'),
(28, 29, '../public/img/foto-profile/', 'Andi Ahsan', '0912836728938', 'Laki-laki', 'nasakkkee');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trx_peminjaman`
--

CREATE TABLE `trx_peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `nama_peminjam` varchar(255) NOT NULL,
  `judul_kegiatan` varchar(255) NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `tanggal_peminjaman` date NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `id_jenis_barang` int(11) NOT NULL,
  `jumlah_peminjaman` int(11) NOT NULL,
  `keterangan_peminjaman` text DEFAULT NULL,
  `status` enum('diproses','disetujui','ditolak') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `trx_peminjaman`
--

INSERT INTO `trx_peminjaman` (`id_peminjaman`, `nama_peminjam`, `judul_kegiatan`, `tanggal_pengajuan`, `tanggal_peminjaman`, `tanggal_pengembalian`, `id_jenis_barang`, `jumlah_peminjaman`, `keterangan_peminjaman`, `status`) VALUES
(6, 'ega ', 'omoo', '2025-02-10', '2025-02-26', '2025-03-08', 2, 1, 'Dipinjam pak pulici', 'diproses'),
(7, 'Anggi', 'mudah mudah', '2025-02-11', '2025-02-12', '2025-02-19', 4, 2, 'hehe', 'diproses'),
(11, 'SpongeBob ', 'mudah mudah', '2025-02-15', '2025-02-21', '2025-02-26', 2, 4, 'hehe pinjam dulu yaa', 'diproses'),
(14, 'Saya', 'mudah mudah', '2025-02-16', '2025-02-20', '2025-02-27', 4, 2, 'hehe', 'disetujui'),
(15, 'Ahsan', 'TUBES MICRO', '2025-12-19', '2025-12-19', '2025-12-26', 8, 1, 'Blabla', 'disetujui'),
(16, 'ahsanos', 'mudah mudah', '2025-12-19', '2025-12-19', '2025-12-26', 1, 1, 'zasa', 'diproses');

--
-- Trigger `trx_peminjaman`
--
DELIMITER $$
CREATE TRIGGER `after_peminjaman_update` AFTER UPDATE ON `trx_peminjaman` FOR EACH ROW BEGIN
    -- Jika status berubah menjadi "disetujui", tambahkan ke trx_pengembalian
    IF NEW.status = 'disetujui' AND OLD.status <> 'disetujui' THEN
        INSERT INTO trx_pengembalian (id_peminjaman)
        VALUES (NEW.id_peminjaman);
    END IF;

    -- Jika status berubah dari "disetujui" ke selain "disetujui", hapus dari trx_pengembalian
    IF OLD.status = 'disetujui' AND NEW.status NOT IN ('disetujui') THEN
        DELETE FROM trx_pengembalian WHERE id_peminjaman = OLD.id_peminjaman;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `trx_pengembalian`
--

CREATE TABLE `trx_pengembalian` (
  `id_pengembalian` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `status_pengembalian` enum('Dikembalikan','Belum Dikembalikan','Rusak','Hilang') DEFAULT NULL,
  `keterangan` enum('Tepat Waktu','Tidak Tepat Waktu','Bermasalah') DEFAULT NULL,
  `detail_masalah` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `trx_pengembalian`
--

INSERT INTO `trx_pengembalian` (`id_pengembalian`, `id_peminjaman`, `status_pengembalian`, `keterangan`, `detail_masalah`) VALUES
(15, 15, NULL, NULL, NULL),
(16, 14, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `trx_user`
--

CREATE TABLE `trx_user` (
  `id_user` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `trx_user`
--

INSERT INTO `trx_user` (`id_user`, `email`, `password`, `id_role`) VALUES
(6, 'furqonfatahillah999@gmail.com', '$2y$10$Shs7Errud4hePyn4.Ke/Z.H6kTEPRw3wNVZVhKCvYIrBUhGHy1xxy', 3),
(12, 'nrl.azmi160103@gmail.com', '$2y$10$JENJHI1HEJ5xOdNTZDVUKOTBUFprh5nIDWC.OCKgWqoUGEFcc/8RG', 1),
(22, 'akbar@gmail.com', '$2y$10$dr0rox81DcM8tZzZwm.FWeOJUTpQ6puBX86cxJX4rfg4MAorflB6S', 1),
(26, 'dewiernitarahma@gmail.com', '$2y$10$HB.9TCSY1xOwi8hy0Eh.Cu8BHMKkv8tHdFfmvuIJfokaSs2y3FkL6', 7),
(27, 'julisa@gmail.com', '$2y$10$oxn/vy7HVG762.M/y4JTEu73nUrfrpSmy9X7aXBMJXTOepFQ1CEEC', 1),
(28, 'admin@gmail.com', '$2y$10$1vrpNVH6REUpkz/PxBMrquGrMMSEXYbobyta8DZUgYo/rPoXYUOFi', 7),
(29, 'ahsan@gmail.com', '$2y$10$T9Oek/rxszCN2i2XvcAnD.zYHrwjLan9HYLRZO2lv5DrNNPdVyxnm', 7);

-- --------------------------------------------------------

--
-- Struktur untuk view `detail_barang`
--
DROP TABLE IF EXISTS `detail_barang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detail_barang`  AS SELECT `trx_barang`.`id_barang` AS `id_barang`, `trx_barang`.`foto_barang` AS `foto_barang`, `mst_jenis_barang`.`sub_barang` AS `sub_barang`, `mst_merek_barang`.`nama_merek_barang` AS `nama_merek_barang`, `mst_kondisi_barang`.`kondisi_barang` AS `kondisi_barang`, `trx_barang`.`jumlah_barang` AS `jumlah_barang`, `mst_satuan`.`nama_satuan` AS `nama_satuan`, `trx_barang`.`deskripsi_barang` AS `deskripsi_barang`, `trx_barang`.`tgl_pengadaan_barang` AS `tgl_pengadaan_barang`, `trx_barang`.`kode_barang` AS `kode_barang`, `trx_barang`.`keterangan_label` AS `keterangan_label`, `mst_lokasi_penyimpanan`.`nama_lokasi_penyimpanan` AS `nama_lokasi_penyimpanan`, `trx_barang`.`deskripsi_detail_lokasi` AS `deskripsi_detail_lokasi`, `mst_status`.`status` AS `status`, `trx_barang`.`status_peminjaman` AS `status_peminjaman`, `trx_barang`.`qr_code` AS `qr_code` FROM ((((((`trx_barang` join `mst_jenis_barang` on(`trx_barang`.`id_jenis_barang` = `mst_jenis_barang`.`id_jenis_barang`)) join `mst_merek_barang` on(`trx_barang`.`id_merek_barang` = `mst_merek_barang`.`id_merek_barang`)) join `mst_satuan` on(`trx_barang`.`id_satuan` = `mst_satuan`.`id_satuan`)) join `mst_kondisi_barang` on(`trx_barang`.`id_kondisi_barang` = `mst_kondisi_barang`.`id_kondisi_barang`)) join `mst_lokasi_penyimpanan` on(`trx_barang`.`id_lokasi_penyimpanan` = `mst_lokasi_penyimpanan`.`id_lokasi_penyimpanan`)) join `mst_status` on(`trx_barang`.`id_status` = `mst_status`.`id_status`)) ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `mst_jenis_barang`
--
ALTER TABLE `mst_jenis_barang`
  ADD PRIMARY KEY (`id_jenis_barang`),
  ADD UNIQUE KEY `kode_sub` (`kode_sub`);

--
-- Indeks untuk tabel `mst_kondisi_barang`
--
ALTER TABLE `mst_kondisi_barang`
  ADD PRIMARY KEY (`id_kondisi_barang`);

--
-- Indeks untuk tabel `mst_lokasi_penyimpanan`
--
ALTER TABLE `mst_lokasi_penyimpanan`
  ADD PRIMARY KEY (`id_lokasi_penyimpanan`);

--
-- Indeks untuk tabel `mst_merek_barang`
--
ALTER TABLE `mst_merek_barang`
  ADD PRIMARY KEY (`id_merek_barang`),
  ADD UNIQUE KEY `kode_merek_barang` (`kode_merek_barang`),
  ADD UNIQUE KEY `nama_merek_barang` (`nama_merek_barang`);

--
-- Indeks untuk tabel `mst_role`
--
ALTER TABLE `mst_role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indeks untuk tabel `mst_satuan`
--
ALTER TABLE `mst_satuan`
  ADD PRIMARY KEY (`id_satuan`);

--
-- Indeks untuk tabel `mst_status`
--
ALTER TABLE `mst_status`
  ADD PRIMARY KEY (`id_status`);

--
-- Indeks untuk tabel `mst_template_surat`
--
ALTER TABLE `mst_template_surat`
  ADD PRIMARY KEY (`id_template`);

--
-- Indeks untuk tabel `trx_barang`
--
ALTER TABLE `trx_barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `id_jenis_barang` (`id_jenis_barang`),
  ADD KEY `id_merek_barang` (`id_merek_barang`),
  ADD KEY `id_kondisi_barang` (`id_kondisi_barang`),
  ADD KEY `id_satuan` (`id_satuan`),
  ADD KEY `id_lokasi_penyimpanan` (`id_lokasi_penyimpanan`),
  ADD KEY `id_status` (`id_status`);

--
-- Indeks untuk tabel `trx_data_user`
--
ALTER TABLE `trx_data_user`
  ADD PRIMARY KEY (`id_data_user`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `trx_peminjaman`
--
ALTER TABLE `trx_peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `fk_jenis_barang` (`id_jenis_barang`);

--
-- Indeks untuk tabel `trx_pengembalian`
--
ALTER TABLE `trx_pengembalian`
  ADD PRIMARY KEY (`id_pengembalian`),
  ADD KEY `id_peminjaman` (`id_peminjaman`);

--
-- Indeks untuk tabel `trx_user`
--
ALTER TABLE `trx_user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_role` (`id_role`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `mst_jenis_barang`
--
ALTER TABLE `mst_jenis_barang`
  MODIFY `id_jenis_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `mst_kondisi_barang`
--
ALTER TABLE `mst_kondisi_barang`
  MODIFY `id_kondisi_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `mst_lokasi_penyimpanan`
--
ALTER TABLE `mst_lokasi_penyimpanan`
  MODIFY `id_lokasi_penyimpanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `mst_merek_barang`
--
ALTER TABLE `mst_merek_barang`
  MODIFY `id_merek_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `mst_role`
--
ALTER TABLE `mst_role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `mst_satuan`
--
ALTER TABLE `mst_satuan`
  MODIFY `id_satuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `mst_status`
--
ALTER TABLE `mst_status`
  MODIFY `id_status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `mst_template_surat`
--
ALTER TABLE `mst_template_surat`
  MODIFY `id_template` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `trx_barang`
--
ALTER TABLE `trx_barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT untuk tabel `trx_data_user`
--
ALTER TABLE `trx_data_user`
  MODIFY `id_data_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `trx_peminjaman`
--
ALTER TABLE `trx_peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `trx_pengembalian`
--
ALTER TABLE `trx_pengembalian`
  MODIFY `id_pengembalian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `trx_user`
--
ALTER TABLE `trx_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `trx_barang`
--
ALTER TABLE `trx_barang`
  ADD CONSTRAINT `trx_barang_ibfk_1` FOREIGN KEY (`id_jenis_barang`) REFERENCES `mst_jenis_barang` (`id_jenis_barang`),
  ADD CONSTRAINT `trx_barang_ibfk_2` FOREIGN KEY (`id_merek_barang`) REFERENCES `mst_merek_barang` (`id_merek_barang`),
  ADD CONSTRAINT `trx_barang_ibfk_3` FOREIGN KEY (`id_kondisi_barang`) REFERENCES `mst_kondisi_barang` (`id_kondisi_barang`),
  ADD CONSTRAINT `trx_barang_ibfk_4` FOREIGN KEY (`id_satuan`) REFERENCES `mst_satuan` (`id_satuan`),
  ADD CONSTRAINT `trx_barang_ibfk_5` FOREIGN KEY (`id_lokasi_penyimpanan`) REFERENCES `mst_lokasi_penyimpanan` (`id_lokasi_penyimpanan`),
  ADD CONSTRAINT `trx_barang_ibfk_6` FOREIGN KEY (`id_status`) REFERENCES `mst_status` (`id_status`);

--
-- Ketidakleluasaan untuk tabel `trx_data_user`
--
ALTER TABLE `trx_data_user`
  ADD CONSTRAINT `trx_data_user_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `trx_user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `trx_peminjaman`
--
ALTER TABLE `trx_peminjaman`
  ADD CONSTRAINT `fk_jenis_barang` FOREIGN KEY (`id_jenis_barang`) REFERENCES `mst_jenis_barang` (`id_jenis_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `trx_pengembalian`
--
ALTER TABLE `trx_pengembalian`
  ADD CONSTRAINT `trx_pengembalian_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `trx_peminjaman` (`id_peminjaman`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `trx_user`
--
ALTER TABLE `trx_user`
  ADD CONSTRAINT `trx_user_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `mst_role` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
