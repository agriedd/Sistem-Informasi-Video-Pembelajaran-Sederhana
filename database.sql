-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2022 at 09:36 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `video_pembelajaran`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` bigint(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('l','p') NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `kata_sandi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama`, `jenis_kelamin`, `tanggal_lahir`, `email`, `kata_sandi`) VALUES
(1, 'admin', 'l', '2001-01-01', 'admin@vidpen.com', '5f4dcc3b5aa765d61d8327deb882cf99');

-- --------------------------------------------------------

--
-- Table structure for table `balasan`
--

CREATE TABLE `balasan` (
  `id_balasan` bigint(20) NOT NULL,
  `balasan` varchar(190) NOT NULL,
  `id_pertanyaan` bigint(20) NOT NULL,
  `id_pengguna` bigint(20) NOT NULL,
  `tanggal` datetime NOT NULL,
  `dukungan_naik` bigint(20) UNSIGNED NOT NULL,
  `dukungan_turun` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `balasan`
--

INSERT INTO `balasan` (`id_balasan`, `balasan`, `id_pertanyaan`, `id_pengguna`, `tanggal`, `dukungan_naik`, `dukungan_turun`) VALUES
(1, '64-bit quad core CPU with SSE2 support.\r\n8 GB RAM.\r\nFull HD display\r\nMouse, trackpad or pen+tablet.\r\nGraphics card with 2 GB RAM, OpenGL 4.3.\r\nLess than 10 year old.\r\nreferensi: https://www.', 1, 1, '2022-12-03 15:52:14', 0, 0),
(2, 'sedangkan untuk rekomendasinya:\r\n64-bit eight core CPU\r\n32 GB RAM\r\n2560×1440 display\r\nThree button mouse or pen+tablet\r\nGraphics card with 8 GB RAM', 1, 1, '2022-12-03 15:53:53', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` bigint(20) NOT NULL,
  `nama_kelas` varchar(100) NOT NULL,
  `id_admin` bigint(20) NOT NULL,
  `tanggal` datetime NOT NULL,
  `deskripsi_singkat` varchar(255) DEFAULT NULL,
  `latar` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `id_admin`, `tanggal`, `deskripsi_singkat`, `latar`, `keterangan`) VALUES
(2, 'Mengenal HTML 5', 1, '2022-12-03 15:17:15', 'Mengenal HTML 5', '/img/kelas/f6eEPEUVNqshq.jpg', 'Mengenal HTML 5 Sekolah Koding'),
(3, 'Database dengan MYSQLi dan PHP', 1, '2022-12-03 15:28:25', 'Database dengan MYSQLi dan PHP', '/img/kelas/vFpWrj34Vogmq.jpg', 'Database dengan MYSQLi dan PHP - Sekolah Koding'),
(4, 'Fotografi dengan smartphone', 1, '2022-12-03 15:34:53', 'Belajar skill fotografi dengan smartphone', '/img/kelas/that-s-her-business-9HOQ-Ita--U-unsplash.jpg', ''),
(5, 'Tutorial dasar blender 2.9', 1, '2022-12-03 15:36:45', 'Tutorial dasar blender 2.9', '/img/kelas/n_szaZBDvQ4mq.jpg', 'Tutorial dasar blender 2.9'),
(6, 'TIPS & ORCHIDCARE', 1, '2022-12-03 15:40:35', 'TIPS & ORCHIDCARE', '/img/kelas/KMooxG7nQPAhq.jpg', '');

-- --------------------------------------------------------

--
-- Table structure for table `kuis`
--

CREATE TABLE `kuis` (
  `id_kuis` bigint(20) NOT NULL,
  `pertanyaan_kuis` varchar(255) NOT NULL,
  `id_video` bigint(20) NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kuis`
--

INSERT INTO `kuis` (`id_kuis`, `pertanyaan_kuis`, `id_video`, `tanggal`) VALUES
(1, 'Apa Kepanjangan dari HTML?', 3, '2022-12-03 15:56:49');

-- --------------------------------------------------------

--
-- Table structure for table `kuis_pengguna`
--

CREATE TABLE `kuis_pengguna` (
  `id_kuis_pengguna` bigint(20) NOT NULL,
  `id_video` bigint(20) NOT NULL,
  `id_pengguna` bigint(20) NOT NULL,
  `tanggal` datetime NOT NULL,
  `skor` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kuis_pengguna`
--

INSERT INTO `kuis_pengguna` (`id_kuis_pengguna`, `id_video`, `id_pengguna`, `tanggal`, `skor`) VALUES
(1, 3, 1, '2022-12-03 15:58:35', '100.00');

-- --------------------------------------------------------

--
-- Table structure for table `opsi_jawaban`
--

CREATE TABLE `opsi_jawaban` (
  `id_opsi_jawaban` bigint(20) NOT NULL,
  `id_kuis` bigint(20) NOT NULL,
  `jawaban` varchar(255) NOT NULL,
  `status_benar` enum('benar','salah') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `opsi_jawaban`
--

INSERT INTO `opsi_jawaban` (`id_opsi_jawaban`, `id_kuis`, `jawaban`, `status_benar`) VALUES
(1, 1, 'HyperText Markup Language', 'benar'),
(2, 1, 'HyperTest Markup Language', 'salah'),
(3, 1, 'HyperTest Markover Language', 'salah'),
(4, 1, 'HyperText Markover Language', 'salah');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` bigint(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('l','p') NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `kata_sandi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `nama`, `jenis_kelamin`, `tanggal_lahir`, `email`, `kata_sandi`) VALUES
(1, 'Edd', 'l', '2020-01-01', 'pengguna@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99');

-- --------------------------------------------------------

--
-- Table structure for table `pertanyaan`
--

CREATE TABLE `pertanyaan` (
  `id_pertanyaan` bigint(20) NOT NULL,
  `pertanyaan` varchar(190) NOT NULL,
  `id_video` bigint(20) NOT NULL,
  `id_pengguna` bigint(20) NOT NULL,
  `tanggal` datetime NOT NULL,
  `dukungan_naik` bigint(20) UNSIGNED NOT NULL,
  `dukungan_turun` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pertanyaan`
--

INSERT INTO `pertanyaan` (`id_pertanyaan`, `pertanyaan`, `id_video`, `id_pengguna`, `tanggal`, `dukungan_naik`, `dukungan_turun`) VALUES
(1, 'apa-apa saja minimum spesifikasi untuk menggunakan aplikasi blender?', 23, 1, '2022-12-03 15:50:00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `video_pembelajaran`
--

CREATE TABLE `video_pembelajaran` (
  `id_video` bigint(20) NOT NULL,
  `judul_video` varchar(190) NOT NULL,
  `id_kelas` bigint(20) NOT NULL,
  `tanggal` datetime NOT NULL,
  `video` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `urutan` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `video_pembelajaran`
--

INSERT INTO `video_pembelajaran` (`id_video`, `judul_video`, `id_kelas`, `tanggal`, `video`, `keterangan`, `urutan`) VALUES
(3, 'Belajar HTML5 - intro', 2, '2022-12-03 15:17:51', 'https://www.youtube.com/watch?v=f6eEPEUVNqs&list=PLCZlgfAG0GXBPga_3SQ_KwLtAYItgn490&index=1', 'Belajar HTML5 - intro', 0),
(4, '1 menjelaskan struktur dasar', 2, '2022-12-03 15:18:16', 'https://www.youtube.com/watch?v=kgvFp-IBz8I&list=PLCZlgfAG0GXBPga_3SQ_KwLtAYItgn490&index=2', '1 menjelaskan struktur dasar', 0),
(5, '2 strutkur baru html5', 2, '2022-12-03 15:22:23', 'https://www.youtube.com/watch?v=ZTG1JyB-OB0&list=PLCZlgfAG0GXBPga_3SQ_KwLtAYItgn490&index=3', '2 strutkur baru html5', 0),
(6, '3 caption pada gambar', 2, '2022-12-03 15:22:54', 'https://www.youtube.com/watch?v=eZYkTvPHLGA&list=PLCZlgfAG0GXBPga_3SQ_KwLtAYItgn490&index=4', '3 caption pada gambar', 0),
(7, '4 dialog dan detail', 2, '2022-12-03 15:23:15', 'https://www.youtube.com/watch?v=MdLIQ7hDRII&list=PLCZlgfAG0GXBPga_3SQ_KwLtAYItgn490&index=5', '4 dialog dan detail', 0),
(8, '5 input baru di html5', 2, '2022-12-03 15:23:57', 'https://www.youtube.com/watch?v=R3PW1x-CJjE&list=PLCZlgfAG0GXBPga_3SQ_KwLtAYItgn490&index=6', '5 input baru di html5', 0),
(9, '6 memasukkan video pada website', 2, '2022-12-03 15:24:26', 'https://www.youtube.com/watch?v=_tha-nVgZwA&list=PLCZlgfAG0GXBPga_3SQ_KwLtAYItgn490&index=7', '6 memasukkan video pada website', 0),
(10, '7 menggunakan audio', 2, '2022-12-03 15:25:12', 'https://www.youtube.com/watch?v=KUJ6BIPWq2Q&list=PLCZlgfAG0GXBPga_3SQ_KwLtAYItgn490&index=8', '7 menggunakan audio', 0),
(11, '8 ke mana', 2, '2022-12-03 15:25:33', 'https://www.youtube.com/watch?v=2DBmkUtWeOw&list=PLCZlgfAG0GXBPga_3SQ_KwLtAYItgn490&index=9', '8 ke mana', 0),
(12, 'Belajar database MySQLi dengan PHP - intro', 3, '2022-12-03 15:29:07', 'https://www.youtube.com/watch?v=vFpWrj34Vog&list=PLCZlgfAG0GXAQPgBd7tNutH1D1QRgklW2&index=1', 'Belajar database MySQLi dengan PHP - intro', 0),
(13, '1 membuat database', 3, '2022-12-03 15:29:36', 'https://www.youtube.com/watch?v=ItPjdAaNuRc&list=PLCZlgfAG0GXAQPgBd7tNutH1D1QRgklW2&index=2', '1 membuat database', 0),
(14, '2 menyambungkan database', 3, '2022-12-03 15:30:02', 'https://www.youtube.com/watch?v=WqcAts_6czg&list=PLCZlgfAG0GXAQPgBd7tNutH1D1QRgklW2&index=3', '2 menyambungkan database', 0),
(15, '3 query pertama', 3, '2022-12-03 15:30:29', 'https://www.youtube.com/watch?v=Tgx8MLQis1M&list=PLCZlgfAG0GXAQPgBd7tNutH1D1QRgklW2&index=4', '3 query pertama', 0),
(16, '4 menampilkan dan memilih data', 3, '2022-12-03 15:30:50', 'https://www.youtube.com/watch?v=Cynsf_NDxMs&list=PLCZlgfAG0GXAQPgBd7tNutH1D1QRgklW2&index=5', '4 menampilkan dan memilih data', 0),
(17, '5 menfilter data', 3, '2022-12-03 15:31:18', 'https://www.youtube.com/watch?v=nh7Yv6dAv-E&list=PLCZlgfAG0GXAQPgBd7tNutH1D1QRgklW2&index=6', '5 menfilter data', 0),
(18, '6 memasukkan data', 3, '2022-12-03 15:31:49', 'https://www.youtube.com/watch?v=V6tTrWjUHKY&list=PLCZlgfAG0GXAQPgBd7tNutH1D1QRgklW2&index=7', '6 memasukkan data', 0),
(19, '7 menghapus data', 3, '2022-12-03 15:32:07', 'https://www.youtube.com/watch?v=bczMXQj5TY0&list=PLCZlgfAG0GXAQPgBd7tNutH1D1QRgklW2&index=8', '7 menghapus data', 0),
(20, '8 mengubah data', 3, '2022-12-03 15:32:43', 'https://www.youtube.com/watch?v=i8P3ZKVAtaY&list=PLCZlgfAG0GXAQPgBd7tNutH1D1QRgklW2&index=9', '8 mengubah data', 0),
(21, '9 selesai', 3, '2022-12-03 15:33:02', 'https://www.youtube.com/watch?v=Hqtabal1nwA&list=PLCZlgfAG0GXAQPgBd7tNutH1D1QRgklW2&index=10', '9 selesai', 0),
(22, 'Travel Photography pakai HP cukup?', 4, '2022-12-03 15:35:14', 'https://www.youtube.com/watch?v=X86NRNvZpI4&list=PLvMV00lPIdEVWV8j9y3Yn5rFai1d5qMWZ', 'Travel Photography pakai HP cukup?', 0),
(23, 'Tutorial dasar blender 2 9 (Bagian 1)', 5, '2022-12-03 15:37:26', 'https://www.youtube.com/watch?v=n_szaZBDvQ4&list=PL1mw9iOvdPzH4k1vmhz2JvgYFZpmdOGvw', 'Tutorial dasar blender 2 9 (Bagian 1) - \r\nDody Priyatmono', 0),
(24, 'Tutorial dasar blender 2.9 (Bagian 2)', 5, '2022-12-03 15:37:49', 'https://www.youtube.com/watch?v=itTQX22U0qs&list=PL1mw9iOvdPzH4k1vmhz2JvgYFZpmdOGvw&index=2', 'Tutorial dasar blender 2.9 (Bagian 2)', 0),
(25, 'Tutorial dasar blender 2.9 (Bagian3) Rigging dan animasi', 5, '2022-12-03 15:38:16', 'https://www.youtube.com/watch?v=S-8KR3ZKAyw&list=PL1mw9iOvdPzH4k1vmhz2JvgYFZpmdOGvw&index=3', 'Tutorial dasar blender 2.9 (Bagian3) Rigging dan animasi', 0),
(26, 'Tutorial blender Trend 2021 membuat elemen 3d ciamik', 5, '2022-12-03 15:38:50', 'https://www.youtube.com/watch?v=MYSEHrDfQI4&list=PL1mw9iOvdPzH4k1vmhz2JvgYFZpmdOGvw&index=4', 'Tutorial blender Trend 2021 membuat elemen 3d ciamik', 0),
(27, 'ALOE VERA IS A POTENT FERTILIZER FOR ORCHID ROOTS.', 6, '2022-12-03 15:41:10', 'https://www.youtube.com/watch?v=KMooxG7nQPA&list=PLgndsgm3rjWVn4yuXGbljqzB6cjiKiJ2N&index=1', 'ALOE VERA IS A POTENT FERTILIZER FOR ORCHID ROOTS.', 0),
(28, 'THIS LITTLE WAY CAN BE HEALTHY ORCHIDS AND FLOWERING ALL THE TIME.', 6, '2022-12-03 15:41:34', 'THIS LITTLE WAY CAN BE HEALTHY ORCHIDS AND FLOWERING ALL THE TIME.', 'THIS LITTLE WAY CAN BE HEALTHY ORCHIDS AND FLOWERING ALL THE TIME.', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `balasan`
--
ALTER TABLE `balasan`
  ADD PRIMARY KEY (`id_balasan`),
  ADD KEY `id_pertanyaan` (`id_pertanyaan`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `kuis`
--
ALTER TABLE `kuis`
  ADD PRIMARY KEY (`id_kuis`),
  ADD KEY `id_video` (`id_video`);

--
-- Indexes for table `kuis_pengguna`
--
ALTER TABLE `kuis_pengguna`
  ADD PRIMARY KEY (`id_kuis_pengguna`),
  ADD KEY `id_video` (`id_video`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- Indexes for table `opsi_jawaban`
--
ALTER TABLE `opsi_jawaban`
  ADD PRIMARY KEY (`id_opsi_jawaban`),
  ADD KEY `id_kuis` (`id_kuis`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `pertanyaan`
--
ALTER TABLE `pertanyaan`
  ADD PRIMARY KEY (`id_pertanyaan`),
  ADD KEY `id_video` (`id_video`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- Indexes for table `video_pembelajaran`
--
ALTER TABLE `video_pembelajaran`
  ADD PRIMARY KEY (`id_video`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `balasan`
--
ALTER TABLE `balasan`
  MODIFY `id_balasan` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kuis`
--
ALTER TABLE `kuis`
  MODIFY `id_kuis` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kuis_pengguna`
--
ALTER TABLE `kuis_pengguna`
  MODIFY `id_kuis_pengguna` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `opsi_jawaban`
--
ALTER TABLE `opsi_jawaban`
  MODIFY `id_opsi_jawaban` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pertanyaan`
--
ALTER TABLE `pertanyaan`
  MODIFY `id_pertanyaan` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `video_pembelajaran`
--
ALTER TABLE `video_pembelajaran`
  MODIFY `id_video` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `balasan`
--
ALTER TABLE `balasan`
  ADD CONSTRAINT `balasan_ibfk_1` FOREIGN KEY (`id_pertanyaan`) REFERENCES `pertanyaan` (`id_pertanyaan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `balasan_ibfk_2` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kuis`
--
ALTER TABLE `kuis`
  ADD CONSTRAINT `kuis_ibfk_1` FOREIGN KEY (`id_video`) REFERENCES `video_pembelajaran` (`id_video`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kuis_pengguna`
--
ALTER TABLE `kuis_pengguna`
  ADD CONSTRAINT `kuis_pengguna_ibfk_1` FOREIGN KEY (`id_video`) REFERENCES `video_pembelajaran` (`id_video`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kuis_pengguna_ibfk_2` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `opsi_jawaban`
--
ALTER TABLE `opsi_jawaban`
  ADD CONSTRAINT `opsi_jawaban_ibfk_1` FOREIGN KEY (`id_kuis`) REFERENCES `kuis` (`id_kuis`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pertanyaan`
--
ALTER TABLE `pertanyaan`
  ADD CONSTRAINT `pertanyaan_ibfk_1` FOREIGN KEY (`id_video`) REFERENCES `video_pembelajaran` (`id_video`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pertanyaan_ibfk_2` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `video_pembelajaran`
--
ALTER TABLE `video_pembelajaran`
  ADD CONSTRAINT `video_pembelajaran_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
