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
 1 AS `deskripsi_barang`,
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_jenis_barang`
--

LOCK TABLES `mst_jenis_barang` WRITE;
/*!40000 ALTER TABLE `mst_jenis_barang` DISABLE KEYS */;
INSERT INTO `mst_jenis_barang` VALUES (1,'monitor','C','MOZ','C/MOZ'),(2,'keyboard','C','KEZ','C/KEZ'),(4,'laptop','C','LAP','C/LAP'),(8,'Pads','C','asa','C/asa');
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_merek_barang`
--

LOCK TABLES `mst_merek_barang` WRITE;
/*!40000 ALTER TABLE `mst_merek_barang` DISABLE KEYS */;
INSERT INTO `mst_merek_barang` VALUES (2,'fantech','002'),(5,'hp','004'),(6,'NoBrand','001'),(7,'Logitech','003'),(8,'Samsung','005'),(9,'Intel','006'),(15,'IPON','101');
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
  `deskripsi_barang` text DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_barang`
--

LOCK TABLES `trx_barang` WRITE;
/*!40000 ALTER TABLE `trx_barang` DISABLE KEYS */;
INSERT INTO `trx_barang` VALUES (80,'../public/img/foto-barang/MacBook Pro 16 (1).png',1,5,5,1,1,'','2024-06-11','Sudah',5,'meja 7',5,'Bisa','2024/VI/C/MON/004/1/10','../public/img/qr-code/code_666a5b9421738.png'),(81,'../public/img/foto-barang/logho.jpg',2,2,1,0,7,'','2025-02-11','Sudah',6,'',5,'Bisa','2025/II/C/MON/004/6/7','../public/img/qr-code/code_67a9d456934ef.png');
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
  `no_hp_user` varchar(15) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `alamat` varchar(100) NOT NULL,
  PRIMARY KEY (`id_data_user`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `trx_data_user_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `trx_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_data_user`
--

LOCK TABLES `trx_data_user` WRITE;
/*!40000 ALTER TABLE `trx_data_user` DISABLE KEYS */;
INSERT INTO `trx_data_user` VALUES (5,6,'../public/img/foto-profile/user.svg','Furqon Fatahillah','085240153953','Laki-laki','Borong raya'),(11,12,'../public/img/foto-profile/WhatsApp Image 2024-02-02 at 19.05.56_a1d84076.jpg','Nurul Azmi','082292704208','Perempuan','pampang'),(21,22,'../public/img/foto-profile/Vectto.jpeg','akbar','0834326473434','Laki-laki','makassar'),(25,26,'../public/img/foto-profile/f.jpg','Dewi Ernita Rahma','085216090040','Perempuan','Jl. Kakaktua II'),(26,27,'../public/img/foto-profile/Picture1 biru.png','Julisa','085216090048','Perempuan','Pampang'),(27,28,'../public/img/foto-profile/','Ahsan','09090909090','Laki-laki','masalae'),(28,29,'../public/img/foto-profile/','Andi Ahsan','0912836728938','Laki-laki','nasakkkee'),(29,30,'../public/img/foto-profile/695cba19df719.png','Andi Rahman','088246700573','Laki-laki','Perumnas BTP Blok H.lama No.509, Tamalanrea, Kec. Tamalanrea, Kota Makassar, Sulawesi Selatan 90245');
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
  `jumlah` int(11) NOT NULL,
  `keterangan_barang` text DEFAULT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `id_peminjaman` (`id_peminjaman`),
  KEY `id_jenis_barang` (`id_jenis_barang`),
  CONSTRAINT `trx_detail_peminjaman_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `trx_peminjaman` (`id_peminjaman`) ON DELETE CASCADE,
  CONSTRAINT `trx_detail_peminjaman_ibfk_2` FOREIGN KEY (`id_jenis_barang`) REFERENCES `mst_jenis_barang` (`id_jenis_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_detail_peminjaman`
--

LOCK TABLES `trx_detail_peminjaman` WRITE;
/*!40000 ALTER TABLE `trx_detail_peminjaman` DISABLE KEYS */;
INSERT INTO `trx_detail_peminjaman` VALUES (1,32,1,1,NULL),(2,32,2,1,NULL),(5,33,2,1,NULL),(6,33,4,1,NULL),(7,33,8,1,NULL);
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
  `nama_peminjam` varchar(255) NOT NULL,
  `judul_kegiatan` varchar(255) NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `tanggal_peminjaman` date NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `keterangan_peminjaman` text DEFAULT NULL,
  `status` enum('Diproses','Disetujui','Ditolak','Dikembalikan','Melengkapi Surat') DEFAULT 'Melengkapi Surat',
  `file_surat` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_peminjaman`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_peminjaman`
--

LOCK TABLES `trx_peminjaman` WRITE;
/*!40000 ALTER TABLE `trx_peminjaman` DISABLE KEYS */;
INSERT INTO `trx_peminjaman` VALUES (6,'ega ','omoo','2025-02-10','2025-02-26','2025-03-08','Dipinjam pak pulici','Diproses',NULL),(7,'Anggi','mudah mudah','2025-02-11','2025-02-12','2025-02-19','hehe','Dikembalikan',NULL),(11,'SpongeBob ','mudah mudah','2025-02-15','2025-02-21','2025-02-26','[DITOLAK] cara mu tidak bagus','Ditolak',NULL),(14,'Saya','mudah mudah','2025-02-16','2025-02-20','2025-02-27','hehe','Disetujui',NULL),(15,'Ahsan','TUBES MICRO','2025-12-19','2025-12-19','2025-12-26','Blabla','Dikembalikan',NULL),(16,'ahsanos','mudah mudah','2025-12-19','2025-12-19','2025-12-26','zasa','Diproses',NULL),(17,'Mahasiswa','mencoba','2026-01-04','2026-01-01','2026-01-05','','Diproses',NULL),(18,'Mahasiswa','mencoba','2026-01-04','2026-01-01','2026-01-05','','Diproses',NULL),(19,'Mahasiswa','mencoba','2026-01-04','2026-01-01','2026-01-05','','Diproses',NULL),(20,'Mahasiswa','kk','2026-01-04','2026-01-10','2026-01-21','','Diproses',NULL),(21,'Mahasiswa','mencoba','2026-01-06','2026-01-06','2026-01-15','','Diproses',NULL),(22,'Mahasiswa','mencoba','2026-01-06','2026-01-06','2026-01-15','','Diproses',NULL),(23,'Mahasiswa','kk','2026-01-06','2026-01-10','2026-01-21','','Melengkapi Surat',NULL),(24,'Andi Rahman','kk','2026-01-06','2026-01-10','2026-01-21','','Diproses','SIGNED_695e494559b9f.pdf'),(32,'User','COBA AJA','2026-01-07','2026-01-07','2026-01-29',', ','Melengkapi Surat',NULL),(33,'Andi Rahman','COBA AJA','2026-01-07','2026-01-07','2026-01-29','-, -, ','Diproses','SIGNED_695fa9d03c6ce.pdf');
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_pengembalian`
--

LOCK TABLES `trx_pengembalian` WRITE;
/*!40000 ALTER TABLE `trx_pengembalian` DISABLE KEYS */;
INSERT INTO `trx_pengembalian` VALUES (16,14,NULL,NULL,NULL);
/*!40000 ALTER TABLE `trx_pengembalian` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_user`
--

LOCK TABLES `trx_user` WRITE;
/*!40000 ALTER TABLE `trx_user` DISABLE KEYS */;
INSERT INTO `trx_user` VALUES (6,'furqonfatahillah999@gmail.com','$2y$10$Shs7Errud4hePyn4.Ke/Z.H6kTEPRw3wNVZVhKCvYIrBUhGHy1xxy',3),(12,'nrl.azmi160103@gmail.com','$2y$10$JENJHI1HEJ5xOdNTZDVUKOTBUFprh5nIDWC.OCKgWqoUGEFcc/8RG',1),(22,'akbar@gmail.com','$2y$10$dr0rox81DcM8tZzZwm.FWeOJUTpQ6puBX86cxJX4rfg4MAorflB6S',1),(26,'dewiernitarahma@gmail.com','$2y$10$HB.9TCSY1xOwi8hy0Eh.Cu8BHMKkv8tHdFfmvuIJfokaSs2y3FkL6',7),(27,'julisa@gmail.com','$2y$10$oxn/vy7HVG762.M/y4JTEu73nUrfrpSmy9X7aXBMJXTOepFQ1CEEC',1),(28,'admin@gmail.com','$2y$10$1vrpNVH6REUpkz/PxBMrquGrMMSEXYbobyta8DZUgYo/rPoXYUOFi',7),(29,'ahsan@gmail.com','$2y$10$T9Oek/rxszCN2i2XvcAnD.zYHrwjLan9HYLRZO2lv5DrNNPdVyxnm',7),(30,'andikah3954g@gmail.com','$2y$10$c1u4p2bZnPEBqWFcxDAqVuAvV0mupw/2K.Yy6cCioDZKnhrpKrCz.',7);
/*!40000 ALTER TABLE `trx_user` ENABLE KEYS */;
UNLOCK TABLES;

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
/*!50001 VIEW `detail_barang` AS select `trx_barang`.`id_barang` AS `id_barang`,`trx_barang`.`foto_barang` AS `foto_barang`,`mst_jenis_barang`.`sub_barang` AS `sub_barang`,`mst_merek_barang`.`nama_merek_barang` AS `nama_merek_barang`,`mst_kondisi_barang`.`kondisi_barang` AS `kondisi_barang`,`trx_barang`.`jumlah_barang` AS `jumlah_barang`,`mst_satuan`.`nama_satuan` AS `nama_satuan`,`trx_barang`.`deskripsi_barang` AS `deskripsi_barang`,`trx_barang`.`tgl_pengadaan_barang` AS `tgl_pengadaan_barang`,`trx_barang`.`kode_barang` AS `kode_barang`,`trx_barang`.`keterangan_label` AS `keterangan_label`,`mst_lokasi_penyimpanan`.`nama_lokasi_penyimpanan` AS `nama_lokasi_penyimpanan`,`trx_barang`.`deskripsi_detail_lokasi` AS `deskripsi_detail_lokasi`,`mst_status`.`status` AS `status`,`trx_barang`.`status_peminjaman` AS `status_peminjaman`,`trx_barang`.`qr_code` AS `qr_code` from ((((((`trx_barang` join `mst_jenis_barang` on(`trx_barang`.`id_jenis_barang` = `mst_jenis_barang`.`id_jenis_barang`)) join `mst_merek_barang` on(`trx_barang`.`id_merek_barang` = `mst_merek_barang`.`id_merek_barang`)) join `mst_satuan` on(`trx_barang`.`id_satuan` = `mst_satuan`.`id_satuan`)) join `mst_kondisi_barang` on(`trx_barang`.`id_kondisi_barang` = `mst_kondisi_barang`.`id_kondisi_barang`)) join `mst_lokasi_penyimpanan` on(`trx_barang`.`id_lokasi_penyimpanan` = `mst_lokasi_penyimpanan`.`id_lokasi_penyimpanan`)) join `mst_status` on(`trx_barang`.`id_status` = `mst_status`.`id_status`)) */;
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

-- Dump completed on 2026-01-09 19:50:58