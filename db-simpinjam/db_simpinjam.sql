-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2023 at 09:51 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_simpinjam`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_barang`
--

CREATE TABLE `tb_barang` (
  `id` int(11) NOT NULL,
  `kode` varchar(256) NOT NULL,
  `stok` int(11) NOT NULL,
  `tempat` varchar(50) NOT NULL,
  `createDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_barang`
--

INSERT INTO `tb_barang` (`id`, `kode`, `stok`, `tempat`, `createDate`) VALUES
(29, 'LCD Proyektor', 3, 'Ruang BAUPK Lantai 5', '2023-10-03 14:02:44'),
(30, 'Laptop', 8, 'Ruang BAUPK Lantai 5', '2023-10-03 14:03:08'),
(31, 'Sound System', 5, 'Perlengkapan Lantai Bawah', '2023-10-03 14:03:24'),
(32, 'Kabel Roll', 17, 'Perlengkapan Lantai Bawah', '2023-10-04 09:43:06'),
(33, 'Tatakan Bendera isi 3', 3, 'Perlengkapan Lantai Bawah', '2023-10-04 09:44:33'),
(34, 'Tatakan Bendera isi 5', 2, 'Perlengkapan Lantai Bawah', '2023-10-04 09:44:49'),
(36, 'Bendera Merah Putih', 2, 'Perlengkapan Lantai 1', '2023-10-04 10:43:48');

-- --------------------------------------------------------

--
-- Table structure for table `tb_riwayat`
--

CREATE TABLE `tb_riwayat` (
  `id` int(11) NOT NULL,
  `kode` varchar(256) NOT NULL,
  `label` varchar(50) NOT NULL,
  `jenis` varchar(16) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `createDate` datetime NOT NULL,
  `unit` varchar(50) NOT NULL,
  `noTlp` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_riwayat`
--

INSERT INTO `tb_riwayat` (`id`, `kode`, `label`, `jenis`, `jumlah`, `createDate`, `unit`, `noTlp`) VALUES
(57, 'Bendera Merah Putih', 'A', 'Peminjaman', 1, '2023-10-04 14:37:13', 'BAUPK', '085447788958'),
(58, 'Bendera Merah Putih', 'A', 'Pengembalian', 1, '2023-10-04 14:37:41', 'BAUPK', '087754425895');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id`, `nama`, `username`, `password`, `level`, `createDate`) VALUES
(1, 'Administrator', 'admin', '3a7df10f621c24195f0d54540cca9855', 'Admin', '2023-10-02 19:14:02'),
(2, 'User', 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'User', '2020-12-02 19:14:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_barang`
--
ALTER TABLE `tb_barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kode` (`kode`);

--
-- Indexes for table `tb_riwayat`
--
ALTER TABLE `tb_riwayat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK01` (`kode`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_barang`
--
ALTER TABLE `tb_barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `tb_riwayat`
--
ALTER TABLE `tb_riwayat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_riwayat`
--
ALTER TABLE `tb_riwayat`
  ADD CONSTRAINT `FK01` FOREIGN KEY (`kode`) REFERENCES `tb_barang` (`kode`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
