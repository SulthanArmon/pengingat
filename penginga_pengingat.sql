-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 01 Apr 2026 pada 19.14
-- Versi server: 11.4.10-MariaDB
-- Versi PHP: 8.4.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `penginga_pengingat`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `reminder_time` datetime NOT NULL,
  `status` enum('belum','selesai') DEFAULT 'belum',
  `notified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `title`, `reminder_time`, `status`, `notified`, `created_at`) VALUES
(26, 17, 'p', '2026-02-25 22:01:00', 'belum', 0, '2026-02-25 13:59:38'),
(27, 18, '1', '2026-02-25 21:01:00', 'belum', 0, '2026-02-25 14:00:56'),
(37, 19, 'tugas bahasa Indonesia ', '2026-01-25 09:21:00', 'belum', 0, '2026-02-26 02:23:49'),
(38, 19, 'tugas matematika ', '2026-02-24 09:24:00', 'belum', 0, '2026-02-26 02:24:38'),
(39, 19, 'tugas pai', '2026-02-26 09:24:00', 'belum', 0, '2026-02-26 02:24:53'),
(40, 21, 'comli', '2026-02-26 11:11:00', 'belum', 0, '2026-02-26 04:12:13'),
(41, 19, 'tugas bahasa indonesia', '2026-02-26 13:21:00', 'belum', 0, '2026-02-26 06:21:22'),
(42, 23, 'B indo', '2026-02-26 13:22:00', 'belum', 0, '2026-02-26 06:21:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(17, 'sinta', 'tata', '2026-02-25 13:59:09'),
(18, 'ppss', 'yasa', '2026-02-25 14:00:37'),
(19, 'jamilla', 'jamillamilla', '2026-02-25 15:18:51'),
(20, 'Erine', '21082007', '2026-02-26 01:06:04'),
(21, 'robert. j. qoatlus', '1', '2026-02-26 04:10:59'),
(22, 'musdalifah', 'musdalifahifa', '2026-02-26 06:07:18'),
(23, 'Muz', 'muzda', '2026-02-26 06:21:10');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
