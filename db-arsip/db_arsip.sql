-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db_arsip
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
  `kode_id` int(11) DEFAULT NULL COMMENT 'Foreign key ke tb_kode_arsip',
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
  KEY `fk_arsip_kode` (`kode_id`),
  CONSTRAINT `fk_arsip_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `tb_kategori_arsip` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_arsip_kode` FOREIGN KEY (`kode_id`) REFERENCES `tb_kode_arsip` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_arsip_user` FOREIGN KEY (`created_by`) REFERENCES `tb_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_arsip`
--

LOCK TABLES `tb_arsip` WRITE;
/*!40000 ALTER TABLE `tb_arsip` DISABLE KEYS */;
INSERT INTO `tb_arsip` VALUES (4,11,'HUB-20251212-002',NULL,NULL,NULL,'Satker Balai Penilaian Kompetensi',NULL,2025,1,NULL,NULL,NULL,'Administrator',NULL,'a134c03a68054e70039f4599ff6cece7.pdf','./uploads/arsip/a134c03a68054e70039f4599ff6cece7.pdf',330,'application/pdf','2025-12-12 14:45:39',NULL,1),(5,11,'HUB-20251212-003',NULL,NULL,NULL,'Satker Balai Penilaian Kompetensi',NULL,2025,1,NULL,NULL,NULL,'Administrator',NULL,'741532c2f6a8fbde786d376f17f448a0.pdf','./uploads/arsip/741532c2f6a8fbde786d376f17f448a0.pdf',189,'application/pdf','2025-12-12 14:45:39',NULL,1),(6,11,'HUB-20251212-004',NULL,NULL,NULL,'Satker Balai Penilaian Kompetensi',NULL,2025,1,NULL,NULL,NULL,'Administrator',NULL,'fde91747ec6e3a9b8ba932f5e8d5aa1d.xlsx','./uploads/arsip/fde91747ec6e3a9b8ba932f5e8d5aa1d.xlsx',7,'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','2025-12-12 14:48:36',NULL,1),(7,11,'HUB-20251212-005',NULL,NULL,NULL,'Satker Balai Penilaian Kompetensi',NULL,2025,1,NULL,NULL,NULL,'Administrator',NULL,'fcb31fde5d10438ad9a24be302258a9a.xlsx','./uploads/arsip/fcb31fde5d10438ad9a24be302258a9a.xlsx',7,'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','2025-12-12 14:48:36',NULL,1),(8,11,'HUB-20251212-006',NULL,NULL,NULL,'Satker Balai Penilaian Kompetensi',NULL,2025,1,NULL,NULL,NULL,'Administrator',NULL,'eae99b52e9d5b32b094d8bd476561aae.pdf','./uploads/arsip/eae99b52e9d5b32b094d8bd476561aae.pdf',1643,'application/pdf','2025-12-12 14:48:36',NULL,1);
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
  `parent_id` int(11) DEFAULT NULL,
  `createDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_kategori_arsip`
--

LOCK TABLES `tb_kategori_arsip` WRITE;
/*!40000 ALTER TABLE `tb_kategori_arsip` DISABLE KEYS */;
INSERT INTO `tb_kategori_arsip` VALUES (3,'Arsip Aktif',NULL,'2025-12-09 15:58:11'),(4,'Arsip Inaktif',NULL,'2025-12-09 15:58:11'),(11,'Hubungan Masyarakat (HM)',3,'2025-12-12 10:59:09'),(12,'Hukum (HK)',3,'2025-12-12 10:59:15'),(14,'Kepegawaian (KP)',3,'2025-12-12 10:59:36'),(15,'Keuangan (KU)',3,'2025-12-12 10:59:45'),(16,'Organisasi dan Tata Laksana (OR)',3,'2025-12-12 10:59:51'),(17,'Pengadaan Barang Jasa (PB)',3,'2025-12-12 10:59:57'),(18,'Pengawasan (PW)',3,'2025-12-12 11:00:03'),(19,'Pengelolaan Aset Barang Milik Negara (PS)',3,'2025-12-12 11:00:10'),(20,'Pengelolaan Data (PA)',3,'2025-12-12 11:00:16'),(21,'Pengembangan Sumber Daya Manusia (SM)',3,'2025-12-12 11:00:25'),(22,'Perencanaan (PR)',3,'2025-12-12 11:00:50'),(23,'Umum (UM)',3,'2025-12-12 11:00:57'),(26,'Hubungan Masyarakat (HM)',4,'2025-12-12 11:14:22'),(27,'Hukum (HK)',4,'2025-12-12 11:14:30'),(28,'Kepegawaian (KP)',4,'2025-12-12 11:14:37'),(29,'Keuangan (KU)',4,'2025-12-12 11:14:42'),(30,'Organisasi dan Tata Laksana (OR)',4,'2025-12-12 11:14:48'),(31,'Pengadaan Barang Jasa (PB)',4,'2025-12-12 11:14:54'),(32,'Pengawasan (PW)',4,'2025-12-12 11:14:59'),(33,'Pengelolaan Aset Barang Milik Negara (PS)',4,'2025-12-12 11:15:05'),(34,'Pengelolaan Data (PA)',4,'2025-12-12 11:15:11'),(35,'Pengembangan Sumber Daya Manusia (SM)',4,'2025-12-12 11:15:19'),(36,'Perencanaan (PR)',4,'2025-12-12 11:15:25'),(37,'Umum (UM)',4,'2025-12-12 11:15:30');
/*!40000 ALTER TABLE `tb_kategori_arsip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_kode_arsip`
--

DROP TABLE IF EXISTS `tb_kode_arsip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_kode_arsip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) NOT NULL COMMENT 'Kode arsip (contoh: HK01, HK0101, dll)',
  `nama` varchar(500) NOT NULL COMMENT 'Nama/deskripsi kode',
  `createDate` datetime NOT NULL,
  `updateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=225 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_kode_arsip`
--

LOCK TABLES `tb_kode_arsip` WRITE;
/*!40000 ALTER TABLE `tb_kode_arsip` DISABLE KEYS */;
INSERT INTO `tb_kode_arsip` VALUES (1,'HK01','Produk Hukum','2025-12-15 08:06:05','2025-12-15 02:07:52'),(2,'HK0101','Produk Hukum Pengaturan (PP/Permen, Pedoman, Juklak, Instruksi, SE, dll.)','2025-12-15 08:06:05','2025-12-15 02:07:56'),(3,'HK0102','Produk Hukum Penetapan (Keputusan: penetapan kegiatan, penetapan pelaksana, dll.)','2025-12-15 08:06:05','2025-12-15 02:08:01'),(4,'HK02','Perjanjian Kerjasama','2025-12-15 08:06:05','2025-12-15 02:08:07'),(5,'HK0201','Perjanjian Kerjasama Dalam Negeri','2025-12-15 08:06:05','2025-12-15 02:08:11'),(6,'HK0202','Perjanjian Kerjasama Luar Negeri','2025-12-15 08:06:05','2025-12-15 02:08:21'),(7,'HK03','Sosialisasi Hukum (penyebarluasan regulasi & instrumen hukum)','2025-12-15 08:06:05','2025-12-15 02:08:29'),(8,'HK04','Dokumentasi Hukum (JDIH, dokumentasi & informasi hukum)','2025-12-15 08:06:05','2025-12-15 02:08:34'),(9,'HK05','Hak Kekayaan Intelektual (HKI) (pengurusan & penyelesaian urusan HKI)','2025-12-15 08:06:05','2025-12-15 02:08:39'),(10,'HK06','Advokasi Hukum','2025-12-15 08:06:05','2025-12-15 02:08:44'),(11,'HK0601','Advokasi Hukum Perdata','2025-12-15 08:06:05','2025-12-15 02:10:01'),(12,'HK0602','Advokasi Hukum Pidana','2025-12-15 08:06:05','2025-12-15 02:10:08'),(13,'HK0603','Advokasi Hukum TUN','2025-12-15 08:06:05','2025-12-15 02:10:16'),(14,'HM01','Publikasi Informasi (wawancara, konferensi pers, media massa, media sosial, layanan informasi publik)','2025-12-15 08:11:13',NULL),(15,'HM02','Dokumentasi Informasi (dokumentasi kegiatan/isu PUPR)','2025-12-15 08:11:37',NULL),(16,'HM03','Penerbitan (buletin, majalah, jurnal PUPR)','2025-12-15 08:11:53',NULL),(17,'HM04','Dengar Pendapat / Hearing (kegiatan hearing dengan DPR)','2025-12-15 08:12:09',NULL),(18,'HM05','Hubungan Antar Lembaga','2025-12-15 08:12:26',NULL),(19,'HM0501','Lembaga Negara (koordinasi antar kementerian/LPNK/pemda)','2025-12-15 08:12:40',NULL),(20,'HM0502','Organisasi Infrastruktur Nasional','2025-12-15 08:12:53',NULL),(21,'HM0503','Perusahaan (BUMN, BUMD, swasta)','2025-12-15 08:13:06',NULL),(22,'HM0504','Organisasi Kemasyarakatan','2025-12-15 08:13:18',NULL),(23,'HM0505','Bakohumas (forum kehumasan)','2025-12-15 08:13:31',NULL),(24,'HM0506','Perguruan Tinggi / Sekolah (PKL, kunjungan, orientasi lapangan)','2025-12-15 08:13:44',NULL),(25,'KU0101','Penyusunan Target & Pagu Indikatif PNBP (arsip penyusunan target dan pagu PNBP)','2025-12-15 08:30:01',NULL),(26,'KU0102','Penyusunan RBA BLU (arsip penyusunan Rencana Bisnis Anggaran BLU)','2025-12-15 08:30:01',NULL),(27,'KU0201','Belanja Pegawai (gaji, tunjangan, honorarium, uang makan, lembur)','2025-12-15 08:30:01',NULL),(28,'KU0202','Belanja Barang & Jasa (ATK, konsumsi, listrik, air, telepon, penggandaan, inventaris kecil)','2025-12-15 08:30:01',NULL),(29,'KU0203','Belanja Pemeliharaan (gedung, rumah dinas, kendaraan, inventaris, peralatan)','2025-12-15 08:30:01',NULL),(30,'KU0204','Belanja Sewa (sewa gedung, sewa peralatan dan mesin)','2025-12-15 08:30:01',NULL),(31,'KU0205','Belanja Perjalanan (SPD, perjalanan dinas, meeting dalam/luar kota, luar negeri)','2025-12-15 08:30:01',NULL),(32,'KU0206','Belanja Bantuan Pemerintah (bantuan barang/uang ke masyarakat/pemerintah daerah)','2025-12-15 08:30:01',NULL),(33,'KU0207','Belanja Modal Tanah (pengadaan, pembebasan, sertifikat, pematangan tanah)','2025-12-15 08:30:01',NULL),(34,'KU0208','Belanja Modal Peralatan & Mesin (pengadaan inventaris berumur >12 bulan)','2025-12-15 08:30:01',NULL),(35,'KU0209','Belanja Modal Gedung & Bangunan (pembangunan, perencanaan, pengawasan hingga siap pakai)','2025-12-15 08:30:01',NULL),(36,'KU0210','Belanja Modal Jalan, Irigasi & Jaringan (pembangunan, peningkatan, perawatan jaringan infrastruktur)','2025-12-15 08:30:01',NULL),(37,'KU0211','Belanja Modal Aset Lainnya (software, studi, aset tak berwujud lainnya)','2025-12-15 08:30:01',NULL),(38,'KU0212','Belanja Lainnya (bencana alam, tanggap darurat, dll.)','2025-12-15 08:30:01',NULL),(39,'KU0301','Dokumen Pengeluaran Anggaran (SPP, SPP-GU, SPP-LS, SPP-TUP, SPBy, Surat Kuasa, SPM, SP2D)','2025-12-15 08:30:01',NULL),(40,'KU0401','PNBP (transaksi PNBP online, tarif, MP pencairan PNBP)','2025-12-15 08:30:01',NULL),(41,'KU0402','Pengembalian Belanja (pengembalian TA berjalan & lintas TA)','2025-12-15 08:30:01',NULL),(42,'KU0403','Tuntutan Ganti Rugi (TGR) (SKTJM, penagihan, keterangan lunas)','2025-12-15 08:30:01',NULL),(43,'KU0404','Tuntutan Perbendaharaan (tuntutan terhadap bendahara, SKTJM)','2025-12-15 08:30:01',NULL),(44,'KU0405','Perhitungan Ex-Officio (perhitungan saat pejabat tidak bisa mempertanggungjawabkan)','2025-12-15 08:30:01',NULL),(45,'KU0406','Pembukaan Rekening Bendahara (permohonan & laporan rekening bendahara)','2025-12-15 08:30:01',NULL),(46,'KU0407','Berita Acara Pemeriksaan Kas (BAPK kas rutin)','2025-12-15 08:30:01',NULL),(47,'KU0408','Verifikasi Anggaran (pengujian RKA, bukti pengeluaran, administrasi)','2025-12-15 08:30:01',NULL),(48,'KU0409','Nota Verifikasi Pertanggungjawaban (memo penyesuaian, pengembalian SPP/SPM)','2025-12-15 08:30:01',NULL),(49,'KU0410','Pembukuan Anggaran (BKU, BKP, kartu realisasi & monitoring DIPA)','2025-12-15 08:30:01',NULL),(50,'KU0411','Pendampingan Perbendaharaan (bimbingan anggaran, pencairan, laporan, tindak lanjut LHP)','2025-12-15 08:30:01',NULL),(51,'KU0412','Pembinaan Perbendaharaan (sosialisasi aturan & kebijakan keuangan)','2025-12-15 08:30:01',NULL),(52,'KU0501','LPJ Bendahara (BAPK, rekonsiliasi, saldo rekening, koran)','2025-12-15 08:30:01',NULL),(53,'KU0502','Pelaporan PNBP (umum, fungsional, BLU)','2025-12-15 08:30:01',NULL),(54,'KU0503','Pelaporan Keuangan (SAI) (LRA, Neraca, CALK, LO, LPE)','2025-12-15 08:30:01',NULL),(55,'KU0504','Pelaporan Pajak (pelaporan pajak bendahara ke KPP)','2025-12-15 08:30:01',NULL),(56,'KU0505','Pelaporan Piutang Negara (TGR, sewa rumah negara gol III)','2025-12-15 08:30:01',NULL),(57,'KU0601','Evaluasi Laporan Keuangan (eselon I, satker, kementerian)','2025-12-15 08:30:01',NULL),(58,'KU0602','Evaluasi Kinerja Pelaksanaan Anggaran (penyerapan, revisi, retur SPM/SP2D, data kontrak)','2025-12-15 08:30:01',NULL),(59,'KU0603','Evaluasi Pejabat Perbendaharaan (administrasi & teknis pejabat)','2025-12-15 08:30:01',NULL),(60,'KU0604','Evaluasi Kinerja BLU (performansi BLU)','2025-12-15 08:30:01',NULL),(61,'KU0701','KP4 (surat keterangan penerimaan tunjangan keluarga)','2025-12-15 08:30:01',NULL),(62,'KU0702','Keterangan Penghasilan (slip/keterangan penghasilan pegawai)','2025-12-15 08:30:01',NULL),(63,'KU0703','SKPP (surat pemberhentian pembayaran gaji)','2025-12-15 08:30:01',NULL),(64,'KU0704','Penyampaian SPT Pajak (laporan SPT tahunan/manual/e-filing)','2025-12-15 08:30:01',NULL),(65,'KP0101','Analisa Jabatan (uraian tugas, evaluasi jabatan, spesifikasi jabatan, DUPAK/DUK)','2025-12-15 08:30:01',NULL),(66,'KP0102','Analisa Beban Kerja (pengukuran beban kerja, pemetaan kebutuhan pegawai)','2025-12-15 08:30:01',NULL),(67,'KP0103','Formasi Kepegawaian (rencana formasi, pengadaan CPNS, bezetting pegawai)','2025-12-15 08:30:01',NULL),(68,'KP0201','Penerimaan Pegawai (pengumuman, lamaran, seleksi, hasil seleksi)','2025-12-15 08:30:01',NULL),(69,'KP0202','Pengangkatan CPNS/PNS (pemberkasan, NIP, SK CPNS/PNS)','2025-12-15 08:30:01',NULL),(70,'KP0203','Penempatan CPNS/PNS (penugasan, surat penempatan)','2025-12-15 08:30:01',NULL),(71,'KP0204','Penerimaan & Penempatan PPPK/Non-PNS (seleksi dan penempatan PPPK/Non-PNS)','2025-12-15 08:30:01',NULL),(72,'KP0301','Ujian Penyesuaian Ijazah (berkas ujian penyesuaian ijazah)','2025-12-15 08:30:01',NULL),(73,'KP0302','Ujian Dinas (arsip ujian dinas)','2025-12-15 08:30:01',NULL),(74,'KP0303','Ujian Kompetensi/Assessment (tes kompetensi untuk kenaikan pangkat/jabatan)','2025-12-15 08:30:01',NULL),(75,'KP0401','Kenaikan & Penyesuaian Pangkat/Golongan (KP, penyesuaian pangkat)','2025-12-15 08:30:01',NULL),(76,'KP0402','Rotasi Kerja (perpindahan antar-unit dalam instansi)','2025-12-15 08:30:01',NULL),(77,'KP0403','Alih Tugas (mutasi antar-instansi, diperbantukan)','2025-12-15 08:30:01',NULL),(78,'KP0404','Penempatan Kembali (setelah CLTN, tugas belajar, diperbantukan)','2025-12-15 08:30:01',NULL),(79,'KP0501','Pengangkatan Jabatan Struktural (pengangkatan, pelantikan)','2025-12-15 08:30:01',NULL),(80,'KP0502','Pemberhentian Jabatan Struktural (SK pemberhentian dari jabatan)','2025-12-15 08:30:01',NULL),(81,'KP0601','Data Pegawai (DR H, Akta, KTP, KK, surat nikah/cerai, dll.)','2025-12-15 08:30:01',NULL),(82,'KP0602','Absensi Pegawai (rekap kehadiran)','2025-12-15 08:30:01',NULL),(83,'KP0603','Kartu-Kartu Kepegawaian (ID Card, Karpeg, Karsu/Karis, Taspen, BPJS)','2025-12-15 08:30:01',NULL),(84,'KP0604','Tanda Jasa (penghargaan masa kerja, pegawai teladan)','2025-12-15 08:30:01',NULL),(85,'KP0605','Penggajian (kenaikan gaji berkala, gaji istimewa)','2025-12-15 08:30:01',NULL),(86,'KP0606','Penyesuaian Masa Kerja (penyesuaian MKG)','2025-12-15 08:30:01',NULL),(87,'KP0607','Penyesuaian Kelas Jabatan (penyesuaian kelas jabatan akibat perubahan)','2025-12-15 08:30:01',NULL),(88,'KP0701','Cuti Pegawai (cuti tahunan, cuti besar, cuti melahirkan, CLTN)','2025-12-15 08:30:01',NULL),(89,'KP0801','Penilaian Prestasi Kerja (SKP) (SKP, penilaian kinerja)','2025-12-15 08:30:01',NULL),(90,'KP0802','Pembinaan Karakter Pegawai (pembinaan jasmani & rohani)','2025-12-15 08:30:01',NULL),(91,'KP0803','Hukuman Disiplin (tegur lisan/tertulis, penurunan pangkat)','2025-12-15 08:30:01',NULL),(92,'KP0804','Penyelesaian Sengketa Pegawai (rekam perselisihan, perceraian)','2025-12-15 08:30:01',NULL),(93,'KP0901','Pengembangan Karir (pemetaan, pembinaan, pola karir)','2025-12-15 08:30:01',NULL),(94,'KP0902','Pelatihan Keterampilan/Keahlian (pola keterampilan dan keahlian)','2025-12-15 08:30:01',NULL),(95,'KP0903','Tugas Belajar/Izin Belajar (permohonan, penetapan tugas belajar/izin belajar)','2025-12-15 08:30:01',NULL),(96,'KP0904','Sertifikasi Profesi (arsip sertifikasi profesi ASN)','2025-12-15 08:30:01',NULL),(97,'KP1001','Pengangkatan JFT (usulan & SK pengangkatan)','2025-12-15 08:30:01',NULL),(98,'KP1002','Kenaikan Jenjang JFT (kenaikan pangkat/jenjang JF)','2025-12-15 08:30:01',NULL),(99,'KP1003','Pemindahan JFT (mutasi antar-jabatan fungsional)','2025-12-15 08:30:01',NULL),(100,'KP1004','Pembebasan Sementara JFT (pembebasan sementara)','2025-12-15 08:30:01',NULL),(101,'KP1005','Pemberhentian JFT (SK pemberhentian JF)','2025-12-15 08:30:01',NULL),(102,'KP1101','Pemberhentian Dengan Hormat (pengajuan & SK pemberhentian DH)','2025-12-15 08:30:01',NULL),(103,'KP1102','Pemberhentian Tidak Dengan Hormat (SK PTDH, proses pemeriksaan)','2025-12-15 08:30:01',NULL),(104,'KP1201','Pensiun Pegawai (berkas pensiun, SK, hak pensiun, Taspen)','2025-12-15 08:30:01',NULL),(105,'PS01','Pengadaan','2025-12-15 08:30:01',NULL),(106,'PS0101','Pengadaan Aset Lancar','2025-12-15 08:30:01',NULL),(107,'PS0102','Pengadaan Aset Tetap','2025-12-15 08:30:01',NULL),(108,'PS0103','Pengadaan Aset Lainnya','2025-12-15 08:30:01',NULL),(109,'PS02','Penggunaan','2025-12-15 08:30:01',NULL),(110,'PS0201','Penetapan Status Penggunaan','2025-12-15 08:30:01',NULL),(111,'PS0202','Penggunaan Sementara / Pengalihan Status / Operasional oleh Pihak Lain','2025-12-15 08:30:01',NULL),(112,'PS03','Pemanfaatan & Pemeliharaan','2025-12-15 08:30:01',NULL),(113,'PS0301','Pemanfaatan BMN (sewa, KSP, BGS/BSG, KSP Infrastruktur, pinjam pakai)','2025-12-15 08:30:01',NULL),(114,'PS0302','Pemeliharaan BMN','2025-12-15 08:30:01',NULL),(115,'PS0303','Pemeriksaan BMN','2025-12-15 08:30:01',NULL),(116,'PS04','Pemindahtanganan','2025-12-15 08:30:01',NULL),(117,'PS0401','Penjualan BMN','2025-12-15 08:30:01',NULL),(118,'PS0402','Tukar Menukar BMN','2025-12-15 08:30:01',NULL),(119,'PS0403','Hibah BMN','2025-12-15 08:30:01',NULL),(120,'PS0404','Penyertaan Modal Pemerintah Pusat','2025-12-15 08:30:01',NULL),(121,'PS05','Penghapusan, Pemusnahan, Pengawasan & Pengendalian','2025-12-15 08:30:01',NULL),(122,'PS0501','Penghapusan & Pemusnahan BMN','2025-12-15 08:30:01',NULL),(123,'PS0502','Pengawasan & Pengendalian BMN','2025-12-15 08:30:01',NULL),(124,'PS06','Penatausahaan','2025-12-15 08:30:01',NULL),(125,'PS0601','Pembukuan BMN (SIMAK-BMN)','2025-12-15 08:30:01',NULL),(126,'PS0602','Inventarisasi & Revaluasi BMN','2025-12-15 08:30:01',NULL),(127,'PS0603','Pelaporan BMN','2025-12-15 08:30:01',NULL),(128,'OR01','Organisasi','2025-12-15 08:30:01',NULL),(129,'OR0101','Struktur Organisasi','2025-12-15 08:30:01',NULL),(130,'OR0102','Tata Kerja & Uraian Tugas Pokok','2025-12-15 08:30:01',NULL),(131,'OR02','Ketatalaksanaan','2025-12-15 08:30:01',NULL),(132,'OR0201','Tata Laksana (Proses Bisnis)','2025-12-15 08:30:01',NULL),(133,'OR0202','Mekanisme Kerja (SOP, SMM, dll.)','2025-12-15 08:30:01',NULL),(134,'OR0203','Reformasi Birokrasi','2025-12-15 08:30:01',NULL),(135,'OR0204','Budaya Organisasi','2025-12-15 08:30:01',NULL),(136,'OR03','Organisasi Non Kedinasan','2025-12-15 08:30:01',NULL),(137,'OR0301','KORPRI','2025-12-15 08:30:01',NULL),(138,'OR0302','Dharma Wanita','2025-12-15 08:30:01',NULL),(139,'OR0303','Koperasi','2025-12-15 08:30:01',NULL),(140,'OR0304','Kerukunan Pensiun PU (KPPU)','2025-12-15 08:30:01',NULL),(141,'PB01','Pengadaan Barang','2025-12-15 08:30:01',NULL),(142,'PB0101','Melalui Pelelangan Umum','2025-12-15 08:30:01',NULL),(143,'PB0102','Melalui Pelelangan Terbatas','2025-12-15 08:30:01',NULL),(144,'PB0103','Melalui Pelelangan Sederhana','2025-12-15 08:30:01',NULL),(145,'PB0104','Melalui Penunjukan Langsung','2025-12-15 08:30:01',NULL),(146,'PB0105','Melalui Pengadaan Langsung','2025-12-15 08:30:01',NULL),(147,'PB0106','Melalui Kontes','2025-12-15 08:30:01',NULL),(148,'PB02','Pengadaan Pekerjaan Konstruksi','2025-12-15 08:30:01',NULL),(149,'PB0201','Melalui Pelelangan Umum','2025-12-15 08:30:01',NULL),(150,'PB0202','Melalui Pelelangan Terbatas','2025-12-15 08:30:01',NULL),(151,'PB0203','Melalui Pemilihan Langsung','2025-12-15 08:30:01',NULL),(152,'PB0204','Melalui Penunjukan Langsung','2025-12-15 08:30:01',NULL),(153,'PB0205','Melalui Pengadaan Langsung','2025-12-15 08:30:01',NULL),(154,'PB03','Pengadaan Jasa Konsultasi','2025-12-15 08:30:01',NULL),(155,'PB0301','Melalui Seleksi Umum','2025-12-15 08:30:01',NULL),(156,'PB0302','Melalui Seleksi Sederhana','2025-12-15 08:30:01',NULL),(157,'PB0303','Melalui Penunjukan Langsung','2025-12-15 08:30:01',NULL),(158,'PB0304','Melalui Pengadaan Langsung','2025-12-15 08:30:01',NULL),(159,'PB0305','Melalui Sayembara','2025-12-15 08:30:01',NULL),(160,'PB04','Pengadaan Jasa Lainnya','2025-12-15 08:30:01',NULL),(161,'PB0401','Melalui Pelelangan Umum','2025-12-15 08:30:01',NULL),(162,'PB0402','Melalui Pelelangan Sederhana','2025-12-15 08:30:01',NULL),(163,'PB0403','Melalui Penunjukan Langsung','2025-12-15 08:30:01',NULL),(164,'PB0404','Melalui Pengadaan Langsung','2025-12-15 08:30:01',NULL),(165,'PB0405','Melalui Sayembara','2025-12-15 08:30:01',NULL),(166,'PR0101','Penyusunan Rencana & Program Unit Kerja (rencana & program tiap unit organisasi)','2025-12-15 08:30:01',NULL),(167,'PR0102','Strategi & Dokumen Perencanaan Pembangunan (RPJP, RPJM, Renstra, RKT, Renja K/L, Musrenbangnas, rencana daerah khusus)','2025-12-15 08:30:01',NULL),(168,'PR0103','Penetapan Kinerja Tahunan (penyusunan & penetapan kinerja tahunan)','2025-12-15 08:30:01',NULL),(169,'PR0201','Penyusunan Penganggaran (KAK, RAB, RKA-KL, DIPA, POK) (termasuk usulan, revisi, pagu indikatif, pagu anggaran, alokasi)','2025-12-15 08:30:01',NULL),(170,'PR0202','Sistem Penganggaran (persiapan bahan, koordinasi, pengelolaan sistem penganggaran)','2025-12-15 08:30:01',NULL),(171,'PR0203','Analisis Data Penganggaran (analisis, koordinasi, fasilitasi pengesahan dokumen anggaran)','2025-12-15 08:30:01',NULL),(172,'PR0204','Fasilitasi Pendanaan Daerah (DAK) (analisis, penyusunan, pengelolaan, evaluasi, pelaporan DAK)','2025-12-15 08:30:01',NULL),(173,'PR0301','Pemantauan Kinerja & Anggaran (pemantauan, pengelolaan data kinerja & anggaran)','2025-12-15 08:30:01',NULL),(174,'PR0302','Evaluasi Kinerja & Pelaksanaan Anggaran (siapkan bahan evaluasi)','2025-12-15 08:30:01',NULL),(175,'PR0303','Pelaporan Realisasi (Bulanan/Triwulan/Tahunan) (termasuk laporan program prioritas, strategis, instruksi Menteri/Presiden)','2025-12-15 08:30:01',NULL),(176,'PR0304','Laporan LAKIP (pengolahan LAKIP)','2025-12-15 08:30:01',NULL),(177,'PW0101','Pemeriksaan Administrasi Umum (mencakup identitas, tugas pokok, organisasi, kepegawaian, BMN, pengadaan, rencana → laporan)','2025-12-15 08:30:01',NULL),(178,'PW0102','Pemeriksaan Keuangan (kas bendahara, bukti penerimaan/pengeluaran, laporan keuangan, LRA, Neraca, CALK, output DIPA)','2025-12-15 08:30:01',NULL),(179,'PW0103','Pemeriksaan Kinerja (perencanaan → pelelangan → kontrak → progres → pengendalian → kualitas → manfaat)','2025-12-15 08:30:01',NULL),(180,'PW0104','Pemeriksaan Khusus (TPK, pelanggaran disiplin, KKN, pengadaan, tindak pidana umum, kasus berindikasi masalah)','2025-12-15 08:30:01',NULL),(181,'PW0105','Reviu Keuangan dan Kegiatan (reviu laporan keuangan semesteran & tahunan)','2025-12-15 08:30:01',NULL),(182,'PW0201','Laporan Hasil Audit (LHA) APIP (ikhtisar LHA ke KemenPAN-RB, BPKP, UKP4, tanggapan BPK RI)','2025-12-15 08:30:01',NULL),(183,'PW0202','Tindak Lanjut Hasil Audit (tindak lanjut LHA Itjen, BPK, dan LHA lainnya)','2025-12-15 08:30:01',NULL),(184,'PW0203','Tuntutan Ganti Rugi (TGR/TP) (pemantauan kerugian negara → rencana → penyelesaian)','2025-12-15 08:30:01',NULL),(185,'PW0204','Evaluasi Kegiatan/Kinerja (evaluasi LAKIP, RB, dan evaluasi lainnya)','2025-12-15 08:30:01',NULL),(186,'PW0205','Penerapan Early Warning System (EWS) (fasilitasi dan pemantauan pengelolaan keuangan → laporan)','2025-12-15 08:30:01',NULL),(187,'PW0301','Pengaduan Internal (pelaporan, penanganan, penyelesaian)','2025-12-15 08:30:01',NULL),(188,'PW0302','Pengaduan Eksternal (pelaporan, penanganan, penyelesaian)','2025-12-15 08:30:01',NULL),(189,'PW0401','Pendampingan Pengawasan (pendampingan EPPD, bencana, BMN, dll)','2025-12-15 08:30:01',NULL),(190,'PW0402','Bimbingan dan Konsultasi Pengawasan (rencana → pelaksanaan → laporan)','2025-12-15 08:30:01',NULL),(191,'PW0403','Pengelolaan & Pemaparan Hasil Pengawasan (pengelolaan data hasil pengawasan & pemaparan)','2025-12-15 08:30:01',NULL),(192,'PW0404','Sosialisasi Pengawasan (sosialisasi, forum komunikasi APIP, dll)','2025-12-15 08:30:01',NULL),(193,'UM0101','Peringatan Hari Kemerdekaan/Hari Besar Nasional/Hari Bhakti PUPR','2025-12-15 08:30:01',NULL),(194,'UM0102','Rapat/Raker/Rakor/Rapat Teknis/Konsultasi Regional','2025-12-15 08:30:01',NULL),(195,'UM0103','Administrasi Perjalanan Dinas','2025-12-15 08:30:01',NULL),(196,'UM0104','Penghargaan/Kenang-kenangan/Hadiah/Belasungkawa','2025-12-15 08:30:01',NULL),(197,'UM0105','Pengaturan Jam Kerja','2025-12-15 08:30:01',NULL),(198,'UM0201','Penciptaan Tata Naskah Dinas (Surat Masuk/Keluar, Disposisi)','2025-12-15 08:30:01',NULL),(199,'UM0202','Pengelolaan Arsip Dinamis (Pemberkasan, Penataan, Penyimpanan, Alih Media)','2025-12-15 08:30:01',NULL),(200,'UM0203','Penyusutan Arsip (Pemindahan, Penyerahan, Pemusnahan)','2025-12-15 08:30:01',NULL),(201,'UM0204','Pembinaan Kearsipan (Pedoman, Bimtek, Sosialisasi, Lomba Arsip)','2025-12-15 08:30:01',NULL),(202,'UM0205','Fasilitasi Arsip Statis (Inventarisasi, Ikhtisar, Penyerahan ke ANRI)','2025-12-15 08:30:01',NULL),(203,'UM0301','Penggunaan Sarana/Prasarana Kantor (Gedung, Aula, Ruang Rapat, KDO, Rumah Dinas)','2025-12-15 08:30:01',NULL),(204,'UM0302','Pemeliharaan Sarana/Prasarana Kantor','2025-12-15 08:30:01',NULL),(205,'UM0303','Jaringan Listrik, Air, Telepon (Pemasangan, Perbaikan, Pemeliharaan)','2025-12-15 08:30:01',NULL),(206,'UM0401','Pengamanan dan Pengawalan Gedung/Proyek/Rumah Jabatan','2025-12-15 08:30:01',NULL),(207,'UM0402','Izin Keluar-Masuk BMN','2025-12-15 08:30:01',NULL),(208,'UM0403','Kehilangan BMN/Barang di Area Kerja','2025-12-15 08:30:01',NULL),(209,'UM0404','Kerusakan BMN/Barang (Termasuk Bencana/Kebakaran)','2025-12-15 08:30:01',NULL),(210,'UM0405','Kecelakaan di Area Kantor/Proyek/Rumah Jabatan','2025-12-15 08:30:01',NULL),(211,'UM0406','Penertiban Perparkiran Pegawai/Pejabat/Tamu','2025-12-15 08:30:01',NULL),(212,'UM0407','Seragam/Pakaian Dinas (Ketentuan, Pembuatan, Pembagian)','2025-12-15 08:30:01',NULL),(213,'UM0501','Pelayanan Poliklinik Pegawai','2025-12-15 08:30:01',NULL),(214,'UM0502','Penyelenggaraan Perpustakaan','2025-12-15 08:30:01',NULL),(215,'UM0503','Penyelenggaraan Tempat Penitipan Anak','2025-12-15 08:30:01',NULL),(216,'UM0504','Penyelenggaraan Rumah Pintar','2025-12-15 08:30:01',NULL),(217,'UM0505','Administrasi/Penyelenggaraan Koperasi','2025-12-15 08:30:01',NULL),(218,'UM0601','Upacara/Acara Kedinasan (Pelantikan, Sertijab, Hari Besar)','2025-12-15 08:30:01',NULL),(219,'UM0602','Kunjungan Dinas Dalam/Luar Negeri','2025-12-15 08:30:01',NULL),(220,'UM0603','Agenda Pimpinan (Perencanaan, Penjadwalan, Pelaksanaan)','2025-12-15 08:30:01',NULL),(221,'UM0701','Kegiatan Kerohanian (Rutin/Insidental)','2025-12-15 08:30:01',NULL),(222,'UM0702','Kegiatan Olahraga','2025-12-15 08:30:01',NULL),(223,'UM0703','Kegiatan Kesenian','2025-12-15 08:30:01',NULL),(224,'UM0704','Pengumpulan/Penyaluran Sumbangan','2025-12-15 08:30:01',NULL);
/*!40000 ALTER TABLE `tb_kode_arsip` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_riwayat_arsip`
--

LOCK TABLES `tb_riwayat_arsip` WRITE;
/*!40000 ALTER TABLE `tb_riwayat_arsip` DISABLE KEYS */;
INSERT INTO `tb_riwayat_arsip` VALUES (31,4,1,'Upload','Arsip diupload via bulk upload','10.10.30.120','2025-12-12 14:45:39'),(32,5,1,'Upload','Arsip diupload via bulk upload','10.10.30.120','2025-12-12 14:45:39'),(35,6,1,'Upload','Arsip diupload via bulk upload','10.10.30.120','2025-12-12 14:48:36'),(36,7,1,'Upload','Arsip diupload via bulk upload','10.10.30.120','2025-12-12 14:48:36'),(37,8,1,'Upload','Arsip diupload via bulk upload','10.10.30.120','2025-12-12 14:48:36');
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

-- Dump completed on 2025-12-15  9:15:09
