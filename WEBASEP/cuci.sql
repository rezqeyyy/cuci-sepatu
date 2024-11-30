-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Nov 2024 pada 19.31
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cuci`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(15) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `delivery` enum('Yes','No') NOT NULL,
  `services` varchar(255) NOT NULL,
  `total_price` int(11) NOT NULL,
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `customer_name`, `phone`, `email`, `address`, `delivery`, `services`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
(4, 'ORD2411308', 'asep', '000000000000', 'asep@gmail.com', 'yxhtyfchjchffhu', 'No', 'Color Restoration', 50, 'Pending', '2024-11-30 02:00:34', '2024-11-30 04:25:02'),
(5, 'ORD2411306', 'RIZQI ASAN MASIKA', '11111111111111', 'rizqiasanmm@gmail.com', 'awdadadawd', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 02:14:36', '2024-11-30 04:25:02'),
(6, 'ORD2411307', 'RIZQI ASAN MASIKA', '11111111111111', 'rizqiasanmm@gmail.com', 'yxhtyfchjchffhu', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 02:22:07', '2024-11-30 04:25:02'),
(7, 'ORD2411304', 'RIZQI ASAN MASIKA', '11111111111111', 'rizqiasanmm@gmail.com', 'awdadadawd', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 02:45:28', '2024-11-30 04:25:02'),
(8, 'ORD2411307', 'RIZQI ASAN MASIKA', '11111111111111', 'rizqiasanmm@gmail.com', 'awdadadawd', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 02:47:18', '2024-11-30 04:25:02'),
(9, 'ORD2411300', 'RIZQI ASAN MASIKA', '11111111111111', 'rizqiasanmm@gmail.com', 'awdadadawd', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 02:51:12', '2024-11-30 04:25:02'),
(10, 'ORD2411304', 'RIZQI ASAN MASIKA', '11111111111111', 'rizqiasanmm@gmail.com', 'awdadadawd', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 02:53:12', '2024-11-30 04:25:01'),
(11, 'ORD2411302', 'RIZQI ASAN MASIKA', '11111111111111', 'rizqiasanmm@gmail.com', 'yxhtyfchjchffhu', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 02:55:11', '2024-11-30 04:25:02'),
(12, 'ORD2411305', 'RIZQI ASAN MASIKA', '11111111111111', 'rizqiasanmm@gmail.com', 'yxhtyfchjchffhu', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 03:09:04', '2024-11-30 04:25:02'),
(13, 'ORD2411306', 'qwdawd', '232345214113', 'rizqiasanmm@gmail.com', 'awdasdawd', 'No', 'Color Restoration', 50, 'Pending', '2024-11-30 03:10:27', '2024-11-30 04:25:02'),
(14, 'ORD2411309', 'qwdawd', '232345214113', 'rizqiasanmm@gmail.com', 'rgdfsdfsd', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 03:10:56', '2024-11-30 04:25:02'),
(15, 'ORD2411303', 'qwdawd', '232345214113', 'rizqiasanmm@gmail.com', 'e3waeda', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 03:11:35', '2024-11-30 04:25:02'),
(16, 'ORD2411301', 'RIZQI ASAN MASIKA', '11111111111111', 'rizqiasanmm@gmail.com', 'yxhtyfchjchffhu', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 03:19:20', '2024-11-30 04:25:02'),
(17, 'ORD2411305', 'RIZQI ASAN MASIKA', '11111111111111', 'rizqiasanmm@gmail.com', 'yxhtyfchjchffhu', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 03:23:39', '2024-11-30 04:25:02'),
(18, 'ORD2411307', 'RIZQI ASAN MASIKA', '11111111111111', 'rizqiasanmm@gmail.com', 'yxhtyfchjchffhu', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 03:24:45', '2024-11-30 04:25:02'),
(19, 'ORD2411301', 'RIZQI ASAN MASIKA', '11111111111111', 'rizqiasanmm@gmail.com', 'awdadadawd', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 03:25:50', '2024-11-30 04:25:02'),
(20, 'ORD2411309', 'naufal', '11111111111111', 'rizqiasanmm@gmail.com', 'awdadadawd', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 03:30:28', '2024-11-30 04:25:02'),
(21, 'ORD2411304', 'naufal', '11111111111111', 'naufal.putra.hasan.tik23@stu.pnj.ac.id', 'awdadadawd', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 03:30:53', '2024-11-30 04:25:02'),
(22, 'ORD2411303', 'RANU', '11111111111111', 'kaulamenssalon@gmail.com', 'wadasdawd', 'No', 'Color Restoration', 50, 'Pending', '2024-11-30 03:36:38', '2024-11-30 04:25:02'),
(23, 'ORD2411305', 'NASHWA', '11111111111111', 'aliyaashwaa@gmail.com', 'awdadadawd', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 04:10:40', '2024-11-30 04:25:02'),
(24, 'ORD2411307', 'RIZQI ASAN MASIKA', '11111111111111', 'rizqiasanmm@gmail.com', 'yxhtyfchjchffhu', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 04:19:31', '2024-11-30 04:25:02'),
(25, 'ORD2411309', 'RIZQI ASAN MASIKA', '087771204269', 'RIZQIASANMM@GMAIL.COM', 'JL PENGADEGAN SELATAN 5, RT 02 RW 05, KELURAHAN PENGADEGAN, KECAMATAN PANCORAN, KOTA JAKARTA SELATAN, 12770', 'Yes', 'Color Restoration', 50, 'Pending', '2024-11-30 04:23:46', '2024-11-30 04:25:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `regis`
--

CREATE TABLE `regis` (
  `id` int(11) NOT NULL,
  `nama` varchar(70) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `regis`
--

INSERT INTO `regis` (`id`, `nama`, `username`, `email`, `password`) VALUES
(2, 'RIZQI ASAN MASIKA', 'easybutterx', 'RIZQIASANMM@GMAIL.COM', '$2y$10$ly8SHDW94sUSPbrsm8L.qe9GS4zWheEOCyGwg34hL9neXAHXkrI46');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `regis`
--
ALTER TABLE `regis`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `regis`
--
ALTER TABLE `regis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
