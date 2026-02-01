-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2026 at 08:45 AM
-- Server version: 10.4.32-MariaDB-log
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventori_db12`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `detail_barang`
-- (See below for the actual view)
--
CREATE TABLE `detail_barang` (
);

-- --------------------------------------------------------

--
-- Table structure for table `mst_jenis_barang`
--

CREATE TABLE `mst_jenis_barang` (
  `id_jenis_barang` int(11) NOT NULL,
  `sub_barang` varchar(50) DEFAULT NULL,
  `grup_sub` char(1) DEFAULT NULL,
  `kode_sub` varchar(3) DEFAULT NULL,
  `kode_jenis_barang` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mst_jenis_barang`
--

INSERT INTO `mst_jenis_barang` (`id_jenis_barang`, `sub_barang`, `grup_sub`, `kode_sub`, `kode_jenis_barang`) VALUES
(3, 'Keyboard', 'C', 'KY1', 'C/KY1'),
(4, 'Headset', 'A', 'HS1', 'A/HS1'),
(5, 'Laptop', 'C', 'LP1', 'C/LP1');

-- --------------------------------------------------------

--
-- Table structure for table `mst_kondisi_barang`
--

CREATE TABLE `mst_kondisi_barang` (
  `id_kondisi_barang` int(11) NOT NULL,
  `kondisi_barang` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mst_kondisi_barang`
--

INSERT INTO `mst_kondisi_barang` (`id_kondisi_barang`, `kondisi_barang`) VALUES
(1, 'Baik'),
(2, 'Rusak - dapat diperbaiki '),
(3, 'Rusak - sedang diperbaiki'),
(4, 'Rusak total'),
(5, 'Sudah terpakai');

-- --------------------------------------------------------

--
-- Table structure for table `mst_lokasi_penyimpanan`
--

CREATE TABLE `mst_lokasi_penyimpanan` (
  `id_lokasi_penyimpanan` int(11) NOT NULL,
  `nama_lokasi_penyimpanan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mst_lokasi_penyimpanan`
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
-- Table structure for table `mst_merek_barang`
--

CREATE TABLE `mst_merek_barang` (
  `id_merek_barang` int(11) NOT NULL,
  `nama_merek_barang` varchar(50) DEFAULT NULL,
  `kode_merek_barang` char(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mst_merek_barang`
--

INSERT INTO `mst_merek_barang` (`id_merek_barang`, `nama_merek_barang`, `kode_merek_barang`) VALUES
(3, 'ASUS', '201'),
(4, 'Lenovo', '301'),
(5, 'HP', '401');

-- --------------------------------------------------------

--
-- Table structure for table `mst_role`
--

CREATE TABLE `mst_role` (
  `id_role` int(11) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mst_role`
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
-- Table structure for table `mst_satuan`
--

CREATE TABLE `mst_satuan` (
  `id_satuan` int(11) NOT NULL,
  `nama_satuan` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mst_satuan`
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
-- Table structure for table `mst_spesifikasi`
--

CREATE TABLE `mst_spesifikasi` (
  `id_spesifikasi` int(11) NOT NULL,
  `spesifikasi_barang` varchar(255) NOT NULL,
  `foto_barang` text NOT NULL,
  `id_jenis_barang` int(11) NOT NULL,
  `id_merek_barang` int(11) NOT NULL,
  `id_satuan` int(11) NOT NULL,
  `qr_code_spesifikasi` text DEFAULT NULL,
  `kode_barang` varchar(50) DEFAULT NULL COMMENT 'Contoh: 2026/01/C/LP1/401',
  `jumlah_total` int(11) DEFAULT 0 COMMENT 'Total barang dalam batch ini'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mst_spesifikasi`
--

INSERT INTO `mst_spesifikasi` (`id_spesifikasi`, `spesifikasi_barang`, `foto_barang`, `id_jenis_barang`, `id_merek_barang`, `id_satuan`, `qr_code_spesifikasi`, `kode_barang`, `jumlah_total`) VALUES
(2, 'Keyboard ASUS Series-9962', '../public/img/foto-barang/new_2.png', 3, 3, 5, NULL, NULL, 0),
(3, 'Headset Lenovo Series-7685', '../public/img/foto-barang/new_3.png', 4, 4, 1, NULL, NULL, 0),
(4, 'Headset HP Series-4195', '../public/img/foto-barang/new_4.png', 4, 5, 1, NULL, NULL, 0),
(5, 'Headset HP Series-7966', '../public/img/foto-barang/new_5.png', 4, 5, 1, NULL, NULL, 0),
(6, 'Headset ASUS Series-4764', '../public/img/foto-barang/new_6.png', 4, 3, 1, NULL, NULL, 0),
(7, 'Laptop HP Series-8625', '../public/img/foto-barang/new_7.png', 5, 5, 5, NULL, NULL, 0),
(8, 'Keyboard ASUS Series-1166', '../public/img/foto-barang/new_8.png', 3, 3, 1, NULL, NULL, 0),
(9, 'Keyboard Lenovo Series-3029', '../public/img/foto-barang/new_9.png', 3, 4, 5, NULL, NULL, 0),
(10, 'Headset ASUS Series-3302', '../public/img/foto-barang/new_10.png', 4, 3, 3, NULL, NULL, 0),
(16, 'Keyboard 1', '../public/img/foto-barang/697cc22be4b2c_Group 19.png', 5, 5, 1, '../public/img/qr-code/MASTER_SPEK_697cc22beda31.png', NULL, 0),
(18, 'Keyboard 12', '../public/img/foto-barang/697cec0fdeaf8_vlcsnap-2026-01-18-14h48m23s669.png', 3, 3, 1, '../public/img/qr-code/MASTER_SPEK_UPD_697da91140263.png', '2026/01/C/KY1/201', 30);

-- --------------------------------------------------------

--
-- Table structure for table `mst_status`
--

CREATE TABLE `mst_status` (
  `id_status` int(11) NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mst_status`
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
-- Table structure for table `mst_template_surat`
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
-- Table structure for table `trx_barang`
--

CREATE TABLE `trx_barang` (
  `id_barang` int(11) NOT NULL,
  `id_spesifikasi` int(11) NOT NULL,
  `id_kondisi_barang` int(11) DEFAULT NULL,
  `tgl_pengadaan_barang` date NOT NULL,
  `keterangan_label` enum('Sudah','Belum') NOT NULL,
  `id_lokasi_penyimpanan` int(11) DEFAULT NULL,
  `deskripsi_detail_lokasi` text DEFAULT NULL,
  `id_status` int(11) DEFAULT NULL,
  `status_peminjaman` enum('Bisa','Tidak Bisa') NOT NULL,
  `qr_code` text NOT NULL,
  `urutan_unit` int(11) DEFAULT 1 COMMENT 'Barang ke-1, ke-2, dst'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trx_barang`
--

INSERT INTO `trx_barang` (`id_barang`, `id_spesifikasi`, `id_kondisi_barang`, `tgl_pengadaan_barang`, `keterangan_label`, `id_lokasi_penyimpanan`, `deskripsi_detail_lokasi`, `id_status`, `status_peminjaman`, `qr_code`, `urutan_unit`) VALUES
(2, 2, 1, '2026-01-22', 'Sudah', 6, 'Pengadaan Baru', 5, 'Bisa', '../public/img/qr-code/new_2.png', 1),
(3, 3, 2, '2026-01-22', 'Sudah', 10, 'Pengadaan Baru', 5, 'Bisa', '../public/img/qr-code/new_3.png', 1),
(4, 4, 1, '2026-01-22', 'Sudah', 16, 'Pengadaan Baru', 3, 'Bisa', '../public/img/qr-code/new_4.png', 1),
(5, 5, 4, '2026-01-22', 'Sudah', 12, 'Pengadaan Baru', 3, 'Tidak Bisa', '../public/img/qr-code/new_5.png', 1),
(6, 6, 5, '2026-01-22', 'Belum', 6, 'Pengadaan Baru', 3, 'Tidak Bisa', '../public/img/qr-code/new_6.png', 1),
(7, 7, 1, '2026-01-22', 'Sudah', 8, 'Pengadaan Baru', 5, 'Bisa', '../public/img/qr-code/new_7.png', 1),
(8, 8, 4, '2026-01-22', 'Belum', 12, 'Pengadaan Baru', 5, 'Tidak Bisa', '../public/img/qr-code/new_8.png', 1),
(9, 9, 1, '2026-01-22', 'Belum', 18, 'Pengadaan Baru', 5, 'Tidak Bisa', '../public/img/qr-code/new_9.png', 1),
(10, 10, 1, '2026-01-22', 'Sudah', 4, 'Pengadaan Baru', 3, 'Bisa', '../public/img/qr-code/new_10.png', 1),
(11, 16, 1, '2026-01-30', 'Belum', 18, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cc22c9983e.png', 1),
(12, 16, 1, '2026-01-30', 'Belum', 18, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cc22d51424.png', 1),
(13, 16, 1, '2026-01-30', 'Belum', 18, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cc22d78650.png', 1),
(15, 18, 1, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec0fe62fc.png', 1),
(16, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec1015dac.png', 2),
(17, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec10344b3.png', 3),
(18, 18, 1, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec104c318.png', 4),
(19, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec106e30a.png', 5),
(20, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec1090481.png', 6),
(21, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec10b8795.png', 7),
(22, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec10db60c.png', 8),
(23, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec1106c34.png', 9),
(24, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec1128985.png', 10),
(25, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec114bac1.png', 11),
(26, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec116d4da.png', 12),
(27, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec118cda6.png', 13),
(28, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec11aca8b.png', 14),
(29, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec11c6f90.png', 15),
(30, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec11ded93.png', 16),
(31, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec120dc3d.png', 17),
(32, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec122dc8f.png', 18),
(33, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec1253407.png', 19),
(34, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec1273ca0.png', 20),
(35, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec1292d65.png', 21),
(36, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec12b44a3.png', 22),
(37, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec12d9b13.png', 23),
(38, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec130eb5d.png', 24),
(39, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec133b876.png', 25),
(40, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec135715b.png', 26),
(41, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec1376f41.png', 27),
(42, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec139ac33.png', 28),
(43, 18, 3, '2026-01-30', 'Sudah', 13, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_697cec13c29c4.png', 29),
(44, 18, 1, '2026-01-30', 'Sudah', 12, 'wasdfasdf', 5, 'Bisa', '../public/img/qr-code/UNIT_UPD_697da910962de.png', 30);

-- --------------------------------------------------------

--
-- Table structure for table `trx_data_user`
--

CREATE TABLE `trx_data_user` (
  `id_data_user` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `foto` text DEFAULT NULL,
  `nama_user` varchar(100) NOT NULL,
  `nim_nip` varchar(30) NOT NULL,
  `no_hp_user` varchar(15) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `file_ttd` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trx_data_user`
--

INSERT INTO `trx_data_user` (`id_data_user`, `id_user`, `foto`, `nama_user`, `nim_nip`, `no_hp_user`, `jenis_kelamin`, `alamat`, `file_ttd`) VALUES
(1, 1, '../public/img/foto-profile/user.svg', 'Kepala Lab', '001', '081234567001', 'Laki-laki', 'Makassar', NULL),
(2, 2, '../public/img/foto-profile/user.svg', 'Laboran', '002', '081234567002', 'Perempuan', 'Makassar', NULL),
(3, 3, '../public/img/foto-profile/user.svg', 'Koordinator Lab', '003', '081234567003', 'Laki-laki', 'Makassar', NULL),
(4, 4, '../public/img/foto-profile/user.svg', 'Asisten Lab', '004', '081234567004', 'Perempuan', 'Makassar', NULL),
(5, 5, '../public/img/foto-profile/user.svg', 'Calon Asisten', '005', '081234567005', 'Laki-laki', 'Makassar', NULL),
(6, 6, '../public/img/foto-profile/user.svg', 'Calon CA', '006', '081234567006', 'Perempuan', 'Makassar', NULL),
(7, 7, '../public/img/foto-profile/user.svg', 'Mahasiswa', '007', '081234567007', 'Laki-laki', 'Makassar', NULL),
(35, 36, '../public/img/foto-profile/697d7ea0734ac.png', 'Andi Rifqi Aunur Rahman', '13020230219', '088246700573', 'Laki-laki', 'Perumnas BTP Blok H.lama No.509, Tamalanrea, Kec. Tamalanrea, Kota Makassar, Sulawesi Selatan 90245', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trx_detail_peminjaman`
--

CREATE TABLE `trx_detail_peminjaman` (
  `id_detail` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `id_jenis_barang` int(11) NOT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `keterangan_barang` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trx_detail_peminjaman`
--

INSERT INTO `trx_detail_peminjaman` (`id_detail`, `id_peminjaman`, `id_jenis_barang`, `id_barang`, `jumlah`, `keterangan_barang`) VALUES
(4, 1, 3, 2, 1, NULL),
(5, 1, 5, 7, 1, NULL),
(6, 1, 3, 2, 1, NULL),
(7, 2, 4, 4, 1, NULL),
(8, 3, 3, 15, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trx_detail_pengembalian`
--

CREATE TABLE `trx_detail_pengembalian` (
  `id_detail_pengembalian` int(11) NOT NULL,
  `id_pengembalian` int(11) NOT NULL,
  `id_detail_peminjaman` int(11) NOT NULL,
  `jumlah_kembali` int(11) NOT NULL,
  `kondisi_barang` enum('Baik','Rusak','Hilang') NOT NULL,
  `keterangan_kondisi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trx_detail_pengembalian`
--

INSERT INTO `trx_detail_pengembalian` (`id_detail_pengembalian`, `id_pengembalian`, `id_detail_peminjaman`, `jumlah_kembali`, `kondisi_barang`, `keterangan_kondisi`) VALUES
(1, 3, 5, 1, 'Baik', ''),
(2, 3, 6, 1, 'Rusak', '');

-- --------------------------------------------------------

--
-- Table structure for table `trx_pemeriksa_pengembalian`
--

CREATE TABLE `trx_pemeriksa_pengembalian` (
  `id_pemeriksa` int(11) NOT NULL,
  `id_pengembalian` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `waktu_periksa` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trx_pemeriksa_pengembalian`
--

INSERT INTO `trx_pemeriksa_pengembalian` (`id_pemeriksa`, `id_pengembalian`, `id_user`, `waktu_periksa`) VALUES
(1, 3, 30, '2026-01-25 07:09:02');

-- --------------------------------------------------------

--
-- Table structure for table `trx_peminjaman`
--

CREATE TABLE `trx_peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `judul_kegiatan` varchar(255) NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `tanggal_peminjaman` date NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `keterangan_peminjaman` text DEFAULT NULL,
  `keterangan_tolak` text DEFAULT NULL,
  `status` enum('Diproses','Disetujui','Tolak Peminjaman','Dikembalikan','Melengkapi Surat','Tolak Pengembalian') DEFAULT 'Melengkapi Surat',
  `file_surat` varchar(255) DEFAULT NULL,
  `validasi_kalab` enum('0','1') DEFAULT '0' COMMENT '0=Belum, 1=Sudah (Huzain)',
  `validasi_laboran` enum('0','1') DEFAULT '0' COMMENT '0=Belum, 1=Sudah (Fatimah)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trx_peminjaman`
--

INSERT INTO `trx_peminjaman` (`id_peminjaman`, `id_user`, `judul_kegiatan`, `tanggal_pengajuan`, `tanggal_peminjaman`, `tanggal_pengembalian`, `keterangan_peminjaman`, `keterangan_tolak`, `status`, `file_surat`, `validasi_kalab`, `validasi_laboran`) VALUES
(1, 27, 'qasd', '2026-01-25', '2026-01-25', '2026-01-25', '12312312', '', 'Tolak Peminjaman', 'SIGNED_6976110029a5f.pdf', '0', '0'),
(2, 1, 'COBA AJA Admin', '2026-01-31', '2026-01-22', '2026-01-22', '-', NULL, 'Diproses', 'SIGNED_697da1131070f.pdf', '0', '0'),
(3, 1, 'COBA AJA Admin', '2026-02-01', '2026-01-22', '2026-01-22', '-', NULL, 'Melengkapi Surat', NULL, '0', '0');

-- --------------------------------------------------------

--
-- Table structure for table `trx_pengembalian`
--

CREATE TABLE `trx_pengembalian` (
  `id_pengembalian` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `tgl_pengembalian_aktual` date DEFAULT NULL,
  `status_pengembalian` enum('Selesai Periksa','Periksa','Periksa Ulang') DEFAULT NULL,
  `keterangan` enum('Tepat Waktu','Tidak Tepat Waktu','Bermasalah') DEFAULT NULL,
  `detail_masalah` text DEFAULT NULL,
  `bukti_foto` varchar(255) DEFAULT NULL COMMENT 'Path foto bukti pengembalian'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trx_user`
--

CREATE TABLE `trx_user` (
  `id_user` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_role` int(11) NOT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=Belum Verifikasi, 1=Sudah Verifikasi',
  `verification_token` varchar(64) DEFAULT NULL COMMENT 'Token untuk verifikasi email',
  `token_expiry` datetime DEFAULT NULL COMMENT 'Waktu expired token verifikasi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trx_user`
--

INSERT INTO `trx_user` (`id_user`, `email`, `password`, `id_role`, `email_verified`, `verification_token`, `token_expiry`) VALUES
(1, 'keplab@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1, NULL, NULL),
(2, 'laboran@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 1, NULL, NULL),
(3, 'korlab@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 1, NULL, NULL),
(4, 'asisten@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4, 1, NULL, NULL),
(5, 'ca@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 5, 1, NULL, NULL),
(6, 'cca@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 6, 1, NULL, NULL),
(7, 'mhs@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 7, 1, NULL, NULL),
(36, 'andikah3954g@gmail.com', '$2y$10$NMLn4c7wXE0Zwwr2K0BnE.hEktDu5C.fc1MDujgnaLxJgbdkEXOEG', 7, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure for view `detail_barang`
--
DROP TABLE IF EXISTS `detail_barang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detail_barang`  AS SELECT `b`.`id_barang` AS `id_barang`, `b`.`foto_barang` AS `foto_barang`, `j`.`sub_barang` AS `sub_barang`, `m`.`nama_merek_barang` AS `nama_merek_barang`, `k`.`kondisi_barang` AS `kondisi_barang`, `b`.`jumlah_barang` AS `jumlah_barang`, `s`.`nama_satuan` AS `nama_satuan`, `b`.`spesifikasi_barang` AS `spesifikasi_barang`, `b`.`tgl_pengadaan_barang` AS `tgl_pengadaan_barang`, `b`.`kode_barang` AS `kode_barang`, `b`.`keterangan_label` AS `keterangan_label`, `l`.`nama_lokasi_penyimpanan` AS `nama_lokasi_penyimpanan`, `b`.`deskripsi_detail_lokasi` AS `deskripsi_detail_lokasi`, `st`.`status` AS `status`, `b`.`status_peminjaman` AS `status_peminjaman`, `b`.`qr_code` AS `qr_code` FROM ((((((`trx_barang` `b` join `mst_jenis_barang` `j` on(`b`.`id_jenis_barang` = `j`.`id_jenis_barang`)) join `mst_merek_barang` `m` on(`b`.`id_merek_barang` = `m`.`id_merek_barang`)) join `mst_satuan` `s` on(`b`.`id_satuan` = `s`.`id_satuan`)) join `mst_kondisi_barang` `k` on(`b`.`id_kondisi_barang` = `k`.`id_kondisi_barang`)) join `mst_lokasi_penyimpanan` `l` on(`b`.`id_lokasi_penyimpanan` = `l`.`id_lokasi_penyimpanan`)) join `mst_status` `st` on(`b`.`id_status` = `st`.`id_status`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mst_jenis_barang`
--
ALTER TABLE `mst_jenis_barang`
  ADD PRIMARY KEY (`id_jenis_barang`),
  ADD UNIQUE KEY `kode_sub` (`kode_sub`);

--
-- Indexes for table `mst_kondisi_barang`
--
ALTER TABLE `mst_kondisi_barang`
  ADD PRIMARY KEY (`id_kondisi_barang`);

--
-- Indexes for table `mst_lokasi_penyimpanan`
--
ALTER TABLE `mst_lokasi_penyimpanan`
  ADD PRIMARY KEY (`id_lokasi_penyimpanan`);

--
-- Indexes for table `mst_merek_barang`
--
ALTER TABLE `mst_merek_barang`
  ADD PRIMARY KEY (`id_merek_barang`),
  ADD UNIQUE KEY `kode_merek_barang` (`kode_merek_barang`),
  ADD UNIQUE KEY `nama_merek_barang` (`nama_merek_barang`);

--
-- Indexes for table `mst_role`
--
ALTER TABLE `mst_role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `mst_satuan`
--
ALTER TABLE `mst_satuan`
  ADD PRIMARY KEY (`id_satuan`);

--
-- Indexes for table `mst_spesifikasi`
--
ALTER TABLE `mst_spesifikasi`
  ADD PRIMARY KEY (`id_spesifikasi`),
  ADD KEY `fk_spek_jenis` (`id_jenis_barang`),
  ADD KEY `fk_spek_merek` (`id_merek_barang`),
  ADD KEY `fk_spek_satuan` (`id_satuan`);

--
-- Indexes for table `mst_status`
--
ALTER TABLE `mst_status`
  ADD PRIMARY KEY (`id_status`);

--
-- Indexes for table `mst_template_surat`
--
ALTER TABLE `mst_template_surat`
  ADD PRIMARY KEY (`id_template`);

--
-- Indexes for table `trx_barang`
--
ALTER TABLE `trx_barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `id_kondisi_barang` (`id_kondisi_barang`),
  ADD KEY `id_lokasi_penyimpanan` (`id_lokasi_penyimpanan`),
  ADD KEY `id_status` (`id_status`),
  ADD KEY `fk_trx_spek` (`id_spesifikasi`);

--
-- Indexes for table `trx_data_user`
--
ALTER TABLE `trx_data_user`
  ADD PRIMARY KEY (`id_data_user`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `trx_detail_peminjaman`
--
ALTER TABLE `trx_detail_peminjaman`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_peminjaman` (`id_peminjaman`),
  ADD KEY `id_jenis_barang` (`id_jenis_barang`),
  ADD KEY `fk_detail_barang_unit` (`id_barang`);

--
-- Indexes for table `trx_detail_pengembalian`
--
ALTER TABLE `trx_detail_pengembalian`
  ADD PRIMARY KEY (`id_detail_pengembalian`),
  ADD KEY `idx_pengembalian` (`id_pengembalian`),
  ADD KEY `idx_detail_peminjaman` (`id_detail_peminjaman`);

--
-- Indexes for table `trx_pemeriksa_pengembalian`
--
ALTER TABLE `trx_pemeriksa_pengembalian`
  ADD PRIMARY KEY (`id_pemeriksa`),
  ADD KEY `fk_cek_pengem` (`id_pengembalian`),
  ADD KEY `fk_cek_user` (`id_user`);

--
-- Indexes for table `trx_peminjaman`
--
ALTER TABLE `trx_peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `fk_peminjaman_user` (`id_user`);

--
-- Indexes for table `trx_pengembalian`
--
ALTER TABLE `trx_pengembalian`
  ADD PRIMARY KEY (`id_pengembalian`),
  ADD KEY `id_peminjaman` (`id_peminjaman`);

--
-- Indexes for table `trx_user`
--
ALTER TABLE `trx_user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_role` (`id_role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mst_jenis_barang`
--
ALTER TABLE `mst_jenis_barang`
  MODIFY `id_jenis_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mst_kondisi_barang`
--
ALTER TABLE `mst_kondisi_barang`
  MODIFY `id_kondisi_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mst_lokasi_penyimpanan`
--
ALTER TABLE `mst_lokasi_penyimpanan`
  MODIFY `id_lokasi_penyimpanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `mst_merek_barang`
--
ALTER TABLE `mst_merek_barang`
  MODIFY `id_merek_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mst_role`
--
ALTER TABLE `mst_role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mst_satuan`
--
ALTER TABLE `mst_satuan`
  MODIFY `id_satuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `mst_spesifikasi`
--
ALTER TABLE `mst_spesifikasi`
  MODIFY `id_spesifikasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `mst_status`
--
ALTER TABLE `mst_status`
  MODIFY `id_status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `mst_template_surat`
--
ALTER TABLE `mst_template_surat`
  MODIFY `id_template` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trx_barang`
--
ALTER TABLE `trx_barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `trx_data_user`
--
ALTER TABLE `trx_data_user`
  MODIFY `id_data_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `trx_detail_peminjaman`
--
ALTER TABLE `trx_detail_peminjaman`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `trx_detail_pengembalian`
--
ALTER TABLE `trx_detail_pengembalian`
  MODIFY `id_detail_pengembalian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `trx_pemeriksa_pengembalian`
--
ALTER TABLE `trx_pemeriksa_pengembalian`
  MODIFY `id_pemeriksa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `trx_peminjaman`
--
ALTER TABLE `trx_peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `trx_pengembalian`
--
ALTER TABLE `trx_pengembalian`
  MODIFY `id_pengembalian` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trx_user`
--
ALTER TABLE `trx_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `mst_spesifikasi`
--
ALTER TABLE `mst_spesifikasi`
  ADD CONSTRAINT `fk_spek_jenis` FOREIGN KEY (`id_jenis_barang`) REFERENCES `mst_jenis_barang` (`id_jenis_barang`),
  ADD CONSTRAINT `fk_spek_merek` FOREIGN KEY (`id_merek_barang`) REFERENCES `mst_merek_barang` (`id_merek_barang`),
  ADD CONSTRAINT `fk_spek_satuan` FOREIGN KEY (`id_satuan`) REFERENCES `mst_satuan` (`id_satuan`);

--
-- Constraints for table `trx_barang`
--
ALTER TABLE `trx_barang`
  ADD CONSTRAINT `fk_trx_spek` FOREIGN KEY (`id_spesifikasi`) REFERENCES `mst_spesifikasi` (`id_spesifikasi`),
  ADD CONSTRAINT `trx_barang_ibfk_3` FOREIGN KEY (`id_kondisi_barang`) REFERENCES `mst_kondisi_barang` (`id_kondisi_barang`),
  ADD CONSTRAINT `trx_barang_ibfk_5` FOREIGN KEY (`id_lokasi_penyimpanan`) REFERENCES `mst_lokasi_penyimpanan` (`id_lokasi_penyimpanan`),
  ADD CONSTRAINT `trx_barang_ibfk_6` FOREIGN KEY (`id_status`) REFERENCES `mst_status` (`id_status`);

--
-- Constraints for table `trx_data_user`
--
ALTER TABLE `trx_data_user`
  ADD CONSTRAINT `trx_data_user_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `trx_user` (`id_user`);

--
-- Constraints for table `trx_detail_peminjaman`
--
ALTER TABLE `trx_detail_peminjaman`
  ADD CONSTRAINT `fk_detail_barang_unit` FOREIGN KEY (`id_barang`) REFERENCES `trx_barang` (`id_barang`) ON DELETE SET NULL,
  ADD CONSTRAINT `trx_detail_peminjaman_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `trx_peminjaman` (`id_peminjaman`) ON DELETE CASCADE,
  ADD CONSTRAINT `trx_detail_peminjaman_ibfk_2` FOREIGN KEY (`id_jenis_barang`) REFERENCES `mst_jenis_barang` (`id_jenis_barang`);

--
-- Constraints for table `trx_detail_pengembalian`
--
ALTER TABLE `trx_detail_pengembalian`
  ADD CONSTRAINT `fk_detail_kembali_header` FOREIGN KEY (`id_pengembalian`) REFERENCES `trx_pengembalian` (`id_pengembalian`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detail_kembali_pinjam` FOREIGN KEY (`id_detail_peminjaman`) REFERENCES `trx_detail_peminjaman` (`id_detail`) ON DELETE CASCADE;

--
-- Constraints for table `trx_pemeriksa_pengembalian`
--
ALTER TABLE `trx_pemeriksa_pengembalian`
  ADD CONSTRAINT `fk_cek_pengem` FOREIGN KEY (`id_pengembalian`) REFERENCES `trx_pengembalian` (`id_pengembalian`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cek_user` FOREIGN KEY (`id_user`) REFERENCES `mst_data` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `trx_peminjaman`
--
ALTER TABLE `trx_peminjaman`
  ADD CONSTRAINT `fk_peminjaman_user` FOREIGN KEY (`id_user`) REFERENCES `trx_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trx_pengembalian`
--
ALTER TABLE `trx_pengembalian`
  ADD CONSTRAINT `trx_pengembalian_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `trx_peminjaman` (`id_peminjaman`) ON DELETE CASCADE;

--
-- Constraints for table `trx_user`
--
ALTER TABLE `trx_user`
  ADD CONSTRAINT `trx_user_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `mst_role` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
