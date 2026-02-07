-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 21 Des 2025 pada 02.19
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mukasa_finance`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_pembayarans`
--

CREATE TABLE `jenis_pembayarans` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jenis_pembayarans`
--

INSERT INTO `jenis_pembayarans` (`id`, `nama`, `nominal`, `kategori`, `keterangan`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SPP Bulanan X', 1500000.00, 'SPP', NULL, 1, '2025-11-21 08:41:23', '2025-12-20 19:09:46', NULL),
(2, 'Uang Gedung', 5000000.00, 'Gedung', NULL, 1, '2025-11-21 08:41:23', '2025-11-21 08:41:23', NULL),
(3, 'Praktikum IPA', 200000.00, 'Praktikum', NULL, 1, '2025-11-21 08:41:23', '2025-11-21 08:41:23', NULL),
(4, 'Kegiatan Sekolah', 100000.00, 'Lainnya', NULL, 1, '2025-11-21 08:41:23', '2025-11-21 08:41:23', NULL),
(5, 'Ujian Semester', 75000.00, 'Lainnya', NULL, 1, '2025-11-21 08:41:23', '2025-11-21 08:41:23', NULL),
(6, 'pembangunan kampus 2', 150000.00, 'SPP', NULL, 1, '2025-11-22 01:43:02', '2025-11-28 21:44:36', '2025-11-28 21:44:36'),
(7, 'Z', 1000000.00, 'SPP', NULL, 1, '2025-11-22 02:01:58', '2025-11-28 21:43:16', '2025-11-28 21:43:16'),
(8, 'saa', 100000.00, 'Lainnya', NULL, 1, '2025-11-28 16:49:37', '2025-11-28 21:43:00', '2025-11-28 21:43:00'),
(9, 'Menganalisa cerpen', 1000.00, 'Gedung', NULL, 1, '2025-11-28 17:04:16', '2025-11-28 21:38:46', '2025-11-28 21:38:46'),
(10, 'Membuat dan Menelaah cerpen teman', 10000.00, 'Gedung', NULL, 1, '2025-11-28 21:48:46', '2025-11-28 21:49:29', '2025-11-28 21:49:29'),
(11, 'Menganalisa cerpen', 10000099.00, 'Praktikum', NULL, 1, '2025-11-28 21:52:05', '2025-11-28 21:58:05', '2025-11-28 21:58:05'),
(12, '11111', 100000.00, 'Lainnya', NULL, 1, '2025-11-28 21:52:19', '2025-11-28 21:52:24', '2025-11-28 21:52:24'),
(13, 'Menganalisa cerpen 1', 100000999.00, 'SPP', NULL, 1, '2025-11-28 21:59:27', '2025-11-28 22:02:15', '2025-11-28 22:02:15'),
(14, 'Menganalisa cerpen A', 1000000000000.00, 'SPP', 'A', 1, '2025-11-28 22:08:44', '2025-11-28 22:09:18', '2025-11-28 22:09:18'),
(15, 'Menganalisa cerpen', 10000000000.00, 'SPP', NULL, 1, '2025-11-28 22:11:54', '2025-11-28 22:11:59', '2025-11-28 22:11:59'),
(16, 'Menganalisa cerpen yyy', 500000.00, 'SPP', NULL, 1, '2025-11-28 22:20:16', '2025-11-28 22:25:45', '2025-11-28 22:25:45'),
(28, 'Membuat dan Menelaah cerpen teman', 100000.00, 'Lainnya', NULL, 1, '2025-12-01 10:00:35', '2025-12-20 11:07:35', '2025-12-20 11:07:35'),
(64, 'pembangunan kampus 2s', 100000.00, 'Lainnya', NULL, 1, '2025-12-20 10:57:05', '2025-12-20 10:57:05', NULL),
(65, 'Z', 100000.00, 'Lainnya', 'd', 1, '2025-12-20 18:57:24', '2025-12-20 18:57:24', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_11_21_145122_create_permission_tables', 1),
(6, '2025_11_21_145335_create_jenis_pembayarans_table', 1),
(7, '2025_11_21_145404_create_pembayarans_table', 1),
(8, '2025_11_22_075304_add_tenggat_waktu_to_pembayarans_table', 2),
(9, '2025_11_22_082355_create_penagihans_table', 3),
(10, '2025_11_23_083436_add_soft_delete_to_users_table', 4),
(11, '2025_11_23_232634_add_deleted_at_to_jenis_pembayarans_table', 5),
(12, '2025_11_28_234827_add_keterangan_to_jenis_pembayarans_table', 6),
(13, '2025_11_29_045128_modify_kategori_column_jenis_pembayarans', 7),
(14, '2025_11_29_072511_check_repair_penagihan_table', 8),
(15, '2025_12_01_000001_make_tanggal_bayar_nullable_in_pembayarans', 9),
(16, '2025_12_21_005500_make_tanggal_bayar_nullable_in_pembayarans_table', 9);

-- --------------------------------------------------------

--
-- Struktur dari tabel `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayarans`
--

CREATE TABLE `pembayarans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `jenis_pembayaran_id` bigint UNSIGNED NOT NULL,
  `tanggal_bayar` date DEFAULT NULL,
  `tenggat_waktu` date DEFAULT NULL,
  `bukti` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `keterangan_admin` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `alasan_reject` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pembayarans`
--

INSERT INTO `pembayarans` (`id`, `user_id`, `jenis_pembayaran_id`, `tanggal_bayar`, `tenggat_waktu`, `bukti`, `keterangan`, `keterangan_admin`, `status`, `alasan_reject`, `created_at`, `updated_at`) VALUES
(3, 3, 5, '2025-11-06', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(4, 4, 1, '2025-11-12', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(5, 4, 2, '2025-10-29', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(7, 4, 4, '2025-11-10', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-23 01:13:26'),
(8, 5, 1, '2025-11-04', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-23 03:19:17'),
(9, 5, 3, '2025-11-12', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-23 03:19:27'),
(10, 6, 4, '2025-11-09', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'rejected', 'onlinr', '2025-11-21 08:41:23', '2025-11-23 03:19:40'),
(11, 7, 2, '2025-10-26', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-23 03:28:03'),
(12, 7, 4, '2025-11-08', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-23 05:00:37'),
(13, 8, 2, '2025-11-07', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'rejected', 'AD', '2025-11-21 08:41:23', '2025-11-23 03:28:21'),
(14, 8, 3, '2025-11-08', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'rejected', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(15, 8, 4, '2025-11-18', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(16, 8, 5, '2025-11-16', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'rejected', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(17, 9, 1, '2025-11-06', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'rejected', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(18, 9, 4, '2025-10-23', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-28 22:38:55'),
(19, 10, 1, '2025-10-27', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(20, 10, 2, '2025-10-27', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(21, 10, 3, '2025-10-29', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(22, 10, 4, '2025-10-27', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(23, 11, 3, '2025-10-22', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(24, 11, 4, '2025-11-13', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'rejected', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(25, 12, 4, '2025-11-01', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(26, 12, 5, '2025-10-26', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'rejected', 'm', '2025-11-21 08:41:23', '2025-11-23 05:24:05'),
(27, 13, 1, '2025-11-04', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(28, 13, 3, '2025-10-31', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-23 05:24:14'),
(29, 14, 2, '2025-10-27', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'rejected', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(30, 14, 3, '2025-10-30', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'rejected', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(31, 14, 4, '2025-11-12', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(32, 15, 4, '2025-10-23', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(33, 15, 5, '2025-10-23', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(34, 17, 4, '2025-10-24', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(35, 17, 5, '2025-10-22', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'rejected', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(36, 18, 1, '2025-10-26', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'rejected', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(37, 18, 2, '2025-11-13', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(38, 18, 3, '2025-11-08', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'rejected', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(39, 18, 4, '2025-11-01', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'rejected', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(40, 18, 5, '2025-11-02', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(41, 19, 1, '2025-11-04', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(42, 19, 5, '2025-10-22', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'rejected', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(43, 20, 1, '2025-11-20', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(44, 20, 4, '2025-10-30', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(45, 20, 5, '2025-10-28', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(46, 21, 1, '2025-10-26', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(47, 21, 3, '2025-10-28', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(48, 21, 4, '2025-11-19', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'rejected', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(49, 21, 5, '2025-11-09', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(50, 22, 1, '2025-11-07', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(51, 22, 3, '2025-11-15', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(52, 22, 5, '2025-11-07', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'rejected', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(53, 23, 5, '2025-11-17', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(54, 24, 1, '2025-10-25', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(55, 24, 2, '2025-11-04', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(56, 24, 4, '2025-11-03', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(57, 24, 5, '2025-11-14', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(58, 25, 5, '2025-10-27', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(59, 26, 1, '2025-11-08', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'rejected', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(60, 26, 4, '2025-10-31', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(61, 27, 2, '2025-10-25', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(62, 27, 3, '2025-10-31', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(63, 27, 5, '2025-10-31', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'rejected', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(64, 28, 2, '2025-11-08', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(65, 28, 3, '2025-10-26', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(66, 28, 4, '2025-10-23', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(67, 29, 1, '2025-10-28', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'rejected', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(68, 29, 3, '2025-11-01', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(69, 29, 5, '2025-10-25', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'rejected', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(70, 30, 1, '2025-11-04', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(71, 30, 2, '2025-11-17', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(72, 30, 3, '2025-11-08', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(73, 31, 2, '2025-10-31', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(74, 31, 3, '2025-11-08', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(75, 31, 5, '2025-11-18', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(76, 32, 2, '2025-10-22', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(77, 32, 3, '2025-10-28', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(78, 32, 4, '2025-11-06', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'approved', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(79, 32, 5, '2025-10-23', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'pending', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23'),
(80, 14, 3, '2025-11-22', '2025-11-29', 'uploads/bukti/Q56GBqcWdgOCOwZe2UMGlZ7GbqjJsNeMmUJ5yVDO.png', 'Dibayar ya', 'sudah ya', 'approved', NULL, '2025-11-22 01:08:44', '2025-11-22 01:10:10'),
(81, 8, 64, NULL, '2025-12-30', NULL, NULL, 's', 'rejected', 'ds', '2025-12-20 10:57:05', '2025-12-20 11:01:11'),
(82, 25, 64, NULL, '2025-12-30', NULL, NULL, NULL, 'approved', NULL, '2025-12-20 10:57:05', '2025-12-20 11:00:59'),
(83, 8, 2, '2025-12-20', '2025-12-27', 'uploads/bukti/77ojpwvTK77tOhFoLOUvapqFGBHW4BRhAtblxRoZ.pdf', NULL, NULL, 'pending', NULL, '2025-12-20 11:26:38', '2025-12-20 11:26:38'),
(84, 31, 65, NULL, '2025-12-30', NULL, NULL, NULL, 'pending', NULL, '2025-12-20 18:57:24', '2025-12-20 18:57:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penagihans`
--

CREATE TABLE `penagihans` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `nominal` decimal(12,2) NOT NULL,
  `jenis` enum('bulanan','tahunan','bebas') COLLATE utf8mb4_unicode_ci NOT NULL,
  `target` enum('massal','individu') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenggat_waktu` date NOT NULL,
  `target_siswa` json DEFAULT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jurusan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `penagihans`
--

INSERT INTO `penagihans` (`id`, `judul`, `deskripsi`, `nominal`, `jenis`, `target`, `tenggat_waktu`, `target_siswa`, `kelas`, `jurusan`, `status`, `created_at`, `updated_at`, `created_by`) VALUES
(53, 'pembangunan kampus 2s', NULL, 100000.00, 'bulanan', 'massal', '2025-12-30', NULL, '10', 'IPS', 1, '2025-12-20 10:57:05', '2025-12-20 10:57:05', 2),
(54, 'Z', 'd', 100000.00, 'bebas', 'individu', '2025-12-30', '[\"31\"]', NULL, NULL, 1, '2025-12-20 18:57:24', '2025-12-20 18:57:24', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelas` enum('10','11','12') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jurusan` enum('IPA','IPS') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','bendahara','siswa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'siswa',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `nis`, `kelas`, `jurusan`, `role`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administrator', 'admin@smamuhkasihan.sch.id', 'admin001', NULL, NULL, 'admin', '$2y$10$T.wEbZ8SK5fjSdty8Y.l8ODdbmGdc5Pn.d2npL4kAHYOC3hGvNG5W', NULL, '2025-11-21 08:41:20', '2025-11-21 08:41:20', NULL),
(2, 'Bendahara Sekolah', 'bendahara@smamuhkasihan.sch.id', 'bendahara001', NULL, NULL, 'bendahara', '$2y$10$KFCHoHI/93/gc6fFFZMRJOlSjxTit96qD6bxiMzhMM1olV.19ouzK', NULL, '2025-11-21 08:41:21', '2025-12-01 10:01:55', NULL),
(3, 'Siswa 1', 'siswa1@smamuhkasihan.sch.id', 'S001', '12', 'IPA', 'siswa', '$2y$10$2h61TVamZlTvtlf56Y3hpO49np8dlW1PpM5.9UbjoWU3gKk0veZa.', NULL, '2025-11-21 08:41:21', '2025-11-23 02:59:54', '2025-11-23 02:59:54'),
(4, 'Siswa 2', 'siswa2@smamuhkasihan.sch.id', 'S002', NULL, NULL, 'bendahara', '$2y$10$qd/3aOVxGTrSWFmOSkNIcOiASkFebmg7ZUdt1xc5G6ntUSxOpXllu', NULL, '2025-11-21 08:41:21', '2025-11-23 05:25:31', '2025-11-23 05:25:31'),
(5, 'Siswa 3', 'siswa3@smamuhkasihan.sch.id', 'S003', NULL, NULL, 'bendahara', '$2y$10$hKEjg5kyCUsKH/IcwWDUWu2eL2RV0NPeRtivr2q9zi1LdjRrLp8tW', NULL, '2025-11-21 08:41:21', '2025-11-23 16:12:15', '2025-11-23 16:12:15'),
(6, 'Siswa 4', 'siswa4@smamuhkasihan.sch.id', 'S004', '10', 'IPA', 'siswa', '$2y$10$/8JSyF8tNlaCv1ejCaE98.syYbiNO.RipjrVUVrtWTClErB4uUYhG', NULL, '2025-11-21 08:41:21', '2025-11-23 16:47:34', '2025-11-23 16:47:34'),
(7, 'Siswa 5', 'siswa5@smamuhkasihan.sch.id', 'S005', '12', 'IPA', 'siswa', '$2y$10$xRX1udtuGtYvI8SX6zaLDuq5VHmhztUgRRoS5KOTQvVh9d9SgnPla', NULL, '2025-11-21 08:41:21', '2025-11-28 16:19:28', '2025-11-28 16:19:28'),
(8, 'Siswa 6', 'siswa6@smamuhkasihan.sch.id', 'S006', '10', 'IPS', 'siswa', '$2y$10$dc5GiEMsmg7u4N0X2nLZxudT9TsT9ihSViiKTJ./LwdMVspsx7hCe', NULL, '2025-11-21 08:41:21', '2025-12-20 11:13:42', NULL),
(9, 'Siswa 7', 'siswa7@smamuhkasihan.sch.id', 'S007', '12', 'IPA', 'siswa', '$2y$10$qfqsGB67eEcUsVyI/X6h3.EFKxXdKWOcnG.WmqXVxgwl6dQUTOR1e', NULL, '2025-11-21 08:41:21', '2025-11-21 08:41:21', NULL),
(10, 'Siswa 8', 'siswa8@smamuhkasihan.sch.id', 'S008', '11', 'IPA', 'siswa', '$2y$10$0jrfyMDFce1s.Dngpk8up.eVDVDxqGZfZBHdL6say8qDwoc88AUgq', NULL, '2025-11-21 08:41:21', '2025-11-21 08:41:21', NULL),
(11, 'Siswa 9', 'siswa9@smamuhkasihan.sch.id', 'S009', '10', 'IPA', 'siswa', '$2y$10$9P9H0sfKpaEDZPitk/uxCetzlKJDJjvCoL3ks2sbwFlfkVyzOVHQi', NULL, '2025-11-21 08:41:21', '2025-11-28 22:26:54', '2025-11-28 22:26:54'),
(12, 'Siswa 10', 'siswa10@smamuhkasihan.sch.id', 'S010', '10', 'IPA', 'siswa', '$2y$10$lYln2XTmuA7hXkB4XNoLAu6GQf4vNRofd4laUF3jhjwEgcm8TnYr6', NULL, '2025-11-21 08:41:21', '2025-11-23 03:34:29', '2025-11-23 03:34:29'),
(13, 'Siswa 11', 'siswa11@smamuhkasihan.sch.id', 'S011', '12', 'IPS', 'siswa', '$2y$10$fUzjXVX/469ml2mVhRWxPuqvr6w0LyDJjzVkFKZQ0J.Cm7HMbRQNW', NULL, '2025-11-21 08:41:21', '2025-11-23 05:25:10', '2025-11-23 05:25:10'),
(14, 'Siswa 12', 'siswa12@smamuhkasihan.sch.id', 'S012', '10', 'IPS', 'siswa', '$2y$10$0gqY.81mGSuSVnttXs/7aOguuM7ycP1MX2JNwyTEn9/gcOeE9OYuu', NULL, '2025-11-21 08:41:21', '2025-11-28 16:19:42', '2025-11-28 16:19:42'),
(15, 'Siswa 13', 'siswa13@smamuhkasihan.sch.id', 'S013', '12', 'IPS', 'siswa', '$2y$10$Ak5HXwfByBanntB2xZ2Bn.iI.FPh1eV2LW6YuLBRUBDtfAspdxN1S', NULL, '2025-11-21 08:41:21', '2025-11-23 03:34:36', '2025-11-23 03:34:36'),
(16, 'Arif', 'siswa14@smamuhkasihan.sch.id', 'S014', '10', 'IPS', 'siswa', '$2y$10$lnBtvYXRUSzo95x.s.BrTeiyvFlrjgjxCYJiagbDKY5qTjhU3r14C', NULL, '2025-11-21 08:41:21', '2025-11-28 22:39:30', '2025-11-28 22:39:30'),
(17, 'Siswa 15', 'siswa15@smamuhkasihan.sch.id', 'S015', '10', 'IPS', 'siswa', '$2y$10$ETj1NqE5ZfpJTR6OVmIXw.dwDGjywDYtsImhhlT4rAjJEGEKZFRYO', NULL, '2025-11-21 08:41:22', '2025-11-23 03:39:07', '2025-11-23 03:39:07'),
(18, 'Siswa 16', 'siswa16@smamuhkasihan.sch.id', 'S016', '10', 'IPA', 'siswa', '$2y$10$1Mfu.sS1YzqLQEEMzA/T0unk5.UHkxLXxKY5ao1pgPLW0gRu063my', NULL, '2025-11-21 08:41:22', '2025-11-23 03:38:55', NULL),
(19, 'Siswa 17', 'siswa17@smamuhkasihan.sch.id', 'S017', '12', 'IPS', 'siswa', '$2y$10$L1IejNkaswWpxG4oah4v0eKL3REW1JuzR8Qc64bym2ivS7y8kRt7i', NULL, '2025-11-21 08:41:22', '2025-11-21 08:41:22', NULL),
(20, 'Siswa 18', 'siswa18@smamuhkasihan.sch.id', 'S018', '11', 'IPS', 'siswa', '$2y$10$E4z5M1NiaP5VO2GtvsM88ur9ICLPJwWurUeKcwn.r0kSa0H8y59qe', NULL, '2025-11-21 08:41:22', '2025-11-23 02:00:44', '2025-11-23 02:00:44'),
(21, 'Siswa 19', 'siswa19@smamuhkasihan.sch.id', 'S019', '12', 'IPA', 'siswa', '$2y$10$lW8RILBGksBpm2yFoh/yLuwS0wu/sX0/wmmOLeaUwdN07Myku7eM6', NULL, '2025-11-21 08:41:22', '2025-11-28 16:19:07', '2025-11-28 16:19:07'),
(22, 'Siswa 20', 'siswa20@smamuhkasihan.sch.id', 'S020', '10', 'IPA', 'siswa', '$2y$10$RGB0zRwLdh/2RTmAXulyQOp6T9Ou/T/0oNqKI9imifAr.dU0QzBi.', NULL, '2025-11-21 08:41:22', '2025-11-23 16:47:25', '2025-11-23 16:47:25'),
(23, 'Siswa 210', 'siswa21@smamuhkasihan.sch.id', 'S021', '11', 'IPS', 'siswa', '$2y$10$Lrzs.Je11TN0hnGG0reg6.TE7K6uPZ86l946i1Yz352q3yhrlYR8u', NULL, '2025-11-21 08:41:22', '2025-11-28 22:26:46', NULL),
(24, 'Siswa 22', 'siswa22@smamuhkasihan.sch.id', 'S022', '10', 'IPA', 'siswa', '$2y$10$quAeggF9VgUe1IWUNKEe3ecQn8eFLL2A7LQVc2gtorX0/3I3E8S9S', NULL, '2025-11-21 08:41:22', '2025-11-21 08:41:22', NULL),
(25, 'Siswa 23', 'siswa23@smamuhkasihan.sch.id', 'S023', '10', 'IPS', 'siswa', '$2y$10$LkqncUprreup/6u21yHi7uLPC9xQGLkaZSYd8glyuN48oBlilJLdS', NULL, '2025-11-21 08:41:22', '2025-11-21 08:41:22', NULL),
(26, 'Siswa 24', 'siswa24@smamuhkasihan.sch.id', 'S024', '12', 'IPS', 'siswa', '$2y$10$XEdIkHDmsyzAsWQR4SY7Weg8hSRuVLJQJOL6d2a/vQTyF9cZOQcji', NULL, '2025-11-21 08:41:22', '2025-12-20 11:07:14', NULL),
(27, 'Siswa 25', 'siswa25@smamuhkasihan.sch.id', 'S025', '10', 'IPA', 'siswa', '$2y$10$DOk8iZq.SvKcQP8ct6uideb88MZUeOjAwXdB2DKcJrFCJ9a6h4/bC', NULL, '2025-11-21 08:41:22', '2025-12-20 11:07:19', '2025-12-20 11:07:19'),
(28, 'Siswa 26', 'siswa26@smamuhkasihan.sch.id', 'S026', '11', 'IPS', 'siswa', '$2y$10$yIcdhMwGFPk2Xf6Dx.TYx.stYjUppCxxTUjJiiHtkYWwytFtWgGZ6', NULL, '2025-11-21 08:41:22', '2025-12-20 11:00:31', '2025-12-20 11:00:31'),
(29, 'Siswa 27', 'siswa27@smamuhkasihan.sch.id', 'S027', '11', 'IPA', 'siswa', '$2y$10$iulHwWWAnEY6k19pM8Cp/.vJlRuQC3fbQ7q6P.EOfHnIJNanEmiKK', NULL, '2025-11-21 08:41:22', '2025-11-21 08:41:22', NULL),
(30, 'Siswa 28', 'siswa28@smamuhkasihan.sch.id', 'S028', '11', 'IPS', 'siswa', '$2y$10$.IGy7jPZjMsc6MbDSDfF6uEO.P9Rho6ecBs4FGAuvGYHMlsdItO.O', NULL, '2025-11-21 08:41:22', '2025-11-21 08:41:22', NULL),
(31, 'Siswa 29', 'siswa29@smamuhkasihan.sch.id', 'S029', '12', 'IPS', 'siswa', '$2y$10$2/fuE/NErxqJmfn2vu.yXeezdV//AHKcUtE.YAfS72js4JGNyCK6u', NULL, '2025-11-21 08:41:23', '2025-11-21 08:41:23', NULL),
(32, 'Siswa 30', 'siswa30@smamuhkasihan.sch.id', 'S030', '10', 'IPS', 'siswa', '$2y$10$AhR59N5JEZD6GoCYruALCOWH/Kys/Sqo/VdFjIBLigPe.PFIk1L4i', NULL, '2025-11-21 08:41:23', '2025-11-23 02:46:19', '2025-11-23 02:46:19'),
(33, 'Dimas Agung Prabowo', 'dimas@mks.id', '12121', '10', 'IPA', 'siswa', '$2y$10$vt/cGg0MLJ2myxMJ9sEGPeYorCtTkR0bGMiw9pBwO4PIg/q9k8tkq', NULL, '2025-11-22 02:56:31', '2025-11-23 02:00:11', '2025-11-23 02:00:11'),
(34, 'Imin', NULL, '12100', '10', 'IPA', 'siswa', '$2y$10$RLwPSjdEQOoJjIbfpCzF5Ob.lWamg03S8DeaQySSX53XMc16Mqqzq', NULL, '2025-11-22 17:35:49', '2025-11-23 02:00:18', '2025-11-23 02:00:18');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jenis_pembayarans`
--
ALTER TABLE `jenis_pembayarans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indeks untuk tabel `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembayarans_user_id_foreign` (`user_id`),
  ADD KEY `pembayarans_jenis_pembayaran_id_foreign` (`jenis_pembayaran_id`);

--
-- Indeks untuk tabel `penagihans`
--
ALTER TABLE `penagihans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indeks untuk tabel `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_nis_unique` (`nis`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jenis_pembayarans`
--
ALTER TABLE `jenis_pembayarans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `pembayarans`
--
ALTER TABLE `pembayarans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT untuk tabel `penagihans`
--
ALTER TABLE `penagihans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT untuk tabel `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD CONSTRAINT `pembayarans_jenis_pembayaran_id_foreign` FOREIGN KEY (`jenis_pembayaran_id`) REFERENCES `jenis_pembayarans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayarans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
