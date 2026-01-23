-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: inventori_db1
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Temporary view structure for view `detail_barang`
--

DROP TABLE IF EXISTS `detail_barang`;
/*!50001 DROP VIEW IF EXISTS `detail_barang`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `detail_barang` AS SELECT 
 1 AS `id_barang`,
 1 AS `foto_barang`,
 1 AS `sub_barang`,
 1 AS `nama_merek_barang`,
 1 AS `kondisi_barang`,
 1 AS `jumlah_barang`,
 1 AS `nama_satuan`,
 1 AS `spesifikasi_barang`,
 1 AS `tgl_pengadaan_barang`,
 1 AS `kode_barang`,
 1 AS `keterangan_label`,
 1 AS `nama_lokasi_penyimpanan`,
 1 AS `deskripsi_detail_lokasi`,
 1 AS `status`,
 1 AS `status_peminjaman`,
 1 AS `qr_code`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `mst_jenis_barang`
--

DROP TABLE IF EXISTS `mst_jenis_barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_jenis_barang` (
  `id_jenis_barang` int(11) NOT NULL AUTO_INCREMENT,
  `sub_barang` varchar(50) DEFAULT NULL,
  `grup_sub` char(1) DEFAULT NULL,
  `kode_sub` varchar(3) DEFAULT NULL,
  `kode_jenis_barang` varchar(5) NOT NULL,
  PRIMARY KEY (`id_jenis_barang`),
  UNIQUE KEY `kode_sub` (`kode_sub`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_jenis_barang`
--

LOCK TABLES `mst_jenis_barang` WRITE;
/*!40000 ALTER TABLE `mst_jenis_barang` DISABLE KEYS */;
INSERT INTO `mst_jenis_barang` VALUES (1,'Mouse','C','MO4','C/MO4'),(2,'Monitor','C','MO3','C/MO3');
/*!40000 ALTER TABLE `mst_jenis_barang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mst_kondisi_barang`
--

DROP TABLE IF EXISTS `mst_kondisi_barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_kondisi_barang` (
  `id_kondisi_barang` int(11) NOT NULL AUTO_INCREMENT,
  `kondisi_barang` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_kondisi_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_kondisi_barang`
--

LOCK TABLES `mst_kondisi_barang` WRITE;
/*!40000 ALTER TABLE `mst_kondisi_barang` DISABLE KEYS */;
INSERT INTO `mst_kondisi_barang` VALUES (1,'Baik'),(2,'Rusak - dapat diperbaiki '),(3,'Rusak - sedang diperbaiki'),(4,'Rusak total'),(5,'Sudah terpakai');
/*!40000 ALTER TABLE `mst_kondisi_barang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mst_lokasi_penyimpanan`
--

DROP TABLE IF EXISTS `mst_lokasi_penyimpanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_lokasi_penyimpanan` (
  `id_lokasi_penyimpanan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lokasi_penyimpanan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_lokasi_penyimpanan`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_lokasi_penyimpanan`
--

LOCK TABLES `mst_lokasi_penyimpanan` WRITE;
/*!40000 ALTER TABLE `mst_lokasi_penyimpanan` DISABLE KEYS */;
INSERT INTO `mst_lokasi_penyimpanan` VALUES (1,'Lab Iot'),(2,'Lab StartUp'),(3,'Lab Neetworking'),(4,'Lab Multimedia'),(5,'Lab Computer Vision'),(6,'Lab Data Since'),(7,'Lab Micro Controller'),(8,'Rg PC I'),(9,'Rg PC II'),(10,'Rg Server'),(11,'Gudang'),(12,'Rg Laboran'),(13,'Rg Asisten Lab'),(14,'Rg Riset I'),(15,'Rg Riset II'),(16,'Rg Riset III'),(17,'Rg Kepala Lab I'),(18,'Rg Kepala Lab II');
/*!40000 ALTER TABLE `mst_lokasi_penyimpanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mst_merek_barang`
--

DROP TABLE IF EXISTS `mst_merek_barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_merek_barang` (
  `id_merek_barang` int(11) NOT NULL AUTO_INCREMENT,
  `nama_merek_barang` varchar(50) DEFAULT NULL,
  `kode_merek_barang` char(3) DEFAULT NULL,
  PRIMARY KEY (`id_merek_barang`),
  UNIQUE KEY `kode_merek_barang` (`kode_merek_barang`),
  UNIQUE KEY `nama_merek_barang` (`nama_merek_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_merek_barang`
--

LOCK TABLES `mst_merek_barang` WRITE;
/*!40000 ALTER TABLE `mst_merek_barang` DISABLE KEYS */;
INSERT INTO `mst_merek_barang` VALUES (1,'Logitech','001'),(2,'LG','101');
/*!40000 ALTER TABLE `mst_merek_barang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mst_role`
--

DROP TABLE IF EXISTS `mst_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_role` (
  `id_role` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(20) NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_role`
--

LOCK TABLES `mst_role` WRITE;
/*!40000 ALTER TABLE `mst_role` DISABLE KEYS */;
INSERT INTO `mst_role` VALUES (1,'KEPLAB'),(2,'LABORAN'),(3,'KORLAB'),(4,'ASISTEN'),(5,'CA'),(6,'CCA'),(7,'MHS');
/*!40000 ALTER TABLE `mst_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mst_satuan`
--

DROP TABLE IF EXISTS `mst_satuan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_satuan` (
  `id_satuan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_satuan` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_satuan`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_satuan`
--

LOCK TABLES `mst_satuan` WRITE;
/*!40000 ALTER TABLE `mst_satuan` DISABLE KEYS */;
INSERT INTO `mst_satuan` VALUES (1,'Buah'),(2,'Lusin'),(3,'Dus'),(4,'Rangkaian'),(5,'Kotak'),(6,'Pack'),(7,'Box'),(8,'Roll'),(9,'Pasang');
/*!40000 ALTER TABLE `mst_satuan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mst_status`
--

DROP TABLE IF EXISTS `mst_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_status` (
  `id_status` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(30) NOT NULL,
  PRIMARY KEY (`id_status`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_status`
--

LOCK TABLES `mst_status` WRITE;
/*!40000 ALTER TABLE `mst_status` DISABLE KEYS */;
INSERT INTO `mst_status` VALUES (1,'Dipinjam'),(2,'Dipindahkan'),(3,'Stay'),(4,'Rusak'),(5,'Bagus'),(6,'Baru diganti');
/*!40000 ALTER TABLE `mst_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mst_template_surat`
--

DROP TABLE IF EXISTS `mst_template_surat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_template_surat` (
  `id_template` int(11) NOT NULL AUTO_INCREMENT,
  `nama_template` varchar(100) NOT NULL,
  `jenis_surat` enum('Peminjaman','Pengembalian','Bebas Lab') DEFAULT 'Peminjaman',
  `file_template` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_template`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_template_surat`
--

LOCK TABLES `mst_template_surat` WRITE;
/*!40000 ALTER TABLE `mst_template_surat` DISABLE KEYS */;
/*!40000 ALTER TABLE `mst_template_surat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trx_barang`
--

DROP TABLE IF EXISTS `trx_barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trx_barang` (
  `id_barang` int(11) NOT NULL AUTO_INCREMENT,
  `foto_barang` text NOT NULL,
  `id_jenis_barang` int(11) DEFAULT NULL,
  `id_merek_barang` int(11) DEFAULT NULL,
  `id_kondisi_barang` int(11) DEFAULT NULL,
  `jumlah_barang` int(3) NOT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `spesifikasi_barang` varchar(255) DEFAULT NULL,
  `tgl_pengadaan_barang` date NOT NULL,
  `keterangan_label` enum('Sudah','Belum') NOT NULL,
  `id_lokasi_penyimpanan` int(11) DEFAULT NULL,
  `deskripsi_detail_lokasi` text DEFAULT NULL,
  `id_status` int(11) DEFAULT NULL,
  `status_peminjaman` enum('Bisa','Tidak Bisa') NOT NULL,
  `kode_barang` varchar(26) NOT NULL,
  `qr_code` text NOT NULL,
  PRIMARY KEY (`id_barang`),
  KEY `id_jenis_barang` (`id_jenis_barang`),
  KEY `id_merek_barang` (`id_merek_barang`),
  KEY `id_kondisi_barang` (`id_kondisi_barang`),
  KEY `id_satuan` (`id_satuan`),
  KEY `id_lokasi_penyimpanan` (`id_lokasi_penyimpanan`),
  KEY `id_status` (`id_status`),
  CONSTRAINT `trx_barang_ibfk_1` FOREIGN KEY (`id_jenis_barang`) REFERENCES `mst_jenis_barang` (`id_jenis_barang`),
  CONSTRAINT `trx_barang_ibfk_2` FOREIGN KEY (`id_merek_barang`) REFERENCES `mst_merek_barang` (`id_merek_barang`),
  CONSTRAINT `trx_barang_ibfk_3` FOREIGN KEY (`id_kondisi_barang`) REFERENCES `mst_kondisi_barang` (`id_kondisi_barang`),
  CONSTRAINT `trx_barang_ibfk_4` FOREIGN KEY (`id_satuan`) REFERENCES `mst_satuan` (`id_satuan`),
  CONSTRAINT `trx_barang_ibfk_5` FOREIGN KEY (`id_lokasi_penyimpanan`) REFERENCES `mst_lokasi_penyimpanan` (`id_lokasi_penyimpanan`),
  CONSTRAINT `trx_barang_ibfk_6` FOREIGN KEY (`id_status`) REFERENCES `mst_status` (`id_status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_barang`
--

LOCK TABLES `trx_barang` WRITE;
/*!40000 ALTER TABLE `trx_barang` DISABLE KEYS */;
INSERT INTO `trx_barang` VALUES (1,'../public/img/foto-barang/6965413feaac4_M90.png',1,1,1,10,1,'M90','2026-01-13','Sudah',4,'lemari 7',2,'Bisa','2026/I/C/MO4/001/1/10','../public/img/qr-code/code_6965413ff0417.png'),(2,'../public/img/foto-barang/6965419428280_k120.png',2,2,1,1,1,'LG 120 inch','2026-01-13','Sudah',15,'lemari 7',5,'Bisa','2026/I/C/MO3/101/1/10','../public/img/qr-code/code_696541942d0b7.png');
/*!40000 ALTER TABLE `trx_barang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trx_data_user`
--

DROP TABLE IF EXISTS `trx_data_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trx_data_user` (
  `id_data_user` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `foto` text DEFAULT NULL,
  `nama_user` varchar(100) NOT NULL,
  `nim_nip` varchar(30) NOT NULL,
  `no_hp_user` varchar(15) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `file_ttd` text DEFAULT NULL,
  PRIMARY KEY (`id_data_user`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `trx_data_user_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `trx_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_data_user`
--

LOCK TABLES `trx_data_user` WRITE;
/*!40000 ALTER TABLE `trx_data_user` DISABLE KEYS */;
INSERT INTO `trx_data_user` VALUES (5,6,'../public/img/foto-profile/user.svg','Furqon Fatahillah','','085240153953','Laki-laki','Borong raya',NULL),(11,12,'../public/img/foto-profile/WhatsApp Image 2024-02-02 at 19.05.56_a1d84076.jpg','Nurul Azmi','','082292704208','Perempuan','pampang',NULL),(21,22,'../public/img/foto-profile/Vectto.jpeg','akbar','','0834326473434','Laki-laki','makassar',NULL),(25,26,'../public/img/foto-profile/f.jpg','Dewi Ernita Rahma','','085216090040','Perempuan','Jl. Kakaktua II',NULL),(26,27,'../public/img/foto-profile/69652cd74c2ce.png','Julisa','13020230219','085216090048','Perempuan','Pampang',NULL),(27,28,'../public/img/foto-profile/','Ahsan','','09090909090','Laki-laki','masalae',NULL),(28,29,'../public/img/foto-profile/','Andi Ahsan','','0912836728938','Laki-laki','nasakkkee',NULL),(29,30,'../public/img/foto-profile/695cba19df719.png','Andi Rahman','','088246700573','Laki-laki','Perumnas BTP Blok H.lama No.509, Tamalanrea, Kec. Tamalanrea, Kota Makassar, Sulawesi Selatan 90245',NULL),(30,31,'../public/img/foto-profile/6964df00b1fd6.jpg','Cacantik','','081374636860','Perempuan','Mars',NULL),(31,32,'../public/img/foto-profile/696bcf369de9c.png','Andi Rifqi Aunur Rahman','13020230219','088246700573','Laki-laki','Perumnas BTP Blok H.lama No.509, Tamalanrea, Kec. Tamalanrea, Kota Makassar, Sulawesi Selatan 90245',NULL);
/*!40000 ALTER TABLE `trx_data_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trx_detail_peminjaman`
--

DROP TABLE IF EXISTS `trx_detail_peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trx_detail_peminjaman` (
  `id_detail` int(11) NOT NULL AUTO_INCREMENT,
  `id_peminjaman` int(11) NOT NULL,
  `id_jenis_barang` int(11) NOT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `keterangan_barang` text DEFAULT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `id_peminjaman` (`id_peminjaman`),
  KEY `id_jenis_barang` (`id_jenis_barang`),
  KEY `fk_detail_barang_unit` (`id_barang`),
  CONSTRAINT `fk_detail_barang_unit` FOREIGN KEY (`id_barang`) REFERENCES `trx_barang` (`id_barang`) ON DELETE SET NULL,
  CONSTRAINT `trx_detail_peminjaman_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `trx_peminjaman` (`id_peminjaman`) ON DELETE CASCADE,
  CONSTRAINT `trx_detail_peminjaman_ibfk_2` FOREIGN KEY (`id_jenis_barang`) REFERENCES `mst_jenis_barang` (`id_jenis_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_detail_peminjaman`
--

LOCK TABLES `trx_detail_peminjaman` WRITE;
/*!40000 ALTER TABLE `trx_detail_peminjaman` DISABLE KEYS */;
INSERT INTO `trx_detail_peminjaman` VALUES (2,1,1,1,1,NULL),(3,2,2,2,1,NULL),(4,3,2,2,1,NULL);
/*!40000 ALTER TABLE `trx_detail_peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trx_peminjaman`
--

DROP TABLE IF EXISTS `trx_peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trx_peminjaman` (
  `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `judul_kegiatan` varchar(255) NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `tanggal_peminjaman` date NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `keterangan_peminjaman` text DEFAULT NULL,
  `status` enum('Diproses','Disetujui','Ditolak','Dikembalikan','Melengkapi Surat') DEFAULT 'Melengkapi Surat',
  `file_surat` varchar(255) DEFAULT NULL,
  `validasi_kalab` enum('0','1') DEFAULT '0' COMMENT '0=Belum, 1=Sudah (Huzain)',
  `validasi_laboran` enum('0','1') DEFAULT '0' COMMENT '0=Belum, 1=Sudah (Fatimah)',
  PRIMARY KEY (`id_peminjaman`),
  KEY `fk_peminjaman_user` (`id_user`),
  CONSTRAINT `fk_peminjaman_user` FOREIGN KEY (`id_user`) REFERENCES `trx_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_peminjaman`
--

LOCK TABLES `trx_peminjaman` WRITE;
/*!40000 ALTER TABLE `trx_peminjaman` DISABLE KEYS */;
INSERT INTO `trx_peminjaman` VALUES (1,27,'Mencoba','2026-01-17','2026-01-16','2026-01-22','asdasda','Melengkapi Surat',NULL,'0','0'),(2,30,'Mencoba','2026-01-17','2026-01-16','2026-01-22','1234567890-','Disetujui','SIGNED_696bc845f2f55.pdf','1','1'),(3,32,'Mencoba','2026-01-17','2026-01-16','2026-01-22','-','Disetujui','SIGNED_696bf0e2c7600.pdf','1','1');
/*!40000 ALTER TABLE `trx_peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trx_pengembalian`
--

DROP TABLE IF EXISTS `trx_pengembalian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trx_pengembalian` (
  `id_pengembalian` int(11) NOT NULL AUTO_INCREMENT,
  `id_peminjaman` int(11) NOT NULL,
  `status_pengembalian` enum('Dikembalikan','Belum Dikembalikan','Rusak','Hilang') DEFAULT NULL,
  `keterangan` enum('Tepat Waktu','Tidak Tepat Waktu','Bermasalah') DEFAULT NULL,
  `detail_masalah` text DEFAULT NULL,
  PRIMARY KEY (`id_pengembalian`),
  KEY `id_peminjaman` (`id_peminjaman`),
  CONSTRAINT `trx_pengembalian_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `trx_peminjaman` (`id_peminjaman`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_pengembalian`
--

LOCK TABLES `trx_pengembalian` WRITE;
/*!40000 ALTER TABLE `trx_pengembalian` DISABLE KEYS */;
INSERT INTO `trx_pengembalian` VALUES (1,2,NULL,NULL,NULL),(2,3,NULL,NULL,NULL);
/*!40000 ALTER TABLE `trx_pengembalian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trx_pengembalian_tolak`
--

DROP TABLE IF EXISTS `trx_pengembalian_tolak`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trx_pengembalian_tolak` (
  `id_pengembalian_tolak` int(11) NOT NULL AUTO_INCREMENT,
  `id_peminjaman` int(11) NOT NULL,
  `alasan_penolakan` text NOT NULL,
  `tanggal_penolakan` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_pengembalian_tolak`),
  KEY `id_peminjaman` (`id_peminjaman`),
  CONSTRAINT `trx_pengembalian_tolak_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `trx_peminjaman` (`id_peminjaman`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_pengembalian_tolak`
--

LOCK TABLES `trx_pengembalian_tolak` WRITE;
/*!40000 ALTER TABLE `trx_pengembalian_tolak` DISABLE KEYS */;
/*!40000 ALTER TABLE `trx_pengembalian_tolak` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trx_user`
--

DROP TABLE IF EXISTS `trx_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trx_user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_role` int(11) NOT NULL,
  PRIMARY KEY (`id_user`),
  KEY `id_role` (`id_role`),
  CONSTRAINT `trx_user_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `mst_role` (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_user`
--

LOCK TABLES `trx_user` WRITE;
/*!40000 ALTER TABLE `trx_user` DISABLE KEYS */;
INSERT INTO `trx_user` VALUES (6,'furqonfatahillah999@gmail.com','$2y$10$Shs7Errud4hePyn4.Ke/Z.H6kTEPRw3wNVZVhKCvYIrBUhGHy1xxy',3),(12,'nrl.azmi160103@gmail.com','$2y$10$JENJHI1HEJ5xOdNTZDVUKOTBUFprh5nIDWC.OCKgWqoUGEFcc/8RG',1),(22,'akbar@gmail.com','$2y$10$dr0rox81DcM8tZzZwm.FWeOJUTpQ6puBX86cxJX4rfg4MAorflB6S',1),(26,'dewiernitarahma@gmail.com','$2y$10$HB.9TCSY1xOwi8hy0Eh.Cu8BHMKkv8tHdFfmvuIJfokaSs2y3FkL6',7),(27,'julisa@gmail.com','$2y$10$oxn/vy7HVG762.M/y4JTEu73nUrfrpSmy9X7aXBMJXTOepFQ1CEEC',1),(28,'admin@gmail.com','$2y$10$1vrpNVH6REUpkz/PxBMrquGrMMSEXYbobyta8DZUgYo/rPoXYUOFi',7),(29,'ahsan@gmail.com','$2y$10$T9Oek/rxszCN2i2XvcAnD.zYHrwjLan9HYLRZO2lv5DrNNPdVyxnm',7),(30,'andikah3954g@gmail.com','$2y$10$c1u4p2bZnPEBqWFcxDAqVuAvV0mupw/2K.Yy6cCioDZKnhrpKrCz.',7),(31,'cacantik@gmail.com','$2y$10$LPDOT2V5b0vZDmNrvj3DFuM.TtOKzWlz029XBrpZOTq3DLo80olMq',7),(32,'1andikah3954g@gmail.com','$2y$10$DCPkhgCqlUj0sRfQB4t2B.O5Mdq4KWQhZiCKUYvGaaN92uq2WF6we',2),(33,'admin_test@mail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',1),(34,'asisten_test@mail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',4),(35,'user_test@mail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',7);
/*!40000 ALTER TABLE `trx_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trx_data_user`
--

DROP TABLE IF EXISTS `trx_data_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trx_data_user` (
  `id_data_user` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `foto` text DEFAULT NULL,
  `nama_user` varchar(100) NOT NULL,
  `nim_nip` varchar(30) NOT NULL,
  `no_hp_user` varchar(15) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `file_ttd` text DEFAULT NULL,
  PRIMARY KEY (`id_data_user`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `trx_data_user_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `trx_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_data_user`
--

LOCK TABLES `trx_data_user` WRITE;
/*!40000 ALTER TABLE `trx_data_user` DISABLE KEYS */;
INSERT INTO `trx_data_user` VALUES (5,6,'../public/img/foto-profile/user.svg','Furqon Fatahillah','','085240153953','Laki-laki','Borong raya',NULL),(11,12,'../public/img/foto-profile/WhatsApp Image 2024-02-02 at 19.05.56_a1d84076.jpg','Nurul Azmi','','082292704208','Perempuan','pampang',NULL),(21,22,'../public/img/foto-profile/Vectto.jpeg','akbar','','0834326473434','Laki-laki','makassar',NULL),(25,26,'../public/img/foto-profile/f.jpg','Dewi Ernita Rahma','','085216090040','Perempuan','Jl. Kakaktua II',NULL),(26,27,'../public/img/foto-profile/69652cd74c2ce.png','Julisa','13020230219','085216090048','Perempuan','Pampang',NULL),(27,28,'../public/img/foto-profile/','Ahsan','','09090909090','Laki-laki','masalae',NULL),(28,29,'../public/img/foto-profile/','Andi Ahsan','','0912836728938','Laki-laki','nasakkkee',NULL),(29,30,'../public/img/foto-profile/695cba19df719.png','Andi Rahman','','088246700573','Laki-laki','Perumnas BTP Blok H.lama No.509, Tamalanrea, Kec. Tamalanrea, Kota Makassar, Sulawesi Selatan 90245',NULL),(30,31,'../public/img/foto-profile/6964df00b1fd6.jpg','Cacantik','','081374636860','Perempuan','Mars',NULL),(31,32,'../public/img/foto-profile/696bcf369de9c.png','Andi Rifqi Aunur Rahman','13020230219','088246700573','Laki-laki','Perumnas BTP Blok H.lama No.509, Tamalanrea, Kec. Tamalanrea, Kota Makassar, Sulawesi Selatan 90245',NULL),(32,33,'../public/img/foto-profile/user.svg','Admin Test','99999','081234567890','Laki-laki','Makassar',NULL),(33,34,'../public/img/foto-profile/user.svg','Asisten Test','88888','081234567891','Laki-laki','Makassar',NULL),(34,35,'../public/img/foto-profile/user.svg','User Test','77777','081234567892','Perempuan','Makassar',NULL);
/*!40000 ALTER TABLE `trx_data_user` ENABLE KEYS */;
UNLOCK TABLES;


-- SQL untuk menambahkan field baru ke tabel trx_pengembalian
-- Jalankan di phpMyAdmin atau MySQL client

-- 1. Tambah field tanggal pengembalian aktual
ALTER TABLE `trx_pengembalian` 
ADD COLUMN `tgl_pengembalian_aktual` DATE NULL AFTER `id_peminjaman`,
ADD COLUMN `id_petugas` INT(11) NULL AFTER `detail_masalah`,
ADD CONSTRAINT `fk_pengembalian_petugas` 
    FOREIGN KEY (`id_petugas`) REFERENCES `trx_user` (`id_user`) 
    ON DELETE SET NULL;

-- Penjelasan:
-- tgl_pengembalian_aktual: Tanggal barang benar-benar dikembalikan/dicek oleh petugas
-- id_petugas: ID user (Asisten/Korlab) yang menerima pengembalian

--
-- Final view structure for view `detail_barang`
--

/*!50001 DROP VIEW IF EXISTS `detail_barang`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `detail_barang` AS select `b`.`id_barang` AS `id_barang`,`b`.`foto_barang` AS `foto_barang`,`j`.`sub_barang` AS `sub_barang`,`m`.`nama_merek_barang` AS `nama_merek_barang`,`k`.`kondisi_barang` AS `kondisi_barang`,`b`.`jumlah_barang` AS `jumlah_barang`,`s`.`nama_satuan` AS `nama_satuan`,`b`.`spesifikasi_barang` AS `spesifikasi_barang`,`b`.`tgl_pengadaan_barang` AS `tgl_pengadaan_barang`,`b`.`kode_barang` AS `kode_barang`,`b`.`keterangan_label` AS `keterangan_label`,`l`.`nama_lokasi_penyimpanan` AS `nama_lokasi_penyimpanan`,`b`.`deskripsi_detail_lokasi` AS `deskripsi_detail_lokasi`,`st`.`status` AS `status`,`b`.`status_peminjaman` AS `status_peminjaman`,`b`.`qr_code` AS `qr_code` from ((((((`trx_barang` `b` join `mst_jenis_barang` `j` on(`b`.`id_jenis_barang` = `j`.`id_jenis_barang`)) join `mst_merek_barang` `m` on(`b`.`id_merek_barang` = `m`.`id_merek_barang`)) join `mst_satuan` `s` on(`b`.`id_satuan` = `s`.`id_satuan`)) join `mst_kondisi_barang` `k` on(`b`.`id_kondisi_barang` = `k`.`id_kondisi_barang`)) join `mst_lokasi_penyimpanan` `l` on(`b`.`id_lokasi_penyimpanan` = `l`.`id_lokasi_penyimpanan`)) join `mst_status` `st` on(`b`.`id_status` = `st`.`id_status`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-18  4:32:51
