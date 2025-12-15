-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db_arsip
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tb_arsip`
--

DROP TABLE IF EXISTS `tb_arsip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_arsip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_id` int(11) NOT NULL,
  `no_berkas` varchar(100) DEFAULT NULL,
  `no_urut` int(11) DEFAULT NULL,
  `kode` varchar(100) DEFAULT NULL,
  `indeks_pekerjaan` varchar(500) DEFAULT NULL,
  `uraian_masalah_kegiatan` text DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `jumlah_berkas` int(11) DEFAULT 1,
  `asli_kopi` enum('Asli','Kopi') DEFAULT NULL COMMENT 'Asli atau Kopi',
  `box` varchar(100) DEFAULT NULL COMMENT 'Nomor Box',
  `klasifikasi_keamanan` varchar(100) DEFAULT NULL COMMENT 'Klasifikasi keamanan dan akses arsip dinamis',
  `nama_pengisi` varchar(256) DEFAULT NULL COMMENT 'Nama pengisi arsip',
  `link_drive` varchar(1000) DEFAULT NULL COMMENT 'Link Google Drive',
  `nama_file` varchar(500) DEFAULT NULL,
  `path_file` varchar(1000) DEFAULT NULL,
  `ukuran_file` bigint(20) DEFAULT NULL COMMENT 'Ukuran file dalam bytes',
  `tipe_file` varchar(100) DEFAULT NULL COMMENT 'MIME type file',
  `createDate` datetime NOT NULL,
  `updateDate` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'ID user yang membuat',
  PRIMARY KEY (`id`),
  KEY `kategori_id` (`kategori_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `fk_arsip_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `tb_kategori_arsip` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_arsip_user` FOREIGN KEY (`created_by`) REFERENCES `tb_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_arsip`
--

LOCK TABLES `tb_arsip` WRITE;
/*!40000 ALTER TABLE `tb_arsip` DISABLE KEYS */;
INSERT INTO `tb_arsip` VALUES (1,3,'00251',1,'KU03','SATKER BALAI PENILAIAN KOMPETENSI','SPM No. 00251 Tanggal: 4 Juni 2018, Pembayaran Belanja Pegawai Tunjangan Kinerja Bulan Mei 2018 Sejumlah 6 Pegawai di Balai Penilaian Kompetensi Kementerian Pekerjaan Umum dan Perumahan Rakyat Sesuai SPP: 00251/400947/VI/2018 Tanggal : 4 Juni 2018(21,946,945)',2018,1,'Asli','1','Terbatas','Administrator','','8db4622184ff3f3a9237e6235160f942.pdf','./uploads/arsip/8db4622184ff3f3a9237e6235160f942.pdf',677,'application/pdf','2025-12-09 23:07:00','2025-12-10 05:44:08',1),(2,3,'DOK-20251210-001',NULL,'','','',2025,1,NULL,'','','Administrator','https://onedrive.live.com',NULL,NULL,NULL,NULL,'2025-12-10 11:43:17',NULL,1);
/*!40000 ALTER TABLE `tb_arsip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_kategori_arsip`
--

DROP TABLE IF EXISTS `tb_kategori_arsip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_kategori_arsip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(256) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `createDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_nama` (`nama`),
  KEY `idx_parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_kategori_arsip`
--

LOCK TABLES `tb_kategori_arsip` WRITE;
/*!40000 ALTER TABLE `tb_kategori_arsip` DISABLE KEYS */;
INSERT INTO `tb_kategori_arsip` VALUES (1,'Surat Masuk','Arsip untuk surat-surat yang masuk',NULL,'2025-12-09 15:58:11'),(2,'Surat Keluar','Arsip untuk surat-surat yang keluar',NULL,'2025-12-09 15:58:11'),(3,'Dokumen Kepegawaian','Arsip dokumen kepegawaian',NULL,'2025-12-09 15:58:11'),(4,'Dokumen Keuangan','Arsip dokumen keuangan',NULL,'2025-12-09 15:58:11'),(5,'Dokumen Umum','Arsip dokumen umum lainnya',NULL,'2025-12-09 15:58:11'),(10,'Test','',3,'2025-12-10 09:00:51');
/*!40000 ALTER TABLE `tb_kategori_arsip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_riwayat_arsip`
--

DROP TABLE IF EXISTS `tb_riwayat_arsip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_riwayat_arsip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `arsip_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `aksi` enum('Upload','Download','View','Update','Delete') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `createDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `arsip_id` (`arsip_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_riwayat_arsip` FOREIGN KEY (`arsip_id`) REFERENCES `tb_arsip` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_riwayat_user` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_riwayat_arsip`
--

LOCK TABLES `tb_riwayat_arsip` WRITE;
/*!40000 ALTER TABLE `tb_riwayat_arsip` DISABLE KEYS */;
INSERT INTO `tb_riwayat_arsip` VALUES (1,1,1,'Upload','Arsip baru diupload','::1','2025-12-09 23:07:00'),(2,1,1,'View','Arsip dilihat','::1','2025-12-09 23:07:03'),(3,1,1,'View','Arsip dilihat','10.10.30.120','2025-12-10 08:09:41'),(4,1,1,'View','Arsip dilihat','10.10.30.120','2025-12-10 08:10:54'),(5,1,1,'View','Arsip dilihat','10.10.30.120','2025-12-10 08:11:23'),(6,1,1,'Download','Arsip didownload','10.10.30.120','2025-12-10 08:12:41'),(7,1,1,'View','Arsip dilihat','10.10.30.120','2025-12-10 08:12:56'),(8,1,1,'Download','Arsip didownload','10.10.30.206','2025-12-10 08:14:56'),(9,1,1,'Update','Arsip diupdate','10.10.30.120','2025-12-10 09:01:00'),(10,1,1,'View','Arsip dilihat','10.10.30.120','2025-12-10 09:39:44'),(11,1,1,'View','Arsip dilihat','10.10.30.206','2025-12-10 09:58:38'),(12,1,1,'View','Arsip dilihat','10.10.30.206','2025-12-10 09:58:40'),(13,1,1,'Update','Arsip diupdate','10.10.30.120','2025-12-10 10:21:42'),(14,1,1,'View','Arsip dilihat','10.10.30.120','2025-12-10 10:29:10'),(15,1,1,'Update','Arsip diupdate','10.10.30.120','2025-12-10 10:45:08'),(16,1,1,'Update','Arsip diupdate','10.10.30.120','2025-12-10 10:52:45'),(17,2,1,'Upload','Arsip baru diupload','::1','2025-12-10 11:43:17'),(18,1,1,'Update','Arsip diupdate','::1','2025-12-10 11:44:08');
/*!40000 ALTER TABLE `tb_riwayat_arsip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_user`
--

DROP TABLE IF EXISTS `tb_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(256) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `level` varchar(16) NOT NULL,
  `createDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_user`
--

LOCK TABLES `tb_user` WRITE;
/*!40000 ALTER TABLE `tb_user` DISABLE KEYS */;
INSERT INTO `tb_user` VALUES (1,'Administrator','admin','21232f297a57a5a743894a0e4a801fc3','Admin','2025-12-09 15:58:11'),(2,'User','user','ee11cbb19052e40b07aac0ca060c23ee','User','2025-12-09 15:58:11');
/*!40000 ALTER TABLE `tb_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-10 14:59:30
