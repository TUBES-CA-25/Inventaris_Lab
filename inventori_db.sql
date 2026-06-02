-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: inventori_db13
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_jenis_barang`
--

LOCK TABLES `mst_jenis_barang` WRITE;
/*!40000 ALTER TABLE `mst_jenis_barang` DISABLE KEYS */;
INSERT INTO `mst_jenis_barang` VALUES (9,'Baterai','M','BAT','M/BAT'),(10,'Micro Servo','M','MIC','M/MIC'),(11,'Raspberry','M','RAS','M/RAS'),(12,'Keyboard','C','KEY','C/KEY'),(13,'Kabel Power','C','KAB','C/KAB'),(14,'Terminal','C','TER','C/TER'),(15,'Kursi','F','KUR','F/KUR'),(16,'Monitor','C','MON','C/MON'),(17,'TV','F','TVX','F/TVX'),(18,'Mousepad','C','MOU','C/MOU'),(19,'CCTV','S','CCT','S/CCT');
/*!40000 ALTER TABLE `mst_jenis_barang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mst_jenis_peminjaman`
--

DROP TABLE IF EXISTS `mst_jenis_peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_jenis_peminjaman` (
  `id_jenis_peminjaman` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jenis_peminjaman` varchar(50) NOT NULL,
  PRIMARY KEY (`id_jenis_peminjaman`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_jenis_peminjaman`
--

LOCK TABLES `mst_jenis_peminjaman` WRITE;
/*!40000 ALTER TABLE `mst_jenis_peminjaman` DISABLE KEYS */;
INSERT INTO `mst_jenis_peminjaman` VALUES (1,'Akademik'),(2,'Internal');
/*!40000 ALTER TABLE `mst_jenis_peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mst_keterangan_pengembalian`
--

DROP TABLE IF EXISTS `mst_keterangan_pengembalian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_keterangan_pengembalian` (
  `id_keterangan_pengembalian` int(11) NOT NULL AUTO_INCREMENT,
  `nama_keterangan` varchar(50) NOT NULL,
  PRIMARY KEY (`id_keterangan_pengembalian`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_keterangan_pengembalian`
--

LOCK TABLES `mst_keterangan_pengembalian` WRITE;
/*!40000 ALTER TABLE `mst_keterangan_pengembalian` DISABLE KEYS */;
INSERT INTO `mst_keterangan_pengembalian` VALUES (1,'Tepat Waktu'),(2,'Tidak Tepat Waktu'),(3,'Bermasalah');
/*!40000 ALTER TABLE `mst_keterangan_pengembalian` ENABLE KEYS */;
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
-- Table structure for table `mst_kondisi_pengembalian`
--

DROP TABLE IF EXISTS `mst_kondisi_pengembalian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_kondisi_pengembalian` (
  `id_kondisi_pengembalian` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kondisi` varchar(50) NOT NULL,
  PRIMARY KEY (`id_kondisi_pengembalian`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_kondisi_pengembalian`
--

LOCK TABLES `mst_kondisi_pengembalian` WRITE;
/*!40000 ALTER TABLE `mst_kondisi_pengembalian` DISABLE KEYS */;
INSERT INTO `mst_kondisi_pengembalian` VALUES (1,'Baik'),(2,'Rusak'),(3,'Hilang');
/*!40000 ALTER TABLE `mst_kondisi_pengembalian` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_merek_barang`
--

LOCK TABLES `mst_merek_barang` WRITE;
/*!40000 ALTER TABLE `mst_merek_barang` DISABLE KEYS */;
INSERT INTO `mst_merek_barang` VALUES (6,'No Brand','000'),(7,'Tower Pro','223'),(8,'Raspberry','048'),(9,'Logitech','004'),(10,'Futura','173'),(11,'Dell','020'),(12,'TCL','166'),(13,'Fantech','164'),(14,'Hikvision','037');
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

INSERT INTO `mst_role` VALUES (1,'KEPLAB'),(2,'LABORAN'),(3,'KORLAB'),(4,'ASISTEN'),(5,'DOSEN'),(6,'MHS');

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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_spesifikasi`
--

LOCK TABLES `mst_spesifikasi` WRITE;
/*!40000 ALTER TABLE `mst_spesifikasi` DISABLE KEYS */;
INSERT INTO `mst_spesifikasi` VALUES (27,'9v','../public/img/foto-barang/69820fed86bbf_Bateria Pilha 9V 6F22m Hw Hi-Watt.jpg',9,6,1,'../public/img/qr-code/MASTER_69820fefe53ae.png','2026/II/M/BAT/000',22),(28,'SG90','../public/img/foto-barang/698210a9c57cf_Buy SG-90 Micro Servo _Connector Wire Length 150MM….jpg',10,7,1,'../public/img/qr-code/MASTER_698210aad43db.png','2026/II/M/MIC/223',10),(29,'Pi B+ V1.2 (3)','../public/img/foto-barang/6982122374fdf_Raspberry Pi 4 Pinout, Specifications and Applications.jpg',11,8,1,'../public/img/qr-code/MASTER_698212254260c.png','2026/II/M/RAS/048',11),(30,'-','../public/img/foto-barang/698212d329f39_Check out this listing I just found on Poshmark….jpg',12,9,1,'../public/img/qr-code/MASTER_698212d710084.png','2026/II/C/KEY/004',36),(31,'Kabel power monitor','../public/img/foto-barang/698213744c29e_BARANG SELALU READY STOCK SESUAI IKLAN!,_JIKA ADA….jpg',13,6,1,'../public/img/qr-code/MASTER_SPEK_UPD_6982c7758fe32.png','2026/II/C/KAB/000',36),(32,'Terminal 3 mata','../public/img/foto-barang/69821425cf804_Uticon Stop Kontak _Terminal Kuningan 3 Mata 10A-250V.jpg',14,6,1,'../public/img/qr-code/MASTER_69821425e56e6.png','2026/II/C/TER/000',1),(33,'-','../public/img/foto-barang/698214dd57f39_Jual kursi susun murah _ kursi pesta, kursi susun….jpg',15,10,1,'../public/img/qr-code/MASTER_698214dd70e34.png','2026/II/F/KUR/173',1),(34,'-','../public/img/foto-barang/698215624eb05_Dell P2012Ht 20_ LCD Flat Panel Monitor Display….jpg',16,11,1,'../public/img/qr-code/MASTER_698215626529a.png','2026/II/C/MON/020',1),(35,'-','../public/img/foto-barang/69821647a8f0c_Ideal for small spaces like kitchens, offices and….jpg',17,12,1,'../public/img/qr-code/MASTER_69821647e48a1.png','2026/II/F/TVX/166',2),(36,'-','../public/img/foto-barang/698216f728a88_FANTECH – grand tapis de souris de jeu MP44, 440mm….jpg',18,13,1,'../public/img/qr-code/MASTER_698216f904da7.png','2026/II/C/MOU/164',25),(37,'-','../public/img/foto-barang/69821794e0c03_Camera IP CCTV Hikvision DS-2CD1123G0E-I 2MP 2_8MM….jpg',19,14,1,'../public/img/qr-code/MASTER_6982179515b86.png','2026/II/S/CCT/037',1);
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
-- Table structure for table `mst_status_peminjaman`
--

DROP TABLE IF EXISTS `mst_status_peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_status_peminjaman` (
  `id_status_peminjaman` int(11) NOT NULL AUTO_INCREMENT,
  `nama_status` varchar(50) NOT NULL,
  PRIMARY KEY (`id_status_peminjaman`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_status_peminjaman`
--

LOCK TABLES `mst_status_peminjaman` WRITE;
/*!40000 ALTER TABLE `mst_status_peminjaman` DISABLE KEYS */;
INSERT INTO `mst_status_peminjaman` VALUES (1,'Melengkapi Surat'),(2,'Diproses'),(3,'Disetujui'),(4,'Tolak Peminjaman'),(5,'Dikembalikan'),(6,'Tolak Pengembalian');
/*!40000 ALTER TABLE `mst_status_peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mst_status_pengembalian`
--

DROP TABLE IF EXISTS `mst_status_pengembalian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_status_pengembalian` (
  `id_status_pengembalian` int(11) NOT NULL AUTO_INCREMENT,
  `nama_status_pengembalian` varchar(50) NOT NULL,
  PRIMARY KEY (`id_status_pengembalian`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_status_pengembalian`
--

LOCK TABLES `mst_status_pengembalian` WRITE;
/*!40000 ALTER TABLE `mst_status_pengembalian` DISABLE KEYS */;
INSERT INTO `mst_status_pengembalian` VALUES (1,'Selesai Periksa'),(2,'Periksa'),(3,'Periksa Ulang');
/*!40000 ALTER TABLE `mst_status_pengembalian` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=275 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_barang`
--

LOCK TABLES `trx_barang` WRITE;
/*!40000 ALTER TABLE `trx_barang` DISABLE KEYS */;
INSERT INTO `trx_barang` VALUES (129,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',1,'Tidak Bisa','../public/img/qr-code/UNIT_69820fed89846.png',1),(130,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',1,'Tidak Bisa','../public/img/qr-code/UNIT_69820fede55a6.png',2),(131,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820fee0dc51.png',3),(132,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820fee25652.png',4),(133,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820fee3d202.png',5),(134,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820fee58836.png',6),(135,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820fee72895.png',7),(136,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820fee8d760.png',8),(137,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820feea97d1.png',9),(138,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820feebf863.png',10),(139,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820feed99b2.png',11),(140,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820feeecb42.png',12),(141,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820fef08ab6.png',13),(142,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820fef17a22.png',14),(143,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820fef2e2e1.png',15),(144,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820fef46f27.png',16),(145,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820fef5dc97.png',17),(146,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820fef732d5.png',18),(147,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820fef8b24a.png',19),(148,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820fef9e8fa.png',20),(149,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820fefb4da4.png',21),(150,27,1,'2026-02-01','Sudah',7,'Lemari 1.A',5,'Bisa','../public/img/qr-code/UNIT_69820fefce5d8.png',22),(151,28,1,'2026-02-01','Sudah',7,'Lemari 3.B',5,'Bisa','../public/img/qr-code/UNIT_698210a9c9fc9.png',1),(152,28,1,'2026-02-01','Sudah',7,'Lemari 3.B',5,'Bisa','../public/img/qr-code/UNIT_698210a9e7768.png',2),(153,28,1,'2026-02-01','Sudah',7,'Lemari 3.B',5,'Bisa','../public/img/qr-code/UNIT_698210aa113ad.png',3),(154,28,1,'2026-02-01','Sudah',7,'Lemari 3.B',5,'Bisa','../public/img/qr-code/UNIT_698210aa30fed.png',4),(155,28,1,'2026-02-01','Sudah',7,'Lemari 3.B',5,'Bisa','../public/img/qr-code/UNIT_698210aa4ceca.png',5),(156,28,1,'2026-02-01','Sudah',7,'Lemari 3.B',5,'Bisa','../public/img/qr-code/UNIT_698210aa5f97c.png',6),(157,28,1,'2026-02-01','Sudah',7,'Lemari 3.B',5,'Bisa','../public/img/qr-code/UNIT_698210aa70ccd.png',7),(158,28,1,'2026-02-01','Sudah',7,'Lemari 3.B',5,'Bisa','../public/img/qr-code/UNIT_698210aa80077.png',8),(159,28,1,'2026-02-01','Sudah',7,'Lemari 3.B',5,'Bisa','../public/img/qr-code/UNIT_698210aa9de19.png',9),(160,28,1,'2026-02-01','Sudah',7,'Lemari 3.B',5,'Bisa','../public/img/qr-code/UNIT_698210aab9b22.png',10),(161,29,1,'2026-02-01','Sudah',7,'Lemari 4.A',1,'Tidak Bisa','../public/img/qr-code/UNIT_698212237a83d.png',1),(162,29,1,'2026-02-01','Sudah',7,'Lemari 4.A',5,'Bisa','../public/img/qr-code/UNIT_69821223b0531.png',2),(163,29,1,'2026-02-01','Sudah',7,'Lemari 4.A',5,'Bisa','../public/img/qr-code/UNIT_69821223e4c9d.png',3),(164,29,1,'2026-02-01','Sudah',7,'Lemari 4.A',5,'Bisa','../public/img/qr-code/UNIT_6982122411bfa.png',4),(165,29,1,'2026-02-01','Sudah',7,'Lemari 4.A',5,'Bisa','../public/img/qr-code/UNIT_6982122436a04.png',5),(166,29,1,'2026-02-01','Sudah',7,'Lemari 4.A',5,'Bisa','../public/img/qr-code/UNIT_698212245b00b.png',6),(167,29,1,'2026-02-01','Sudah',7,'Lemari 4.A',5,'Bisa','../public/img/qr-code/UNIT_6982122483b16.png',7),(168,29,1,'2026-02-01','Sudah',7,'Lemari 4.A',5,'Bisa','../public/img/qr-code/UNIT_698212249de58.png',8),(169,29,1,'2026-02-01','Sudah',7,'Lemari 4.A',5,'Bisa','../public/img/qr-code/UNIT_69821224c5294.png',9),(170,29,1,'2026-02-01','Sudah',7,'Lemari 4.A',5,'Bisa','../public/img/qr-code/UNIT_69821224ef4ec.png',10),(171,29,1,'2026-02-01','Sudah',7,'Lemari 4.A',5,'Bisa','../public/img/qr-code/UNIT_698212251abf7.png',11),(172,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d330f9a.png',1),(173,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d3937c6.png',2),(174,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d3a9734.png',3),(175,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d3c9191.png',4),(176,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d3deea5.png',5),(177,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d400e36.png',6),(178,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d41b407.png',7),(179,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d430596.png',8),(180,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d448202.png',9),(181,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d45d8f6.png',10),(182,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d47aee6.png',11),(183,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d49188c.png',12),(184,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d4a7e1b.png',13),(185,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d4c2e26.png',14),(186,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d4d9d95.png',15),(187,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d4e8d62.png',16),(188,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d503e46.png',17),(189,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d51223b.png',18),(190,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d5299ec.png',19),(191,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d54dddc.png',20),(192,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d56334a.png',21),(193,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d588c2d.png',22),(194,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d5a9a08.png',23),(195,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d5d1eeb.png',24),(196,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d5f190b.png',25),(197,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d61b923.png',26),(198,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d634e85.png',27),(199,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d650439.png',28),(200,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d665059.png',29),(201,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d673c33.png',30),(202,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d682643.png',31),(203,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d696721.png',32),(204,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d6abe0c.png',33),(205,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d6c3cb0.png',34),(206,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d6d6b48.png',35),(207,30,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698212d6ed1f5.png',36),(208,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698213744fcf2.png',1),(209,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_6982137465089.png',2),(210,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_6982137482a73.png',3),(211,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698213749aa7b.png',4),(212,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_69821374b34e5.png',5),(213,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_69821374c9d9c.png',6),(214,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_69821374d8715.png',7),(215,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_69821374e55e1.png',8),(216,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_69821375021e6.png',9),(217,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698213751f2af.png',10),(218,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_6982137539838.png',11),(219,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_6982137554e7a.png',12),(220,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_6982137565df0.png',13),(221,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698213757b7a2.png',14),(222,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_6982137598144.png',15),(223,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_69821375bbd0c.png',16),(224,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_69821375dc23a.png',17),(225,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_6982137601ecd.png',18),(226,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698213761e4d6.png',19),(227,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_6982137641f4c.png',20),(228,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_6982137654ffd.png',21),(229,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_6982137664a59.png',22),(230,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_6982137678fea.png',23),(231,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698213768f993.png',24),(232,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_69821376a7116.png',25),(233,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_69821376bf97e.png',26),(234,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_69821376d411b.png',27),(235,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_69821376ed413.png',28),(236,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_6982137716ee7.png',29),(237,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_6982137733090.png',30),(238,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_6982137752a4f.png',31),(239,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_6982137771e4b.png',32),(240,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_6982137787627.png',33),(241,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_698213779f942.png',34),(242,31,1,'2026-02-01','Sudah',2,'Lab Startup',3,'Tidak Bisa','../public/img/qr-code/UNIT_69821377b5875.png',35),(243,31,1,'2026-02-01','Sudah',2,'Lab Startup',5,'Tidak Bisa','../public/img/qr-code/UNIT_UPD_6982c774ce4b8.png',36),(244,32,1,'2026-02-01','Sudah',1,'Lab IoT',3,'Tidak Bisa','../public/img/qr-code/UNIT_69821425d2234.png',1),(245,33,1,'2026-02-01','Sudah',1,'Lab IoT',3,'Tidak Bisa','../public/img/qr-code/UNIT_698214dd5b2b0.png',1),(246,34,1,'2026-02-01','Sudah',3,'Lab Comnet',1,'Tidak Bisa','../public/img/qr-code/UNIT_6982156252a70.png',1),(247,35,1,'2026-02-01','Sudah',4,'Lab Mulmed',3,'Tidak Bisa','../public/img/qr-code/UNIT_69821647ae0c8.png',1),(248,35,1,'2026-02-01','Sudah',4,'Lab Mulmed',3,'Tidak Bisa','../public/img/qr-code/UNIT_69821647d25d1.png',2),(249,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f72bc66.png',1),(250,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f73dc87.png',2),(251,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f75259a.png',3),(252,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f766fac.png',4),(253,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f77d68a.png',5),(254,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f7908e9.png',6),(255,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f79cbce.png',7),(256,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f7a9137.png',8),(257,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f7b3d0b.png',9),(258,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f7c540f.png',10),(259,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f7d5620.png',11),(260,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f7e6659.png',12),(261,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f806555.png',13),(262,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f816e89.png',14),(263,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f82771f.png',15),(264,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f83c0a2.png',16),(265,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f85298d.png',17),(266,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f866ed9.png',18),(267,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f87c6be.png',19),(268,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f890f62.png',20),(269,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f8a6559.png',21),(270,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f8b7f69.png',22),(271,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f8cd59b.png',23),(272,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f8d9f37.png',24),(273,36,1,'2026-02-01','Sudah',6,'Lab Ds',3,'Tidak Bisa','../public/img/qr-code/UNIT_698216f8e88a5.png',25),(274,37,1,'2026-02-01','Sudah',5,'Lab cv',3,'Tidak Bisa','../public/img/qr-code/UNIT_69821794e583f.png',1);
/*!40000 ALTER TABLE `trx_barang` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_detail_peminjaman`
--

LOCK TABLES `trx_detail_peminjaman` WRITE;
/*!40000 ALTER TABLE `trx_detail_peminjaman` DISABLE KEYS */;
INSERT INTO `trx_detail_peminjaman` VALUES (1,1,9,NULL,1,'REQ_SPEC:27');
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
  `id_kondisi_pengembalian` int(11) NOT NULL,
  `keterangan_kondisi` text DEFAULT NULL,
  PRIMARY KEY (`id_detail_pengembalian`),
  KEY `idx_pengembalian` (`id_pengembalian`),
  KEY `idx_detail_peminjaman` (`id_detail_peminjaman`),
  KEY `fk_detail_kembali_kondisi` (`id_kondisi_pengembalian`),
  CONSTRAINT `fk_detail_kembali_header` FOREIGN KEY (`id_pengembalian`) REFERENCES `trx_pengembalian` (`id_pengembalian`) ON DELETE CASCADE,
  CONSTRAINT `fk_detail_kembali_kondisi` FOREIGN KEY (`id_kondisi_pengembalian`) REFERENCES `mst_kondisi_pengembalian` (`id_kondisi_pengembalian`) ON UPDATE CASCADE,
  CONSTRAINT `fk_detail_kembali_pinjam` FOREIGN KEY (`id_detail_peminjaman`) REFERENCES `trx_detail_peminjaman` (`id_detail`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_detail_pengembalian`
--

LOCK TABLES `trx_detail_pengembalian` WRITE;
/*!40000 ALTER TABLE `trx_detail_pengembalian` DISABLE KEYS */;
/*!40000 ALTER TABLE `trx_detail_pengembalian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trx_log_activity`
--

DROP TABLE IF EXISTS `trx_log_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trx_log_activity` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_log`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `trx_log_activity_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `trx_user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_log_activity`
--

LOCK TABLES `trx_log_activity` WRITE;
/*!40000 ALTER TABLE `trx_log_activity` DISABLE KEYS */;
/*!40000 ALTER TABLE `trx_log_activity` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_pemeriksa_pengembalian`
--

LOCK TABLES `trx_pemeriksa_pengembalian` WRITE;
/*!40000 ALTER TABLE `trx_pemeriksa_pengembalian` DISABLE KEYS */;
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
  `id_jenis_peminjaman` int(11) NOT NULL DEFAULT 2,
  `judul_kegiatan` varchar(255) NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `tanggal_peminjaman` date NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `keterangan_peminjaman` text DEFAULT NULL,
  `keterangan_tolak` text DEFAULT NULL,
  `id_status_peminjaman` int(11) NOT NULL DEFAULT 1,
  `last_notification_sent` datetime DEFAULT NULL,
  `file_surat` varchar(255) DEFAULT NULL,
  `validasi_kalab` enum('0','1') DEFAULT '0' COMMENT '0=Belum, 1=Sudah (Huzain)',
  PRIMARY KEY (`id_peminjaman`),
  KEY `fk_peminjaman_user` (`id_user`),
  KEY `fk_peminjaman_status` (`id_status_peminjaman`),
  KEY `fk_peminjaman_jenis` (`id_jenis_peminjaman`),
  CONSTRAINT `fk_peminjaman_jenis` FOREIGN KEY (`id_jenis_peminjaman`) REFERENCES `mst_jenis_peminjaman` (`id_jenis_peminjaman`) ON UPDATE CASCADE,
  CONSTRAINT `fk_peminjaman_status` FOREIGN KEY (`id_status_peminjaman`) REFERENCES `mst_status_peminjaman` (`id_status_peminjaman`) ON UPDATE CASCADE,
  CONSTRAINT `fk_peminjaman_user` FOREIGN KEY (`id_user`) REFERENCES `trx_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_peminjaman`
--

LOCK TABLES `trx_peminjaman` WRITE;
/*!40000 ALTER TABLE `trx_peminjaman` DISABLE KEYS */;
INSERT INTO `trx_peminjaman` VALUES (1,38,1,'TUGAS AKHIR','2026-02-24','2026-02-24','2026-02-25','',NULL,1,NULL,NULL,'0');
/*!40000 ALTER TABLE `trx_peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trx_peminjaman_akademik`
--

DROP TABLE IF EXISTS `trx_peminjaman_akademik`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trx_peminjaman_akademik` (
  `id_peminjaman` int(11) NOT NULL,
  `kategori_kegiatan` varchar(100) NOT NULL COMMENT 'Contoh: Tugas Akhir, Riset, Lain-lain',
  `dosen_pembimbing` varchar(100) NOT NULL,
  `validasi_dosen` enum('0','1') DEFAULT '0' COMMENT '0=Belum, 1=Sudah',
  PRIMARY KEY (`id_peminjaman`),
  CONSTRAINT `fk_akademik_peminjaman` FOREIGN KEY (`id_peminjaman`) REFERENCES `trx_peminjaman` (`id_peminjaman`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_peminjaman_akademik`
--

LOCK TABLES `trx_peminjaman_akademik` WRITE;
/*!40000 ALTER TABLE `trx_peminjaman_akademik` DISABLE KEYS */;
INSERT INTO `trx_peminjaman_akademik` VALUES (1,'Tugas Akhir','Andi Rahman','0');
/*!40000 ALTER TABLE `trx_peminjaman_akademik` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trx_peminjaman_internal`
--

DROP TABLE IF EXISTS `trx_peminjaman_internal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trx_peminjaman_internal` (
  `id_peminjaman` int(11) NOT NULL,
  `validasi_laboran` enum('0','1') DEFAULT '0' COMMENT '0=Belum, 1=Sudah',
  PRIMARY KEY (`id_peminjaman`),
  CONSTRAINT `fk_internal_peminjaman` FOREIGN KEY (`id_peminjaman`) REFERENCES `trx_peminjaman` (`id_peminjaman`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_peminjaman_internal`
--

LOCK TABLES `trx_peminjaman_internal` WRITE;
/*!40000 ALTER TABLE `trx_peminjaman_internal` DISABLE KEYS */;
/*!40000 ALTER TABLE `trx_peminjaman_internal` ENABLE KEYS */;
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
  `id_status_pengembalian` int(11) DEFAULT NULL,
  `id_keterangan_pengembalian` int(11) DEFAULT NULL,
  `detail_masalah` text DEFAULT NULL,
  PRIMARY KEY (`id_pengembalian`),
  KEY `id_peminjaman` (`id_peminjaman`),
  KEY `fk_pengembalian_status` (`id_status_pengembalian`),
  KEY `fk_pengembalian_keterangan` (`id_keterangan_pengembalian`),
  CONSTRAINT `fk_pengembalian_keterangan` FOREIGN KEY (`id_keterangan_pengembalian`) REFERENCES `mst_keterangan_pengembalian` (`id_keterangan_pengembalian`) ON UPDATE CASCADE,
  CONSTRAINT `fk_pengembalian_status` FOREIGN KEY (`id_status_pengembalian`) REFERENCES `mst_status_pengembalian` (`id_status_pengembalian`) ON UPDATE CASCADE,
  CONSTRAINT `trx_pengembalian_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `trx_peminjaman` (`id_peminjaman`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_pengembalian`
--

LOCK TABLES `trx_pengembalian` WRITE;
/*!40000 ALTER TABLE `trx_pengembalian` DISABLE KEYS */;
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
  `foto` text DEFAULT NULL,
  `nama_user` varchar(100) NOT NULL,
  `nim_nip` varchar(30) NOT NULL,
  `no_hp_user` varchar(15) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `alamat` text DEFAULT NULL,
  `file_ttd` text DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=Belum Verifikasi, 1=Sudah Verifikasi',
  `verification_token` varchar(64) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  KEY `id_role` (`id_role`),
  CONSTRAINT `trx_user_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `mst_role` (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trx_user`
--

LOCK TABLES `trx_user` WRITE;
/*!40000 ALTER TABLE `trx_user` DISABLE KEYS */;
INSERT INTO `trx_user` VALUES 
(1,'keplab@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',1,'../public/img/foto-profile/user.svg','Kepala Lab','001','081234567001','Laki-laki','Makassar',NULL,1,NULL,NULL),
(2,'laboran@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',2,'../public/img/foto-profile/user.svg','Laboran','002','081234567002','Perempuan','Makassar',NULL,1,NULL,NULL),
(3,'korlab@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',3,'../public/img/foto-profile/user.svg','Koordinator Lab','003','081234567003','Laki-laki','Makassar',NULL,1,NULL,NULL),
(4,'asisten@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',4,'../public/img/foto-profile/user.svg','Asisten Lab','004','081234567004','Perempuan','Makassar',NULL,1,NULL,NULL),
(5,'ca@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',5,'../public/img/foto-profile/user.svg','Calon Asisten','005','081234567005','Laki-laki','Makassar',NULL,1,NULL,NULL),
(6,'cca@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',6,'../public/img/foto-profile/user.svg','Calon CA','006','081234567006','Perempuan','Makassar',NULL,1,NULL,NULL),
(38,'13020230219@student.umi.ac.id','$2y$10$a04wRuUTOl6lfbubkV/NY.0AUPdRyTyymXNQduB3.YNwf97I3sQAG',6,'../public/img/foto-profile/user.svg','Andi Rahman','13020230219','088246700573','Laki-laki','Perumnas BTP Blok H.lama No.509, Tamalanrea, Kec. Tamalanrea, Kota Makassar, Sulawesi Selatan 90245',NULL,1,NULL,NULL);
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

-- Dump completed on 2026-03-04 16:24:26
