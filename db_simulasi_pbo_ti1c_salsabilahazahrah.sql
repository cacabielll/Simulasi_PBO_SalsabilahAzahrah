-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 17, 2026 at 02:54 AM
-- Server version: 8.0.30
-- PHP Version: 8.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_simulasi_pbo_ti1c_salsabilahazahrah`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_pendaftaran`
--

CREATE TABLE `tabel_pendaftaran` (
  `id_pendaftaran` int NOT NULL,
  `nama_calon` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `asal_sekolah` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nilai_ujian` decimal(5,2) NOT NULL,
  `biaya_pendaftaran_dasar` int NOT NULL,
  `jalur_pendaftaran` enum('Reguler','Prestasi','Kedinasan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `pilihan_prodi` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lokasi_kampus` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_prestasi` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tingkat_prestasi` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sk_ikatan_dinas` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instansi_sponsor` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tabel_pendaftaran`
--

INSERT INTO `tabel_pendaftaran` (`id_pendaftaran`, `nama_calon`, `asal_sekolah`, `nilai_ujian`, `biaya_pendaftaran_dasar`, `jalur_pendaftaran`, `pilihan_prodi`, `lokasi_kampus`, `jenis_prestasi`, `tingkat_prestasi`, `sk_ikatan_dinas`, `instansi_sponsor`) VALUES
(1, 'Andi Saputra', 'SMAN 1 Cilacap', '82.50', 350000, 'Reguler', 'Teknik Informatika', 'Kampus Purwokerto', NULL, NULL, NULL, NULL),
(2, 'Budi Hartono', 'SMKN 2 Banyumas', '76.00', 350000, 'Reguler', 'Sistem Informasi', 'Kampus Purwokerto', NULL, NULL, NULL, NULL),
(3, 'Citra Dewi', 'SMAN 3 Purbalingga', '88.75', 350000, 'Reguler', 'Akuntansi', 'Kampus Purwokerto', NULL, NULL, NULL, NULL),
(4, 'Dian Pratama', 'MAN 1 Banjarnegara', '79.25', 350000, 'Reguler', 'Manajemen', 'Kampus Purwokerto', NULL, NULL, NULL, NULL),
(5, 'Eka Fitriani', 'SMAN 2 Kebumen', '73.00', 350000, 'Reguler', 'Pendidikan Bahasa', NULL, NULL, NULL, NULL, NULL),
(6, 'Fajar Nugroho', 'SMKN 1 Gombong', '85.50', 350000, 'Reguler', 'Teknik Elektro', 'Kampus Kebumen', NULL, NULL, NULL, NULL),
(7, 'Galuh Puspitasari', 'SMAN 1 Purwokerto', '91.00', 350000, 'Reguler', 'Kedokteran', 'Kampus Purwokerto', NULL, NULL, NULL, NULL),
(8, 'Hendra Kusuma', 'SMAN 4 Cilacap', '78.50', 350000, 'Reguler', 'Hukum', 'Kampus Purwokerto', NULL, NULL, NULL, NULL),
(9, 'Indah Lestari', 'SMAN 1 Purbalingga', '90.00', 400000, 'Prestasi', NULL, NULL, 'Olimpiade Matematika', 'Nasional', NULL, NULL),
(10, 'Joko Widodo', 'SMKN 3 Banyumas', '87.50', 400000, 'Prestasi', NULL, NULL, 'Lomba Desain Grafis', 'Provinsi', NULL, NULL),
(11, 'Kartika Sari', 'SMAN 2 Cilacap', '92.25', 400000, 'Prestasi', NULL, NULL, 'Olimpiade Fisika', 'Nasional', NULL, NULL),
(12, 'Lukman Hakim', 'MA Negeri Purwokerto', '84.00', 400000, 'Prestasi', NULL, NULL, 'Lomba Karya Ilmiah', NULL, NULL, NULL),
(13, 'Maya Anggraini', 'SMAN 1 Banjarnegara', '89.75', 400000, 'Prestasi', NULL, NULL, 'Olimpiade Biologi', 'Provinsi', NULL, NULL),
(14, 'Novan Setiawan', 'SMKN 2 Kebumen', '81.00', 400000, 'Prestasi', NULL, NULL, 'Lomba Vokasi', 'Nasional', NULL, NULL),
(15, 'Okta Rahayu', 'SMAN 3 Cilacap', '86.50', 400000, 'Prestasi', NULL, NULL, 'Lomba Debat Bahasa', 'Provinsi', NULL, NULL),
(16, 'Putra Firmansyah', 'SMAN 1 Purwokerto', '88.00', 500000, 'Kedinasan', NULL, NULL, NULL, NULL, 'SK/2024/DINAS/001', 'Dinas Pendidikan Jateng'),
(17, 'Qori Handayani', 'SMKN 1 Banyumas', '85.00', 500000, 'Kedinasan', NULL, NULL, NULL, NULL, 'SK/2024/DINAS/002', 'BPKAD Kabupaten Cilacap'),
(18, 'Reza Prasetyo', 'SMAN 2 Purbalingga', '82.50', 500000, 'Kedinasan', NULL, NULL, NULL, NULL, 'SK/2024/DINAS/003', NULL),
(19, 'Sinta Maharani', 'MAN 2 Banjarnegara', '90.50', 500000, 'Kedinasan', NULL, NULL, NULL, NULL, 'SK/2024/DINAS/004', 'Kementerian PUPR'),
(20, 'Teguh Wibowo', 'SMAN 3 Kebumen', '87.00', 500000, 'Kedinasan', NULL, NULL, NULL, NULL, 'SK/2024/DINAS/005', 'Dinas Kesehatan Jateng');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_pendaftaran`
--
ALTER TABLE `tabel_pendaftaran`
  ADD PRIMARY KEY (`id_pendaftaran`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabel_pendaftaran`
--
ALTER TABLE `tabel_pendaftaran`
  MODIFY `id_pendaftaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
