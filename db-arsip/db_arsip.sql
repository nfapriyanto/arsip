-- phpMyAdmin SQL Dump
-- Database: db_arsip
-- Sistem Penyimpanan Arsip Digital
-- 

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_arsip`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori_arsip`
--

CREATE TABLE `tb_kategori_arsip` (
  `id` int(11) NOT NULL,
  `nama` varchar(256) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `parent_id` int(11) NULL DEFAULT NULL COMMENT 'ID kategori parent untuk kategori bertingkat',
  `createDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_kategori_arsip`
--

INSERT INTO `tb_kategori_arsip` (`id`, `nama`, `deskripsi`, `createDate`) VALUES
(1, 'Surat Masuk', 'Arsip untuk surat-surat yang masuk', NOW()),
(2, 'Surat Keluar', 'Arsip untuk surat-surat yang keluar', NOW()),
(3, 'Dokumen Kepegawaian', 'Arsip dokumen kepegawaian', NOW()),
(4, 'Dokumen Keuangan', 'Arsip dokumen keuangan', NOW()),
(5, 'Dokumen Umum', 'Arsip dokumen umum lainnya', NOW());

-- --------------------------------------------------------

--
-- Table structure for table `tb_arsip`
--

CREATE TABLE `tb_arsip` (
  `id` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `no_berkas` varchar(100) DEFAULT NULL,
  `no_urut` int(11) DEFAULT NULL,
  `kode` varchar(100) DEFAULT NULL,
  `indeks_pekerjaan` varchar(500) DEFAULT NULL,
  `uraian_masalah_kegiatan` text DEFAULT NULL,
  `tahun` YEAR DEFAULT NULL,
  `jumlah_berkas` int(11) DEFAULT 1,
  `asli_kopi` enum('Asli','Kopi') DEFAULT NULL COMMENT 'Asli atau Kopi',
  `box` varchar(100) DEFAULT NULL COMMENT 'Nomor Box',
  `klasifikasi_keamanan` varchar(100) DEFAULT NULL COMMENT 'Klasifikasi keamanan dan akses arsip dinamis',
  `nama_file` varchar(500) NOT NULL,
  `path_file` varchar(1000) NOT NULL,
  `ukuran_file` bigint(20) DEFAULT NULL COMMENT 'Ukuran file dalam bytes',
  `tipe_file` varchar(100) DEFAULT NULL COMMENT 'MIME type file',
  `createDate` datetime NOT NULL,
  `updateDate` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'ID user yang membuat'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_riwayat_arsip`
--

CREATE TABLE `tb_riwayat_arsip` (
  `id` int(11) NOT NULL,
  `arsip_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `aksi` enum('Upload','Download','View','Update','Delete') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `createDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id` int(11) NOT NULL,
  `nama` varchar(256) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `level` varchar(16) NOT NULL,
  `createDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id`, `nama`, `username`, `password`, `level`, `createDate`) VALUES
(1, 'Administrator', 'admin', MD5('admin'), 'Admin', NOW()),
(2, 'User', 'user', MD5('user'), 'User', NOW());

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_kategori_arsip`
--
ALTER TABLE `tb_kategori_arsip`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_nama` (`nama`),
  ADD KEY `idx_parent_id` (`parent_id`);

--
-- Indexes for table `tb_arsip`
--
ALTER TABLE `tb_arsip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`),
  ADD KEY `idx_no_berkas` (`no_berkas`),
  ADD KEY `idx_kode` (`kode`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_judul` (`judul`(255));

--
-- Indexes for table `tb_riwayat_arsip`
--
ALTER TABLE `tb_riwayat_arsip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `arsip_id` (`arsip_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_kategori_arsip`
--
ALTER TABLE `tb_kategori_arsip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_arsip`
--
ALTER TABLE `tb_arsip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_riwayat_arsip`
--
ALTER TABLE `tb_riwayat_arsip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_arsip`
--
ALTER TABLE `tb_arsip`
  ADD CONSTRAINT `fk_arsip_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `tb_kategori_arsip` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_arsip_user` FOREIGN KEY (`created_by`) REFERENCES `tb_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tb_riwayat_arsip`
--
ALTER TABLE `tb_riwayat_arsip`
  ADD CONSTRAINT `fk_riwayat_arsip` FOREIGN KEY (`arsip_id`) REFERENCES `tb_arsip` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_riwayat_user` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

