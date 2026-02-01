-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: inventori_db12
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_jenis_barang`
--

LOCK TABLES `mst_jenis_barang` WRITE;
/*!40000 ALTER TABLE `mst_jenis_barang` DISABLE KEYS */;
INSERT INTO `mst_jenis_barang` VALUES (3,'Keyboard','C','KY1','C/KY1'),(4,'Headset','A','HS1','A/HS1'),(5,'Laptop','C','LP1','C/LP1');
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_merek_barang`
--

LOCK TABLES `mst_merek_barang` WRITE;
/*!40000 ALTER TABLE `mst_merek_barang` DISABLE KEYS */;
INSERT INTO `mst_merek_barang` VALUES (3,'ASUS','201'),(4,'Lenovo','301'),(5,'HP','401');
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
-- Table structure for table `mst_spesifikasi`
--

DROP TABLE IF EXISTS `mst_spesifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_spesifikasi` (
  `id_spesifikasi` int(11) NOT NULL AUTO_INCREMENT,
  `spesifikasi_barang` varchar(255) NOT NULL,
  `foto_barang` text NOT NULL,
  `id_jenis_barang` int(11) NOT NULL,
  `id_merek_barang` int(11) NOT NULL,
  `id_satuan` int(11) NOT NULL,
  `qr_code_spesifikasi` text DEFAULT NULL,
  `kode_barang` varchar(50) DEFAULT NULL COMMENT 'Contoh: 2026/01/C/LP1/401',
  `jumlah_total` int(11) DEFAULT 0 COMMENT 'Total barang dalam batch ini',
  PRIMARY KEY (`id_spesifikasi`),
  KEY `fk_spek_jenis` (`id_jenis_barang`),
  KEY `fk_spek_merek` (`id_merek_barang`),
  KEY `fk_spek_satuan` (`id_satuan`),
  CONSTRAINT `fk_spek_jenis` FOREIGN KEY (`id_jenis_barang`) REFERENCES `mst_jenis_barang` (`id_jenis_barang`),
  CONSTRAINT `fk_spek_merek` FOREIGN KEY (`id_merek_barang`) REFERENCES `mst_merek_barang` (`id_merek_barang`),
  CONSTRAINT `fk_spek_satuan` FOREIGN KEY (`id_satuan`) REFERENCES `mst_satuan` (`id_satuan`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_spesifikasi`
--

LOCK TABLES `mst_spesifikasi` WRITE;
/*!40000 ALTER TABLE `mst_spesifikasi` DISABLE KEYS */;
INSERT INTO `mst_spesifikasi` VALUES (2,'Keyboard ASUS Series-9962','../public/img/foto-barang/new_2.png',3,3,5,NULL,NULL,0),(3,'Headset Lenovo Series-7685','../public/img/foto-barang/new_3.png',4,4,1,NULL,NULL,0),(4,'Headset HP Series-4195','../public/img/foto-barang/new_4.png',4,5,1,NULL,NULL,0),(5,'Headset HP Series-7966','../public/img/foto-barang/new_5.png',4,5,1,NULL,NULL,0),(6,'Headset ASUS Series-4764','../public/img/foto-barang/new_6.png',4,3,1,NULL,NULL,0),(7,'Laptop HP Series-8625','../public/img/foto-barang/new_7.png',5,5,5,NULL,NULL,0),(8,'Keyboard ASUS Series-1166','../public/img/foto-barang/new_8.png',3,3,1,NULL,NULL,0),(9,'Keyboard Lenovo Series-3029','../public/img/foto-barang/new_9.png',3,4,5,NULL,NULL,0),(10,'Headset ASUS Series-3302','../public/img/foto-barang/new_10.png',4,3,3,NULL,NULL,0),(16,'Keyboard 1','../public/img/foto-barang/697cc22be4b2c_Group 19.png',5,5,1,'../public/img/qr-code/MASTER_SPEK_697cc22beda31.png',NULL,0),(18,'Keyboard 12','../public/img/foto-barang/697cec0fdeaf8_vlcsnap-2026-01-18-14h48m23s669.png',3,3,1,'../public/img/qr-code/MASTER_SPEK_UPD_697da91140263.png','2026/01/C/KY1/201',30);
/*!40000 ALTER TABLE `mst_spesifikasi` ENABLE KEYS */;
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
  `id_spesifikasi` int(11) NOT NULL,
  `id_kondisi_barang` int(11) DEFAULT NULL,
  `tgl_pengadaan_barang` date NOT NULL,
  `keterangan_label` enum('Sudah','Belum') NOT NULL,
  `id_lokasi_penyimpanan` int(11) DEFAULT NULL,
  `deskripsi_detail_lokasi` text DEFAULT NULL,
  `id_status` int(11) DEFAULT NULL,
  `status_peminjaman` enum('Bisa','Tidak Bisa') NOT NULL,
  `qr_code` text NOT NULL,
  `urutan_unit` int(11) DEFAULT 1 COMMENT 'Barang ke-1, ke-2, dst',
  PRIMARY KEY (`id_barang`),
  KEY `id_kondisi_barang` (`id_kondisi_barang`),
  KEY `id_lokasi_penyimpanan` (`id_lokasi_penyimpanan`),
  KEY `id_status` (`id_status`),
  KEY `fk_trx_spek` (`id_spesifikasi`),
  CONSTRAINT `fk_trx_spek` FOREIGN KEY (`id_spesifikasi`) REFERENCES `mst_spesifikasi` (`id_spesifikasi`),
  CONSTRAINT `trx_barang_ibfk_3` FOREIGN KEY (`id_kondisi_barang`) REFERENCES `mst_kondisi_barang` (`id_kondisi_barang`),
  CONSTRAINT `trx_barang_ibfk_5` FOREIGN KEY (`id_lokasi_penyimpanan`) REFERENCES `mst_lokasi_penyimpanan` (`id_lokasi_penyimpanan`),
  CONSTRAINT `trx_barang_ibfk_6` FOREIGN KEY (`id_status`) REFERENCES `mst_status` (`id_status`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_barang`
--

LOCK TABLES `trx_barang` WRITE;
/*!40000 ALTER TABLE `trx_barang` DISABLE KEYS */;
INSERT INTO `trx_barang` VALUES (2,2,1,'2026-01-22','Sudah',6,'Pengadaan Baru',5,'Bisa','../public/img/qr-code/new_2.png',1),(3,3,2,'2026-01-22','Sudah',10,'Pengadaan Baru',5,'Bisa','../public/img/qr-code/new_3.png',1),(4,4,1,'2026-01-22','Sudah',16,'Pengadaan Baru',3,'Bisa','../public/img/qr-code/new_4.png',1),(5,5,4,'2026-01-22','Sudah',12,'Pengadaan Baru',3,'Tidak Bisa','../public/img/qr-code/new_5.png',1),(6,6,5,'2026-01-22','Belum',6,'Pengadaan Baru',3,'Tidak Bisa','../public/img/qr-code/new_6.png',1),(7,7,1,'2026-01-22','Sudah',8,'Pengadaan Baru',5,'Bisa','../public/img/qr-code/new_7.png',1),(8,8,4,'2026-01-22','Belum',12,'Pengadaan Baru',5,'Tidak Bisa','../public/img/qr-code/new_8.png',1),(9,9,1,'2026-01-22','Belum',18,'Pengadaan Baru',5,'Tidak Bisa','../public/img/qr-code/new_9.png',1),(10,10,1,'2026-01-22','Sudah',4,'Pengadaan Baru',3,'Bisa','../public/img/qr-code/new_10.png',1),(11,16,1,'2026-01-30','Belum',18,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cc22c9983e.png',1),(12,16,1,'2026-01-30','Belum',18,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cc22d51424.png',1),(13,16,1,'2026-01-30','Belum',18,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cc22d78650.png',1),(15,18,1,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec0fe62fc.png',1),(16,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec1015dac.png',2),(17,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec10344b3.png',3),(18,18,1,'2026-01-30','Sudah',13,'wasdfasdf',1,'Tidak Bisa','../public/img/qr-code/UNIT_697cec104c318.png',4),(19,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec106e30a.png',5),(20,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec1090481.png',6),(21,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec10b8795.png',7),(22,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec10db60c.png',8),(23,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec1106c34.png',9),(24,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec1128985.png',10),(25,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec114bac1.png',11),(26,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec116d4da.png',12),(27,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec118cda6.png',13),(28,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec11aca8b.png',14),(29,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec11c6f90.png',15),(30,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec11ded93.png',16),(31,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec120dc3d.png',17),(32,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec122dc8f.png',18),(33,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec1253407.png',19),(34,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec1273ca0.png',20),(35,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec1292d65.png',21),(36,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec12b44a3.png',22),(37,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec12d9b13.png',23),(38,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec130eb5d.png',24),(39,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec133b876.png',25),(40,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec135715b.png',26),(41,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec1376f41.png',27),(42,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec139ac33.png',28),(43,18,3,'2026-01-30','Sudah',13,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_697cec13c29c4.png',29),(44,18,1,'2026-01-30','Sudah',12,'wasdfasdf',5,'Bisa','../public/img/qr-code/UNIT_UPD_697da910962de.png',30);
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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_data_user`
--

LOCK TABLES `trx_data_user` WRITE;
/*!40000 ALTER TABLE `trx_data_user` DISABLE KEYS */;
INSERT INTO `trx_data_user` VALUES (1,1,'../public/img/foto-profile/user.svg','Kepala Lab','001','081234567001','Laki-laki','Makassar',NULL),(2,2,'../public/img/foto-profile/user.svg','Laboran','002','081234567002','Perempuan','Makassar',NULL),(3,3,'../public/img/foto-profile/user.svg','Koordinator Lab','003','081234567003','Laki-laki','Makassar',NULL),(4,4,'../public/img/foto-profile/user.svg','Asisten Lab','004','081234567004','Perempuan','Makassar',NULL),(5,5,'../public/img/foto-profile/user.svg','Calon Asisten','005','081234567005','Laki-laki','Makassar',NULL),(6,6,'../public/img/foto-profile/user.svg','Calon CA','006','081234567006','Perempuan','Makassar',NULL),(7,7,'../public/img/foto-profile/user.svg','Mahasiswa','007','081234567007','Laki-laki','Makassar',NULL),(35,36,'../public/img/foto-profile/697d7ea0734ac.png','Andi Rifqi Aunur Rahman','13020230219','088246700573','Laki-laki','Perumnas BTP Blok H.lama No.509, Tamalanrea, Kec. Tamalanrea, Kota Makassar, Sulawesi Selatan 90245',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_detail_peminjaman`
--

LOCK TABLES `trx_detail_peminjaman` WRITE;
/*!40000 ALTER TABLE `trx_detail_peminjaman` DISABLE KEYS */;
INSERT INTO `trx_detail_peminjaman` VALUES (4,1,3,2,1,NULL),(5,1,5,7,1,NULL),(6,1,3,2,1,NULL),(7,2,4,4,1,NULL),(8,3,3,15,1,NULL),(9,4,3,18,2,NULL),(10,5,3,18,2,NULL),(11,6,3,18,1,NULL);
/*!40000 ALTER TABLE `trx_detail_peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trx_detail_pengembalian`
--

DROP TABLE IF EXISTS `trx_detail_pengembalian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trx_detail_pengembalian` (
  `id_detail_pengembalian` int(11) NOT NULL AUTO_INCREMENT,
  `id_pengembalian` int(11) NOT NULL,
  `id_detail_peminjaman` int(11) NOT NULL,
  `jumlah_kembali` int(11) NOT NULL,
  `kondisi_barang` enum('Baik','Rusak','Hilang') NOT NULL,
  `keterangan_kondisi` text DEFAULT NULL,
  PRIMARY KEY (`id_detail_pengembalian`),
  KEY `idx_pengembalian` (`id_pengembalian`),
  KEY `idx_detail_peminjaman` (`id_detail_peminjaman`),
  CONSTRAINT `fk_detail_kembali_header` FOREIGN KEY (`id_pengembalian`) REFERENCES `trx_pengembalian` (`id_pengembalian`) ON DELETE CASCADE,
  CONSTRAINT `fk_detail_kembali_pinjam` FOREIGN KEY (`id_detail_peminjaman`) REFERENCES `trx_detail_peminjaman` (`id_detail`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_detail_pengembalian`
--

LOCK TABLES `trx_detail_pengembalian` WRITE;
/*!40000 ALTER TABLE `trx_detail_pengembalian` DISABLE KEYS */;
INSERT INTO `trx_detail_pengembalian` VALUES (1,3,5,1,'Baik',''),(2,3,6,1,'Rusak',''),(3,2,11,1,'Baik','');
/*!40000 ALTER TABLE `trx_detail_pengembalian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trx_pemeriksa_pengembalian`
--

DROP TABLE IF EXISTS `trx_pemeriksa_pengembalian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trx_pemeriksa_pengembalian` (
  `id_pemeriksa` int(11) NOT NULL AUTO_INCREMENT,
  `id_pengembalian` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `waktu_periksa` timestamp NOT NULL DEFAULT current_timestamp(),
  `bukti_foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_pemeriksa`),
  KEY `fk_cek_pengem` (`id_pengembalian`),
  KEY `fk_cek_user` (`id_user`),
  CONSTRAINT `fk_cek_pengem` FOREIGN KEY (`id_pengembalian`) REFERENCES `trx_pengembalian` (`id_pengembalian`) ON DELETE CASCADE,
  CONSTRAINT `fk_cek_user` FOREIGN KEY (`id_user`) REFERENCES `trx_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_pemeriksa_pengembalian`
--

LOCK TABLES `trx_pemeriksa_pengembalian` WRITE;
/*!40000 ALTER TABLE `trx_pemeriksa_pengembalian` DISABLE KEYS */;
INSERT INTO `trx_pemeriksa_pengembalian` VALUES (1,2,4,'2026-02-01 18:03:12',NULL),(2,2,4,'2026-02-01 19:34:47','uploads/pengembalian/697faad7151f1_Unit_1.png');
/*!40000 ALTER TABLE `trx_pemeriksa_pengembalian` ENABLE KEYS */;
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
  `keterangan_tolak` text DEFAULT NULL,
  `status` enum('Diproses','Disetujui','Tolak Peminjaman','Dikembalikan','Melengkapi Surat','Tolak Pengembalian') DEFAULT 'Melengkapi Surat',
  `file_surat` varchar(255) DEFAULT NULL,
  `validasi_kalab` enum('0','1') DEFAULT '0' COMMENT '0=Belum, 1=Sudah (Huzain)',
  `validasi_laboran` enum('0','1') DEFAULT '0' COMMENT '0=Belum, 1=Sudah (Fatimah)',
  PRIMARY KEY (`id_peminjaman`),
  KEY `fk_peminjaman_user` (`id_user`),
  CONSTRAINT `fk_peminjaman_user` FOREIGN KEY (`id_user`) REFERENCES `trx_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_peminjaman`
--

LOCK TABLES `trx_peminjaman` WRITE;
/*!40000 ALTER TABLE `trx_peminjaman` DISABLE KEYS */;
INSERT INTO `trx_peminjaman` VALUES (1,27,'qasd','2026-01-25','2026-01-25','2026-01-25','12312312','','Tolak Peminjaman','SIGNED_6976110029a5f.pdf','0','0'),(2,1,'COBA AJA Admin','2026-01-31','2026-01-22','2026-01-22','-',NULL,'Diproses','SIGNED_697da1131070f.pdf','0','0'),(3,1,'COBA AJA Admin','2026-02-01','2026-01-22','2026-01-22','-',NULL,'Melengkapi Surat',NULL,'0','0'),(4,1,'COBA AJA Admin','2026-02-01','2026-01-22','2026-01-22','-',NULL,'Melengkapi Surat',NULL,'0','0'),(5,1,'COBA AJA Adminytfydhghf','2026-02-01','2026-01-22','2026-01-22','-',NULL,'Disetujui','SIGNED_697f8012908d4.pdf','1','1'),(6,4,'COBA AJA Adminhhghghgqweqweq','2026-02-01','2026-01-22','2026-01-22','-',NULL,'Disetujui','SIGNED_697f92037900c.pdf','1','1');
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
  `tgl_pengembalian_aktual` date DEFAULT NULL,
  `status_pengembalian` enum('Selesai Periksa','Periksa','Periksa Ulang') DEFAULT NULL,
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
INSERT INTO `trx_pengembalian` VALUES (2,6,'2026-02-01','Selesai Periksa','','');
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
  `email_verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=Belum Verifikasi, 1=Sudah Verifikasi',
  `verification_token` varchar(64) DEFAULT NULL COMMENT 'Token untuk verifikasi email',
  `token_expiry` datetime DEFAULT NULL COMMENT 'Waktu expired token verifikasi',
  PRIMARY KEY (`id_user`),
  KEY `id_role` (`id_role`),
  CONSTRAINT `trx_user_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `mst_role` (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_user`
--

LOCK TABLES `trx_user` WRITE;
/*!40000 ALTER TABLE `trx_user` DISABLE KEYS */;
INSERT INTO `trx_user` VALUES (1,'keplab@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',1,1,NULL,NULL),(2,'laboran@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',2,1,NULL,NULL),(3,'korlab@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',3,1,NULL,NULL),(4,'asisten@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',4,1,NULL,NULL),(5,'ca@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',5,1,NULL,NULL),(6,'cca@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',6,1,NULL,NULL),(7,'mhs@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',7,1,NULL,NULL),(36,'andikah3954g@gmail.com','$2y$10$NMLn4c7wXE0Zwwr2K0BnE.hEktDu5C.fc1MDujgnaLxJgbdkEXOEG',7,1,NULL,NULL);
/*!40000 ALTER TABLE `trx_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-02  4:12:13
