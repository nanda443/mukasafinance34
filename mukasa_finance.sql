-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 01, 2025 at 09:49 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

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
-- Table structure for table `failed_jobs`
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
-- Table structure for table `jenis_pembayarans`
--

CREATE TABLE `jenis_pembayarans` (
  `id` bigint UNSIGNED NOT NULL,
  `penagihan_id` bigint UNSIGNED DEFAULT NULL,
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
-- Dumping data for table `jenis_pembayarans`
--

INSERT INTO `jenis_pembayarans` (`id`, `penagihan_id`, `nama`, `nominal`, `kategori`, `keterangan`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'SPP Bulanan', '150000.00', 'SPP', NULL, 1, '2025-12-01 02:06:49', '2025-12-01 02:06:49', NULL),
(2, NULL, 'Uang Gedung', '5000000.00', 'Gedung', NULL, 1, '2025-12-01 02:06:49', '2025-12-01 02:06:49', NULL),
(3, NULL, 'Praktikum IPA', '200000.00', 'Praktikum', NULL, 1, '2025-12-01 02:06:49', '2025-12-01 02:06:49', NULL),
(4, NULL, 'Kegiatan Sekolah', '100000.00', 'Lainnya', NULL, 1, '2025-12-01 02:06:49', '2025-12-01 02:06:49', NULL),
(5, NULL, 'Ujian Semester', '75000.00', 'Lainnya', NULL, 1, '2025-12-01 02:06:49', '2025-12-01 02:06:49', NULL),
(7, NULL, 'spp', '50000.00', 'Lainnya', 'sasasasas', 1, '2025-12-01 02:14:20', '2025-12-01 02:14:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_11_21_145122_create_permission_tables', 1),
(6, '2025_11_21_145335_create_jenis_pembayarans_table', 1),
(7, '2025_11_21_145404_create_pembayarans_table', 1),
(8, '2025_11_22_075304_add_tenggat_waktu_to_pembayarans_table', 1),
(9, '2025_11_22_082355_create_penagihans_table', 1),
(10, '2025_11_23_083436_add_soft_delete_to_users_table', 1),
(11, '2025_11_23_232634_add_deleted_at_to_jenis_pembayarans_table', 1),
(12, '2025_11_28_234827_add_keterangan_to_jenis_pembayarans_table', 1),
(13, '2025_11_29_045128_modify_kategori_column_jenis_pembayarans', 1),
(14, '2025_11_29_072511_check_repair_penagihan_table', 1),
(15, '2025_12_01_000001_make_tanggal_bayar_nullable_in_pembayarans', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayarans`
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
-- Dumping data for table `pembayarans`
--

INSERT INTO `pembayarans` (`id`, `user_id`, `jenis_pembayaran_id`, `tanggal_bayar`, `tenggat_waktu`, `bukti`, `keterangan`, `keterangan_admin`, `status`, `alasan_reject`, `created_at`, `updated_at`) VALUES
(1, 3, 1, '2025-11-17', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'pending', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(2, 3, 2, '2025-11-28', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'pending', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(3, 3, 4, '2025-11-21', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'approved', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(4, 4, 2, '2025-11-16', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'pending', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(5, 4, 3, '2025-11-07', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(6, 4, 5, '2025-11-14', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'approved', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(7, 5, 4, '2025-11-13', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'pending', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(8, 6, 1, '2025-11-30', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(9, 6, 4, '2025-11-27', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(10, 7, 1, '2025-11-12', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'approved', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(11, 7, 2, '2025-11-23', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(12, 7, 5, '2025-11-08', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(13, 8, 5, '2025-11-19', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'pending', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(14, 9, 3, '2025-11-03', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'approved', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(15, 10, 1, '2025-11-25', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'pending', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(16, 10, 2, '2025-11-02', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'pending', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(17, 10, 3, '2025-11-21', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'approved', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(18, 10, 4, '2025-11-14', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(19, 10, 5, '2025-11-08', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'approved', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(20, 11, 4, '2025-11-13', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(21, 12, 2, '2025-11-10', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'approved', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(22, 12, 3, '2025-11-03', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'approved', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(23, 13, 3, '2025-11-01', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'approved', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(24, 13, 4, '2025-11-13', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(25, 14, 1, '2025-11-15', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(26, 14, 2, '2025-11-07', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'pending', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(27, 15, 1, '2025-11-05', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(28, 16, 1, '2025-11-01', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'pending', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(29, 16, 2, '2025-11-12', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(30, 16, 3, '2025-11-07', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(31, 16, 4, '2025-11-27', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'pending', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(32, 17, 3, '2025-11-19', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(33, 17, 4, '2025-11-07', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(34, 18, 1, '2025-11-15', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'pending', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(35, 18, 2, '2025-11-28', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'approved', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(36, 18, 3, '2025-11-07', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'approved', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(37, 18, 5, '2025-11-13', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'pending', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(38, 19, 1, '2025-11-04', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(39, 19, 2, '2025-11-24', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(40, 19, 3, '2025-11-09', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'approved', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(41, 19, 4, '2025-11-12', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(42, 20, 1, '2025-11-30', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(43, 20, 4, '2025-11-17', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(44, 20, 5, '2025-11-22', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'approved', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(45, 21, 1, '2025-11-21', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'approved', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(46, 21, 2, '2025-11-01', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'approved', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(47, 22, 1, '2025-11-11', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'rejected', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49'),
(48, 22, 2, '2025-11-20', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'approved', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(49, 23, 1, '2025-11-11', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'approved', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(50, 23, 2, '2025-11-10', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'pending', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(51, 23, 4, '2025-11-16', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'rejected', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(52, 23, 5, '2025-11-24', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'approved', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(53, 24, 1, '2025-11-01', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'rejected', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(54, 24, 2, '2025-11-06', NULL, 'bukti_example.jpg', 'Pembayaran Uang Gedung', NULL, 'rejected', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(55, 24, 3, '2025-11-28', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'rejected', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(56, 25, 1, '2025-11-02', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'approved', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(57, 25, 3, '2025-11-17', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'rejected', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(58, 25, 4, '2025-11-01', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'pending', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(59, 25, 5, '2025-11-11', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'approved', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(60, 26, 5, '2025-11-13', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'rejected', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(61, 27, 1, '2025-11-23', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'rejected', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(62, 27, 5, '2025-11-17', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'approved', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(63, 28, 3, '2025-11-13', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'rejected', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(64, 28, 4, '2025-11-15', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'approved', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(65, 29, 5, '2025-11-19', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'approved', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(66, 30, 1, '2025-11-08', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'pending', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(67, 30, 3, '2025-11-23', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'rejected', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(68, 30, 5, '2025-11-24', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'approved', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(69, 31, 1, '2025-11-13', NULL, 'bukti_example.jpg', 'Pembayaran SPP Bulanan', NULL, 'pending', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(70, 31, 4, '2025-11-23', NULL, 'bukti_example.jpg', 'Pembayaran Kegiatan Sekolah', NULL, 'rejected', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(71, 32, 3, '2025-11-10', NULL, 'bukti_example.jpg', 'Pembayaran Praktikum IPA', NULL, 'pending', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(72, 32, 5, '2025-11-13', NULL, 'bukti_example.jpg', 'Pembayaran Ujian Semester', NULL, 'rejected', NULL, '2025-12-01 02:06:50', '2025-12-01 02:06:50'),
(73, 5, 7, NULL, '2025-12-10', NULL, NULL, NULL, 'pending', NULL, '2025-12-01 02:14:20', '2025-12-01 02:14:20'),
(74, 13, 7, NULL, '2025-12-10', NULL, NULL, NULL, 'pending', NULL, '2025-12-01 02:14:20', '2025-12-01 02:14:20'),
(75, 26, 7, NULL, '2025-12-10', NULL, NULL, NULL, 'pending', NULL, '2025-12-01 02:14:20', '2025-12-01 02:14:20');

-- --------------------------------------------------------

--
-- Table structure for table `penagihans`
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
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penagihans`
--

INSERT INTO `penagihans` (`id`, `judul`, `deskripsi`, `nominal`, `jenis`, `target`, `tenggat_waktu`, `target_siswa`, `kelas`, `jurusan`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'spp', 'sasasasas', '50000.00', 'tahunan', 'massal', '2025-12-10', NULL, '10', 'IPS', 1, 2, '2025-12-01 02:14:20', '2025-12-01 02:14:20');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
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
-- Table structure for table `personal_access_tokens`
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
-- Table structure for table `roles`
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
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
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
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `nis`, `kelas`, `jurusan`, `role`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administrator', 'admin@smamuhkasihan.sch.id', 'admin001', NULL, NULL, 'admin', '$2y$10$1lZNToyZwTxt2ky2aNB2qe1qUSYFteyTUiK3vCIDVEOZTQxqPHTtm', NULL, '2025-12-01 02:06:46', '2025-12-01 02:06:46', NULL),
(2, 'Bendahara Sekolah', 'bendahara@smamuhkasihan.sch.id', 'bendahara001', NULL, NULL, 'bendahara', '$2y$10$hvLY5llB9jRXu3EFAXVAAe0ts/hN667ovUbitO2xGxM8DRZoNd47W', NULL, '2025-12-01 02:06:46', '2025-12-01 02:06:46', NULL),
(3, 'Siswa 1', 'siswa1@smamuhkasihan.sch.id', 'S001', '12', 'IPA', 'siswa', '$2y$10$u3OnegtSZqWITdNVtrjSW.wTlk5FVTL97GjXFuF2NDcUtzixeTcIG', NULL, '2025-12-01 02:06:47', '2025-12-01 02:06:47', NULL),
(4, 'Siswa 2', 'siswa2@smamuhkasihan.sch.id', 'S002', '12', 'IPS', 'siswa', '$2y$10$PgfJYWvKUGcno9spHQFYq.6Ld.IEuV/OWwaPS6aPm.QzkXr7yrkhC', NULL, '2025-12-01 02:06:47', '2025-12-01 02:06:47', NULL),
(5, 'Siswa 3', 'siswa3@smamuhkasihan.sch.id', 'S003', '10', 'IPS', 'siswa', '$2y$10$b9HCc7YBm.9D.yXDR8kA1ucDVd.YFil2zHHtsuHQKJCr.hu58zvOC', NULL, '2025-12-01 02:06:47', '2025-12-01 02:06:47', NULL),
(6, 'Siswa 4', 'siswa4@smamuhkasihan.sch.id', 'S004', '12', 'IPA', 'siswa', '$2y$10$aAd.xhPgHxyDoqRMlA3rzeXfr3iuYc.LJaWO0PhNvbfLPaMFhOhSm', NULL, '2025-12-01 02:06:47', '2025-12-01 02:06:47', NULL),
(7, 'Siswa 5', 'siswa5@smamuhkasihan.sch.id', 'S005', '10', 'IPA', 'siswa', '$2y$10$pgmQ8pPQcj8AlFY8CRAAYO1Atoi1..Z9TR1NC5ArKMAbJL.6MztDO', NULL, '2025-12-01 02:06:47', '2025-12-01 02:06:47', NULL),
(8, 'Siswa 6', 'siswa6@smamuhkasihan.sch.id', 'S006', '11', 'IPA', 'siswa', '$2y$10$f1DLbVA9wSsodwoYOPDoA.0B8P6Mzg/KicPMFOo06a8/hSsHeHNca', NULL, '2025-12-01 02:06:47', '2025-12-01 02:06:47', NULL),
(9, 'Siswa 7', 'siswa7@smamuhkasihan.sch.id', 'S007', '10', 'IPA', 'siswa', '$2y$10$YrMsVcY7XdNpFvq6ZEwW2OkzUacmbqcHfmeiC7sQzaWGSAFdrJnBS', NULL, '2025-12-01 02:06:47', '2025-12-01 02:06:47', NULL),
(10, 'Siswa 8', 'siswa8@smamuhkasihan.sch.id', 'S008', '12', 'IPA', 'siswa', '$2y$10$Cylyxpmy3toyV18uRYiIb.luJHozAxhoXW1vJR1aiNNyLo6dtwad2', NULL, '2025-12-01 02:06:47', '2025-12-01 02:06:47', NULL),
(11, 'Siswa 9', 'siswa9@smamuhkasihan.sch.id', 'S009', '11', 'IPS', 'siswa', '$2y$10$W5fNN6EhWQazvP8/k5Z.AuxmBRTNaA8IkTC7HtQYoIwznmKhFF7Wi', NULL, '2025-12-01 02:06:47', '2025-12-01 02:06:47', NULL),
(12, 'Siswa 10', 'siswa10@smamuhkasihan.sch.id', 'S010', '10', 'IPA', 'siswa', '$2y$10$xSFfNYW6.vTOCtzlDrKd8.iaHbUvKKqux0aLzxHl2ZkfDxzYxTNNO', NULL, '2025-12-01 02:06:47', '2025-12-01 02:06:47', NULL),
(13, 'Siswa 11', 'siswa11@smamuhkasihan.sch.id', 'S011', '10', 'IPS', 'siswa', '$2y$10$t6aVOv8WoQ4gKswuUc0ziOQ4ts4PxaXM35OJyM37DApVsQmTZEdU6', NULL, '2025-12-01 02:06:47', '2025-12-01 02:06:47', NULL),
(14, 'Siswa 12', 'siswa12@smamuhkasihan.sch.id', 'S012', '11', 'IPS', 'siswa', '$2y$10$ynix4ZasAaOCrz1W9escWOJhgqxNBPZ8Oyf7bPKuW153ylobtPzum', NULL, '2025-12-01 02:06:48', '2025-12-01 02:06:48', NULL),
(15, 'Siswa 13', 'siswa13@smamuhkasihan.sch.id', 'S013', '10', 'IPA', 'siswa', '$2y$10$ueokFw3QPor/5p6kselZj.tmN8PZ14SjdegA2MkOHWqw1ASyg7MTK', NULL, '2025-12-01 02:06:48', '2025-12-01 02:06:48', NULL),
(16, 'Siswa 14', 'siswa14@smamuhkasihan.sch.id', 'S014', '11', 'IPS', 'siswa', '$2y$10$fucIyH1JQDgaOw1ASiT5Cek4Jc4ejoHqrzW4E18pwRxcXUBIp5B7m', NULL, '2025-12-01 02:06:48', '2025-12-01 02:06:48', NULL),
(17, 'Siswa 15', 'siswa15@smamuhkasihan.sch.id', 'S015', '11', 'IPS', 'siswa', '$2y$10$d8cUKnAApVuRbkl1YcPDVOfjXjOHfETxB1ggtYQg1hN.mwwkj.eu6', NULL, '2025-12-01 02:06:48', '2025-12-01 02:06:48', NULL),
(18, 'Siswa 16', 'siswa16@smamuhkasihan.sch.id', 'S016', '11', 'IPS', 'siswa', '$2y$10$IHL8bCutuvhLnuo9Wu2um.NUuqDM75aGZg.RJy5sTs0FdTdxM6/bu', NULL, '2025-12-01 02:06:48', '2025-12-01 02:06:48', NULL),
(19, 'Siswa 17', 'siswa17@smamuhkasihan.sch.id', 'S017', '12', 'IPS', 'siswa', '$2y$10$KwiYOv/K8csXfGYZLWXWCO1BomA4zNeRvmCrmgMk3bAX7cSAj/MGO', NULL, '2025-12-01 02:06:48', '2025-12-01 02:06:48', NULL),
(20, 'Siswa 18', 'siswa18@smamuhkasihan.sch.id', 'S018', '10', 'IPA', 'siswa', '$2y$10$6rpVNhZvDBvGXQH3y7Vfl.WKG8RvYLtkKXSqqOjHQhFseQo2fIL1O', NULL, '2025-12-01 02:06:48', '2025-12-01 02:06:48', NULL),
(21, 'Siswa 19', 'siswa19@smamuhkasihan.sch.id', 'S019', '10', 'IPA', 'siswa', '$2y$10$.DZdQmx4LLtytLTWgQEIXehFI0fe7VmXFojnz7baVQ3TIRAuAa/ea', NULL, '2025-12-01 02:06:48', '2025-12-01 02:06:48', NULL),
(22, 'Siswa 20', 'siswa20@smamuhkasihan.sch.id', 'S020', '10', 'IPA', 'siswa', '$2y$10$mPAIwM5DiG6OvrOVbEEkPentPkZsW13DYCXsnVO0NyYGpI3HSaYpu', NULL, '2025-12-01 02:06:48', '2025-12-01 02:06:48', NULL),
(23, 'Siswa 21', 'siswa21@smamuhkasihan.sch.id', 'S021', '12', 'IPS', 'siswa', '$2y$10$RVID0iI1FPBI/tZApWnM9.8Aesjc.ozhhVFDnAfZjFNQisQ3MgbiG', NULL, '2025-12-01 02:06:48', '2025-12-01 02:06:48', NULL),
(24, 'Siswa 22', 'siswa22@smamuhkasihan.sch.id', 'S022', '12', 'IPS', 'siswa', '$2y$10$2msczTTpdERga5XBGwRTjOly80gV/zy0/K0KYynvVYiTPv83bhbqO', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49', NULL),
(25, 'Siswa 23', 'siswa23@smamuhkasihan.sch.id', 'S023', '12', 'IPA', 'siswa', '$2y$10$SDHAU6cSus4zF.C.zKNDPutmpzx7qtXVmbhMend/oVoEH4BuL5OyO', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49', NULL),
(26, 'Siswa 24', 'siswa24@smamuhkasihan.sch.id', 'S024', '10', 'IPS', 'siswa', '$2y$10$/W/K3v6dOmcGmrB.A6EPDe21amzEw3UlL13/YXsZue2LWlNKQVeAK', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49', NULL),
(27, 'Siswa 25', 'siswa25@smamuhkasihan.sch.id', 'S025', '11', 'IPS', 'siswa', '$2y$10$JmV1yr/xv7NcbF38hjKzMenMD8OfyQd8Bnj2WFo/NfZ7stwft6vDu', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49', NULL),
(28, 'Siswa 26', 'siswa26@smamuhkasihan.sch.id', 'S026', '10', 'IPA', 'siswa', '$2y$10$QBo2j4gexli76TE6mJsn8uybp/.CyM9nkDtEGZU7TJB3z5kYzY6S6', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49', NULL),
(29, 'Siswa 27', 'siswa27@smamuhkasihan.sch.id', 'S027', '12', 'IPA', 'siswa', '$2y$10$YHznYYiq9ydfcqmk9Lo6f.49tu9KzjFFlg/6Oni866MxRYRNJxaUG', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49', NULL),
(30, 'Siswa 28', 'siswa28@smamuhkasihan.sch.id', 'S028', '12', 'IPA', 'siswa', '$2y$10$ingyO90KHbUE9dfC5p.Qt.vQxIsRFPp/PG..37GdJ4IaoUPUAtpiy', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49', NULL),
(31, 'Siswa 29', 'siswa29@smamuhkasihan.sch.id', 'S029', '12', 'IPA', 'siswa', '$2y$10$e6nCgJy9EIWqU.6Pu8FSlesruxQk8mc5.pVQzKbAtRRgFzAviQkfC', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49', NULL),
(32, 'Siswa 30', 'siswa30@smamuhkasihan.sch.id', 'S030', '11', 'IPA', 'siswa', '$2y$10$2pN7jUMaBYCZGXiZwcaP1e5HL1wDpGyy8zwTe1HhOGxlpo3YGN4RW', NULL, '2025-12-01 02:06:49', '2025-12-01 02:06:49', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jenis_pembayarans`
--
ALTER TABLE `jenis_pembayarans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jenis_pembayarans_penagihan_id_foreign` (`penagihan_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembayarans_user_id_foreign` (`user_id`),
  ADD KEY `pembayarans_jenis_pembayaran_id_foreign` (`jenis_pembayaran_id`);

--
-- Indexes for table `penagihans`
--
ALTER TABLE `penagihans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_nis_unique` (`nis`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_pembayarans`
--
ALTER TABLE `jenis_pembayarans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pembayarans`
--
ALTER TABLE `pembayarans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `penagihans`
--
ALTER TABLE `penagihans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jenis_pembayarans`
--
ALTER TABLE `jenis_pembayarans`
  ADD CONSTRAINT `jenis_pembayarans_penagihan_id_foreign` FOREIGN KEY (`penagihan_id`) REFERENCES `penagihans` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD CONSTRAINT `pembayarans_jenis_pembayaran_id_foreign` FOREIGN KEY (`jenis_pembayaran_id`) REFERENCES `jenis_pembayarans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayarans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
