-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 13, 2026 at 01:25 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `desa_bontomarannu`
--

-- --------------------------------------------------------

--
-- Table structure for table `desa_profile`
--

CREATE TABLE `desa_profile` (
  `id` bigint UNSIGNED NOT NULL,
  `deskripsi_lokasi` text COLLATE utf8mb4_unicode_ci,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `nama_desa` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kabupaten` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provinsi` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_pos` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_penduduk` int DEFAULT '0',
  `jumlah_kk` int DEFAULT '0',
  `tahun_berdiri` int DEFAULT NULL,
  `sejarah_desa` text COLLATE utf8mb4_unicode_ci,
  `alamat_kantor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kontak_wa` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kontak_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kontak_facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kontak_instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kontak_youtube` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visi` text COLLATE utf8mb4_unicode_ci,
  `misi` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `desa_profile`
--

INSERT INTO `desa_profile` (`id`, `deskripsi_lokasi`, `updated_by`, `created_at`, `updated_at`, `nama_desa`, `kecamatan`, `kabupaten`, `provinsi`, `kode_pos`, `jumlah_penduduk`, `jumlah_kk`, `tahun_berdiri`, `sejarah_desa`, `alamat_kantor`, `kontak_wa`, `kontak_email`, `kontak_facebook`, `kontak_instagram`, `kontak_youtube`, `visi`, `misi`) VALUES
(1, '', 2, '2026-01-01 07:48:50', '2026-05-22 21:36:23', 'Desa Bontomarannu1', 'Uluere', 'Bantaeng', 'Sulawesi Selatan', '92452', 1094, 519, 1977, 'Desa Bonto Marannu merupakan salah satu desa yang berada di Kecamatan Ulu Ere, Kabupaten Bantaeng, Provinsi Sulawesi Selatan. Desa ini telah dikenal sebagai wilayah pemukiman masyarakat pegunungan yang memiliki kondisi alam sejuk serta didominasi area pertanian dan perkebunan. Keberadaan Desa Bonto Marannu diperkirakan telah ada sejak puluhan tahun lalu dan berkembang secara bertahap seiring bertambahnya jumlah penduduk, pembangunan fasilitas umum, serta aktivitas sosial masyarakat. Beberapa wilayah di sekitar desa juga memiliki hubungan sejarah dengan Bonto Marannu sebelum terjadinya pemekaran desa di wilayah Kecamatan Ulu Ere.\r\n\r\nDalam perkembangannya, Desa Bonto Marannu terus mengalami kemajuan di berbagai sektor, khususnya bidang pendidikan, pertanian, dan pembangunan infrastruktur desa. Kehidupan masyarakat yang masih menjunjung tinggi nilai gotong royong dan budaya lokal menjadi salah satu ciri khas desa ini hingga saat ini. Dengan potensi alam yang dimiliki serta dukungan masyarakat yang aktif dalam pembangunan, Desa Bonto Marannu terus berupaya menjadi desa yang maju, mandiri, dan sejahtera di Kabupaten Bantaeng.', '', '6289647090775', 'alifqadry@gmail.com', 'https://www.facebook.com/lipppyy', '', '', 'Mewujudkan desa yang maju, mandiri, dan sejahtera melalui pembangunan yang merata dan berkelanjutan.\r\nMenciptakan masyarakat desa yang aman, religius, serta memiliki semangat gotong royong yang tinggi.', 'Meningkatkan kualitas pelayanan masyarakat, pembangunan infrastruktur, serta pemberdayaan ekonomi warga desa.\r\nMengembangkan potensi desa di bidang pendidikan, pertanian, UMKM, dan lingkungan demi kesejahteraan bersama.');

-- --------------------------------------------------------

--
-- Table structure for table `email_queue`
--

CREATE TABLE `email_queue` (
  `id` bigint UNSIGNED NOT NULL,
  `recipient` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `body` text COLLATE utf8mb4_general_ci NOT NULL,
  `is_sent` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = pending, 2 = processing, 1 = sent',
  `processing_token` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `processing_at` datetime DEFAULT NULL,
  `fail_count` int UNSIGNED NOT NULL DEFAULT '0',
  `last_error` text COLLATE utf8mb4_general_ci,
  `sent_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_queue`
--

INSERT INTO `email_queue` (`id`, `recipient`, `subject`, `body`, `is_sent`, `processing_token`, `processing_at`, `fail_count`, `last_error`, `sent_at`, `created_at`, `updated_at`) VALUES
(148, 'alifqadry@gmail.com', '212713 adalah kode reset password Anda', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Reset Password\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Description -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>user</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Gunakan kode ini untuk mereset password akun Anda. Masukkan kode tersebut di halaman verifikasi, atau klik tombol di bawah untuk langsung membuat password baru.\n            </p>\n          </td>\n        </tr>\n\n        <!-- OTP Code — besar, tanpa box -->\n        <tr>\n          <td style=\"padding:28px 40px 8px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:42px;font-weight:600;color:#202124;letter-spacing:8px;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              212713\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:13px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              Kode ini berlaku selama 1 jam.\n            </p>\n          </td>\n        </tr>\n\n        <!-- Button -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/reset-password/eyJlbWFpbCI6ImFsaWZxYWRyeUBnbWFpbC5jb20iLCJvdHAiOiIyMTI3MTMiLCJleHAiOjE3Nzk3MTA2NTd9.25646a9e7e2003f4325c46b47b7efc63eb28f6be2fb42bc49aed2543b29971a1\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Buat Password Baru\n            </a>\n          </td>\n        </tr>\n\n        <!-- Divider -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <!-- Disclaimer -->\n        <tr>\n          <td style=\"padding:16px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;line-height:1.6;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.\n            </p>\n          </td>\n        </tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 1, NULL, '2026-05-25 11:51:47', 0, NULL, '2026-05-25 11:51:54', '2026-05-25 11:04:17', '2026-05-25 11:51:54'),
(149, 'alifqadry@gmail.com', '027656 adalah kode reset password Anda', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Reset Password\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Description -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>user</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Gunakan kode ini untuk mereset password akun Anda. Masukkan kode tersebut di halaman verifikasi, atau klik tombol di bawah untuk langsung membuat password baru.\n            </p>\n          </td>\n        </tr>\n\n        <!-- OTP Code — besar, tanpa box -->\n        <tr>\n          <td style=\"padding:28px 40px 8px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:42px;font-weight:600;color:#202124;letter-spacing:8px;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              027656\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:13px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              Kode ini berlaku selama 1 jam.\n            </p>\n          </td>\n        </tr>\n\n        <!-- Button -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/reset-password/eyJlbWFpbCI6ImFsaWZxYWRyeUBnbWFpbC5jb20iLCJvdHAiOiIwMjc2NTYiLCJleHAiOjE3Nzk3MTM0MzB9.05919cbf00eedeff383d030030cec81d0f29d61b91ce6ad194ac5cf007f43e03\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Buat Password Baru\n            </a>\n          </td>\n        </tr>\n\n        <!-- Divider -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <!-- Disclaimer -->\n        <tr>\n          <td style=\"padding:16px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;line-height:1.6;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.\n            </p>\n          </td>\n        </tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 1, NULL, '2026-05-25 11:51:54', 0, NULL, '2026-05-25 11:52:03', '2026-05-25 11:50:30', '2026-05-25 11:52:03'),
(150, 'alifqadry@gmail.com', '675449 adalah kode reset password Anda', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Reset Password\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Description -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>user</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Gunakan kode ini untuk mereset password akun Anda. Masukkan kode tersebut di halaman verifikasi, atau klik tombol di bawah untuk langsung membuat password baru.\n            </p>\n          </td>\n        </tr>\n\n        <!-- OTP Code — besar, tanpa box -->\n        <tr>\n          <td style=\"padding:28px 40px 8px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:42px;font-weight:600;color:#202124;letter-spacing:8px;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              675449\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:13px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              Kode ini berlaku selama 1 jam.\n            </p>\n          </td>\n        </tr>\n\n        <!-- Button -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/reset-password/eyJlbWFpbCI6ImFsaWZxYWRyeUBnbWFpbC5jb20iLCJvdHAiOiI2NzU0NDkiLCJleHAiOjE3Nzk3MTM1MzV9.51d4d7e0d3693fcfcbc4f194a8de24c72a3a574dcf6438006dea5f47f1069fa8\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Buat Password Baru\n            </a>\n          </td>\n        </tr>\n\n        <!-- Divider -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <!-- Disclaimer -->\n        <tr>\n          <td style=\"padding:16px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;line-height:1.6;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.\n            </p>\n          </td>\n        </tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 1, NULL, '2026-05-25 11:52:41', 0, NULL, '2026-05-25 11:52:47', '2026-05-25 11:52:15', '2026-05-25 11:52:47'),
(151, 'alifqadry@gmail.com', '175654 adalah kode reset password Anda', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Reset Password\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Description -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>user</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Gunakan kode ini untuk mereset password akun Anda. Masukkan kode tersebut di halaman verifikasi, atau klik tombol di bawah untuk langsung membuat password baru.\n            </p>\n          </td>\n        </tr>\n\n        <!-- OTP Code — besar, tanpa box -->\n        <tr>\n          <td style=\"padding:28px 40px 8px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:42px;font-weight:600;color:#202124;letter-spacing:8px;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              175654\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:13px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              Kode ini berlaku selama 1 jam.\n            </p>\n          </td>\n        </tr>\n\n        <!-- Button -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/reset-password/eyJlbWFpbCI6ImFsaWZxYWRyeUBnbWFpbC5jb20iLCJvdHAiOiIxNzU2NTQiLCJleHAiOjE3Nzk3MTM3MzZ9.ead65cbd906c2f641a92830d91b1a52a4b875fa0ff1373fe71978e4e61a637ad\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Buat Password Baru\n            </a>\n          </td>\n        </tr>\n\n        <!-- Divider -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <!-- Disclaimer -->\n        <tr>\n          <td style=\"padding:16px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;line-height:1.6;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.\n            </p>\n          </td>\n        </tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-25 11:55:36', '2026-05-25 11:55:36'),
(152, 'alifqadry@gmail.com', '766366 adalah kode reset password Anda', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Reset Password\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Description -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>user</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Gunakan kode ini untuk mereset password akun Anda. Masukkan kode tersebut di halaman verifikasi, atau klik tombol di bawah untuk langsung membuat password baru.\n            </p>\n          </td>\n        </tr>\n\n        <!-- OTP Code — besar, tanpa box -->\n        <tr>\n          <td style=\"padding:28px 40px 8px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:42px;font-weight:600;color:#202124;letter-spacing:8px;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              766366\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:13px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              Kode ini berlaku selama 1 jam.\n            </p>\n          </td>\n        </tr>\n\n        <!-- Button -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/reset-password/eyJlbWFpbCI6ImFsaWZxYWRyeUBnbWFpbC5jb20iLCJvdHAiOiI3NjYzNjYiLCJleHAiOjE3Nzk3MTM4MDR9.9822ea067f2e2bbe0b7fd54f452e96ba1185012b1a6011fe84f373c481d40911\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Buat Password Baru\n            </a>\n          </td>\n        </tr>\n\n        <!-- Divider -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <!-- Disclaimer -->\n        <tr>\n          <td style=\"padding:16px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;line-height:1.6;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.\n            </p>\n          </td>\n        </tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-25 11:56:44', '2026-05-25 11:56:44'),
(153, 'alifqadry@gmail.com', '668232 adalah kode reset password Anda', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Reset Password\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Description -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>user</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Gunakan kode ini untuk mereset password akun Anda. Masukkan kode tersebut di halaman verifikasi, atau klik tombol di bawah untuk langsung membuat password baru.\n            </p>\n          </td>\n        </tr>\n\n        <!-- OTP Code — besar, tanpa box -->\n        <tr>\n          <td style=\"padding:28px 40px 8px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:42px;font-weight:600;color:#202124;letter-spacing:8px;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              668232\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:13px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              Kode ini berlaku selama 1 jam.\n            </p>\n          </td>\n        </tr>\n\n        <!-- Button -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/reset-password/eyJlbWFpbCI6ImFsaWZxYWRyeUBnbWFpbC5jb20iLCJvdHAiOiI2NjgyMzIiLCJleHAiOjE3Nzk3MTM4OTd9.cb36bd05660aa0a8082b9e33a0bd35e2943de2071f1c4bb3af784e597b29b5ee\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Buat Password Baru\n            </a>\n          </td>\n        </tr>\n\n        <!-- Divider -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <!-- Disclaimer -->\n        <tr>\n          <td style=\"padding:16px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;line-height:1.6;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.\n            </p>\n          </td>\n        </tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-25 11:58:17', '2026-05-25 11:58:17'),
(154, 'alifqadry15@gmail.com', 'Surat Baru Masuk', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat Baru Masuk\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Message -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>staf</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat baru dari Warga Desa Padang Loang\n            </p>\n          </td>\n        </tr>\n\n        <!-- Perihal (jika ada) -->\n                <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\n                       text-transform:uppercase;letter-spacing:0.5px;\">\n              Keterangan Usaha\n            </p>\n            <p style=\"margin:2px 0 0 0;font-size:14px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              DAS\n            </p>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/staff/surat/29\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Lihat Selengkapnya\n            </a>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <tr><td style=\"padding-bottom:4px;\"></td></tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-25 12:48:43', '2026-05-25 12:48:43'),
(155, 'admin1@padangloang.id', 'Surat Baru Masuk', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat Baru Masuk\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Message -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>staf2</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat baru dari Warga Desa Padang Loang\n            </p>\n          </td>\n        </tr>\n\n        <!-- Perihal (jika ada) -->\n                <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\n                       text-transform:uppercase;letter-spacing:0.5px;\">\n              Keterangan Usaha\n            </p>\n            <p style=\"margin:2px 0 0 0;font-size:14px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              DAS\n            </p>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/staff/surat/29\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Lihat Selengkapnya\n            </a>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <tr><td style=\"padding-bottom:4px;\"></td></tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-25 12:48:43', '2026-05-25 12:48:43'),
(156, 'alifgdsdspt12@gmail.com', 'Surat Baru Masuk', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat Baru Masuk\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Message -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>staff2</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat baru dari Warga Desa Padang Loang\n            </p>\n          </td>\n        </tr>\n\n        <!-- Perihal (jika ada) -->\n                <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\n                       text-transform:uppercase;letter-spacing:0.5px;\">\n              Keterangan Usaha\n            </p>\n            <p style=\"margin:2px 0 0 0;font-size:14px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              DAS\n            </p>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/staff/surat/29\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Lihat Selengkapnya\n            </a>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <tr><td style=\"padding-bottom:4px;\"></td></tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-25 12:48:43', '2026-05-25 12:48:43'),
(157, 'alifqadry15@gmail.com', 'Surat Baru Masuk', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat Baru Masuk\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Message -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>staf</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat baru dari Warga Desa Padang Loang\n            </p>\n          </td>\n        </tr>\n\n        <!-- Perihal (jika ada) -->\n                <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\n                       text-transform:uppercase;letter-spacing:0.5px;\">\n              Keterangan Usaha\n            </p>\n            <p style=\"margin:2px 0 0 0;font-size:14px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              DAS\n            </p>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/staff/surat/30\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Lihat Selengkapnya\n            </a>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <tr><td style=\"padding-bottom:4px;\"></td></tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-25 12:55:35', '2026-05-25 12:55:35');
INSERT INTO `email_queue` (`id`, `recipient`, `subject`, `body`, `is_sent`, `processing_token`, `processing_at`, `fail_count`, `last_error`, `sent_at`, `created_at`, `updated_at`) VALUES
(158, 'admin1@padangloang.id', 'Surat Baru Masuk', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat Baru Masuk\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Message -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>staf2</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat baru dari Warga Desa Padang Loang\n            </p>\n          </td>\n        </tr>\n\n        <!-- Perihal (jika ada) -->\n                <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\n                       text-transform:uppercase;letter-spacing:0.5px;\">\n              Keterangan Usaha\n            </p>\n            <p style=\"margin:2px 0 0 0;font-size:14px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              DAS\n            </p>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/staff/surat/30\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Lihat Selengkapnya\n            </a>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <tr><td style=\"padding-bottom:4px;\"></td></tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-25 12:55:35', '2026-05-25 12:55:35'),
(159, 'alifgdsdspt12@gmail.com', 'Surat Baru Masuk', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat Baru Masuk\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Message -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>staff2</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat baru dari Warga Desa Padang Loang\n            </p>\n          </td>\n        </tr>\n\n        <!-- Perihal (jika ada) -->\n                <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\n                       text-transform:uppercase;letter-spacing:0.5px;\">\n              Keterangan Usaha\n            </p>\n            <p style=\"margin:2px 0 0 0;font-size:14px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              DAS\n            </p>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/staff/surat/30\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Lihat Selengkapnya\n            </a>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <tr><td style=\"padding-bottom:4px;\"></td></tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-25 12:55:35', '2026-05-25 12:55:35'),
(160, 'alifqadry@gmail.com', '066002 adalah kode reset password Anda', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Reset Password\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Description -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>user</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Gunakan kode ini untuk mereset password akun Anda. Masukkan kode tersebut di halaman verifikasi, atau klik tombol di bawah untuk langsung membuat password baru.\n            </p>\n          </td>\n        </tr>\n\n        <!-- OTP Code — besar, tanpa box -->\n        <tr>\n          <td style=\"padding:28px 40px 8px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:42px;font-weight:600;color:#202124;letter-spacing:8px;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              066002\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:13px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              Kode ini berlaku selama 1 jam.\n            </p>\n          </td>\n        </tr>\n\n        <!-- Button -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/reset-password/eyJlbWFpbCI6ImFsaWZxYWRyeUBnbWFpbC5jb20iLCJvdHAiOiIwNjYwMDIiLCJleHAiOjE3Nzk3MTc3NjN9.32b3facb25eb46511d785268d0a6ed953e145c0f399d849141b7cbf939b995ef\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Buat Password Baru\n            </a>\n          </td>\n        </tr>\n\n        <!-- Divider -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <!-- Disclaimer -->\n        <tr>\n          <td style=\"padding:16px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;line-height:1.6;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.\n            </p>\n          </td>\n        </tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-25 13:02:43', '2026-05-25 13:02:43'),
(161, 'alifqadry@gmail.com', '262938 adalah kode reset password Anda', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Reset Password\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Description -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>user</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Gunakan kode ini untuk mereset password akun Anda. Masukkan kode tersebut di halaman verifikasi, atau klik tombol di bawah untuk langsung membuat password baru.\n            </p>\n          </td>\n        </tr>\n\n        <!-- OTP Code — besar, tanpa box -->\n        <tr>\n          <td style=\"padding:28px 40px 8px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:42px;font-weight:600;color:#202124;letter-spacing:8px;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              262938\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:13px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              Kode ini berlaku selama 1 jam.\n            </p>\n          </td>\n        </tr>\n\n        <!-- Button -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/reset-password/eyJlbWFpbCI6ImFsaWZxYWRyeUBnbWFpbC5jb20iLCJvdHAiOiIyNjI5MzgiLCJleHAiOjE3Nzk3MTk4NzZ9.1ac4564e9265d7528cfa1401cf2a4d255169c54c9d58c320015b3a5d7c0536b9\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Buat Password Baru\n            </a>\n          </td>\n        </tr>\n\n        <!-- Divider -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <!-- Disclaimer -->\n        <tr>\n          <td style=\"padding:16px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;line-height:1.6;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.\n            </p>\n          </td>\n        </tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-25 13:37:56', '2026-05-25 13:37:56'),
(162, 'alifqadry15@gmail.com', '795106 adalah kode reset password Anda', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Reset Password\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Description -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>staf</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Gunakan kode ini untuk mereset password akun Anda. Masukkan kode tersebut di halaman verifikasi, atau klik tombol di bawah untuk langsung membuat password baru.\n            </p>\n          </td>\n        </tr>\n\n        <!-- OTP Code — besar, tanpa box -->\n        <tr>\n          <td style=\"padding:28px 40px 8px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:42px;font-weight:600;color:#202124;letter-spacing:8px;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              795106\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:13px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              Kode ini berlaku selama 1 jam.\n            </p>\n          </td>\n        </tr>\n\n        <!-- Button -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/reset-password/eyJlbWFpbCI6ImFsaWZxYWRyeTE1QGdtYWlsLmNvbSIsIm90cCI6Ijc5NTEwNiIsImV4cCI6MTc3OTcyMDA1OH0=.650d4e14de4e72a88f7a3f8e34766314fe32855b6c7c9e4ac0da4c68dfdf82bb\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Buat Password Baru\n            </a>\n          </td>\n        </tr>\n\n        <!-- Divider -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <!-- Disclaimer -->\n        <tr>\n          <td style=\"padding:16px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;line-height:1.6;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.\n            </p>\n          </td>\n        </tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-25 13:40:58', '2026-05-25 13:40:58'),
(163, 'alifqadry@gmail.com', '745754 adalah kode reset password Anda', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Reset Password\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Description -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>user</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Gunakan kode ini untuk mereset password akun Anda. Masukkan kode tersebut di halaman verifikasi, atau klik tombol di bawah untuk langsung membuat password baru.\n            </p>\n          </td>\n        </tr>\n\n        <!-- OTP Code — besar, tanpa box -->\n        <tr>\n          <td style=\"padding:28px 40px 8px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:42px;font-weight:600;color:#202124;letter-spacing:8px;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              745754\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:13px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              Kode ini berlaku selama 1 jam.\n            </p>\n          </td>\n        </tr>\n\n        <!-- Button -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/reset-password/eyJlbWFpbCI6ImFsaWZxYWRyeUBnbWFpbC5jb20iLCJvdHAiOiI3NDU3NTQiLCJleHAiOjE3Nzk3MjAxMjB9.39ad7c5810413ccf5c348dfb102b3da2fd5f1ce46ac04da056c650c4e3eeb804\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Buat Password Baru\n            </a>\n          </td>\n        </tr>\n\n        <!-- Divider -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <!-- Disclaimer -->\n        <tr>\n          <td style=\"padding:16px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;line-height:1.6;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.\n            </p>\n          </td>\n        </tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-25 13:42:00', '2026-05-25 13:42:00'),
(164, 'alifqadry@gmail.com', '638551 adalah kode reset password Anda', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Reset Password\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Description -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>user</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Gunakan kode ini untuk mereset password akun Anda. Masukkan kode tersebut di halaman verifikasi, atau klik tombol di bawah untuk langsung membuat password baru.\n            </p>\n          </td>\n        </tr>\n\n        <!-- OTP Code — besar, tanpa box -->\n        <tr>\n          <td style=\"padding:28px 40px 8px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:42px;font-weight:600;color:#202124;letter-spacing:8px;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              638551\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:13px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              Kode ini berlaku selama 1 jam.\n            </p>\n          </td>\n        </tr>\n\n        <!-- Button -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/reset-password/eyJlbWFpbCI6ImFsaWZxYWRyeUBnbWFpbC5jb20iLCJvdHAiOiI2Mzg1NTEiLCJleHAiOjE3Nzk3MjAyMzd9.055619d80fe6708c586c983f2d4c675ca14e56d818dd3a8b7c103f2f5c1239ef\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Buat Password Baru\n            </a>\n          </td>\n        </tr>\n\n        <!-- Divider -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <!-- Disclaimer -->\n        <tr>\n          <td style=\"padding:16px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;line-height:1.6;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.\n            </p>\n          </td>\n        </tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-25 13:43:57', '2026-05-25 13:43:57'),
(165, 'alifqadry@gmail.com', '220478 adalah kode reset password Anda', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Reset Password\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Description -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>user</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Gunakan kode ini untuk mereset password akun Anda. Masukkan kode tersebut di halaman verifikasi, atau klik tombol di bawah untuk langsung membuat password baru.\n            </p>\n          </td>\n        </tr>\n\n        <!-- OTP Code — besar, tanpa box -->\n        <tr>\n          <td style=\"padding:28px 40px 8px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:42px;font-weight:600;color:#202124;letter-spacing:8px;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              220478\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:13px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              Kode ini berlaku selama 1 jam.\n            </p>\n          </td>\n        </tr>\n\n        <!-- Button -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/reset-password/eyJlbWFpbCI6ImFsaWZxYWRyeUBnbWFpbC5jb20iLCJvdHAiOiIyMjA0NzgiLCJleHAiOjE3Nzk3NjAwNDJ9.c275e91105c8ab3dcece7a6cd6700605d0fd1fb93d49180f79805d6839240e84\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Buat Password Baru\n            </a>\n          </td>\n        </tr>\n\n        <!-- Divider -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <!-- Disclaimer -->\n        <tr>\n          <td style=\"padding:16px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;line-height:1.6;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.\n            </p>\n          </td>\n        </tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-26 00:47:22', '2026-05-26 00:47:22'),
(166, 'alifqadry@gmail.com', '046952 adalah kode reset password Anda', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Reset Password\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Description -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>user</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Gunakan kode ini untuk mereset password akun Anda. Masukkan kode tersebut di halaman verifikasi, atau klik tombol di bawah untuk langsung membuat password baru.\n            </p>\n          </td>\n        </tr>\n\n        <!-- OTP Code — besar, tanpa box -->\n        <tr>\n          <td style=\"padding:28px 40px 8px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:42px;font-weight:600;color:#202124;letter-spacing:8px;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              046952\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:13px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              Kode ini berlaku selama 1 jam.\n            </p>\n          </td>\n        </tr>\n\n        <!-- Button -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/reset-password/eyJlbWFpbCI6ImFsaWZxYWRyeUBnbWFpbC5jb20iLCJvdHAiOiIwNDY5NTIiLCJleHAiOjE3Nzk3NjAxMDZ9.ed78abaa69b60d4360b65987282a8e198bb495896072ba8e0c29b014a0abc955\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Buat Password Baru\n            </a>\n          </td>\n        </tr>\n\n        <!-- Divider -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <!-- Disclaimer -->\n        <tr>\n          <td style=\"padding:16px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;line-height:1.6;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.\n            </p>\n          </td>\n        </tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-26 00:48:26', '2026-05-26 00:48:26'),
(167, 'alifqadry@gmail.com', '803494 adalah kode reset password Anda', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Reset Password\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Description -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>user</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Gunakan kode ini untuk mereset password akun Anda. Masukkan kode tersebut di halaman verifikasi, atau klik tombol di bawah untuk langsung membuat password baru.\n            </p>\n          </td>\n        </tr>\n\n        <!-- OTP Code — besar, tanpa box -->\n        <tr>\n          <td style=\"padding:28px 40px 8px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:42px;font-weight:600;color:#202124;letter-spacing:8px;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              803494\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:13px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              Kode ini berlaku selama 1 jam.\n            </p>\n          </td>\n        </tr>\n\n        <!-- Button -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/reset-password/eyJlbWFpbCI6ImFsaWZxYWRyeUBnbWFpbC5jb20iLCJvdHAiOiI4MDM0OTQiLCJleHAiOjE3Nzk3NjAxOTJ9.f8a083035e877dec0c4f387980831bc337231e57bd49aaabc01175b602599d4c\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Buat Password Baru\n            </a>\n          </td>\n        </tr>\n\n        <!-- Divider -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <!-- Disclaimer -->\n        <tr>\n          <td style=\"padding:16px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;line-height:1.6;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.\n            </p>\n          </td>\n        </tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-26 00:49:52', '2026-05-26 00:49:52');
INSERT INTO `email_queue` (`id`, `recipient`, `subject`, `body`, `is_sent`, `processing_token`, `processing_at`, `fail_count`, `last_error`, `sent_at`, `created_at`, `updated_at`) VALUES
(168, 'alifqadry@gmail.com', 'Surat Anda dibalas', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat Anda dibalas\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Message -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>user</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Balasan baru dari Bahlil Lahadalia untuk surat: TES NOTIF\n            </p>\n          </td>\n        </tr>\n\n        <!-- Perihal (jika ada) -->\n                <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\n                       text-transform:uppercase;letter-spacing:0.5px;\">\n              Keterangan Usaha\n            </p>\n            <p style=\"margin:2px 0 0 0;font-size:14px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              TES NOTIF\n            </p>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/user/surat/28\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Lihat Selengkapnya\n            </a>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <tr><td style=\"padding-bottom:4px;\"></td></tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-26 02:47:07', '2026-05-26 02:47:07'),
(169, 'alifqadry15@gmail.com', 'Surat Baru Masuk', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat Baru Masuk\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Message -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>staf</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat baru dari Natalius Pigai\n            </p>\n          </td>\n        </tr>\n\n        <!-- Perihal (jika ada) -->\n                <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\n                       text-transform:uppercase;letter-spacing:0.5px;\">\n              Keterangan Tidak Mampu\n            </p>\n            <p style=\"margin:2px 0 0 0;font-size:14px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              SURAT TIDAK MAMPU\n            </p>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/staff/surat/31\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Lihat Selengkapnya\n            </a>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <tr><td style=\"padding-bottom:4px;\"></td></tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-26 02:48:01', '2026-05-26 02:48:01'),
(170, 'admin1@padangloang.id', 'Surat Baru Masuk', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat Baru Masuk\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Message -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>staf2</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat baru dari Natalius Pigai\n            </p>\n          </td>\n        </tr>\n\n        <!-- Perihal (jika ada) -->\n                <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\n                       text-transform:uppercase;letter-spacing:0.5px;\">\n              Keterangan Tidak Mampu\n            </p>\n            <p style=\"margin:2px 0 0 0;font-size:14px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              SURAT TIDAK MAMPU\n            </p>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/staff/surat/31\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Lihat Selengkapnya\n            </a>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <tr><td style=\"padding-bottom:4px;\"></td></tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-26 02:48:01', '2026-05-26 02:48:01'),
(171, 'alifgdsdspt12@gmail.com', 'Surat Baru Masuk', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat Baru Masuk\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Message -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>staff2</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat baru dari Natalius Pigai\n            </p>\n          </td>\n        </tr>\n\n        <!-- Perihal (jika ada) -->\n                <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\n                       text-transform:uppercase;letter-spacing:0.5px;\">\n              Keterangan Tidak Mampu\n            </p>\n            <p style=\"margin:2px 0 0 0;font-size:14px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              SURAT TIDAK MAMPU\n            </p>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/staff/surat/31\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Lihat Selengkapnya\n            </a>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <tr><td style=\"padding-bottom:4px;\"></td></tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-26 02:48:01', '2026-05-26 02:48:01'),
(172, 'alifqadry@gmail.com', 'Surat Anda telah dibaca', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat Anda telah dibaca\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Message -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>user</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat Anda: SURAT TIDAK MAMPU telah dibaca oleh Bahlil Lahadalia\n            </p>\n          </td>\n        </tr>\n\n        <!-- Perihal (jika ada) -->\n                <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\n                       text-transform:uppercase;letter-spacing:0.5px;\">\n              Keterangan Tidak Mampu\n            </p>\n            <p style=\"margin:2px 0 0 0;font-size:14px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              SURAT TIDAK MAMPU\n            </p>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/user/surat/31\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Lihat Selengkapnya\n            </a>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <tr><td style=\"padding-bottom:4px;\"></td></tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-26 02:48:08', '2026-05-26 02:48:08'),
(173, 'alifqadry@gmail.com', 'Surat Anda diterima', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat Anda diterima\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Message -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>user</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat Anda: SURAT TIDAK MAMPU telah diterima oleh Bahlil Lahadalia\n            </p>\n          </td>\n        </tr>\n\n        <!-- Perihal (jika ada) -->\n                <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\n                       text-transform:uppercase;letter-spacing:0.5px;\">\n              Keterangan Tidak Mampu\n            </p>\n            <p style=\"margin:2px 0 0 0;font-size:14px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              SURAT TIDAK MAMPU\n            </p>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/user/surat/31\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Lihat Selengkapnya\n            </a>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <tr><td style=\"padding-bottom:4px;\"></td></tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-26 02:48:40', '2026-05-26 02:48:40'),
(174, 'alifqadry@gmail.com', 'Surat Anda dibalas', '<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n  <style>\n    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap\');\n  </style>\n</head>\n<body style=\"margin:0;padding:0;background:#f1f3f4;font-family:\'Poppins\',Arial,sans-serif;\">\n<table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"\n       style=\"background:#f1f3f4;padding:24px 16px 32px;\">\n  <tr>\n    <td align=\"center\">\n      <!-- Card -->\n      <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"520\"\n             style=\"max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;\">\n\n        <!-- Logo -->\n        <tr>\n          <td style=\"padding:28px 40px 0 40px;text-align:center;\">\n            <img src=\"https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png\" alt=\"Website Desa Bonto Marannu\" width=\"64\" height=\"64\"\n                 style=\"display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;\">\n          </td>\n        </tr>\n\n        <!-- Body -->\n        \n        <!-- Title -->\n        <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <h1 style=\"margin:0;font-size:20px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              Surat Anda dibalas\n            </h1>\n          </td>\n        </tr>\n\n        <!-- Message -->\n        <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Halo <strong>user</strong>,\n            </p>\n            <p style=\"margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;\n                      font-family:\'Poppins\',Arial,sans-serif;\">\n              Balasan baru dari Bahlil Lahadalia untuk surat: SURAT TIDAK MAMPU\n            </p>\n          </td>\n        </tr>\n\n        <!-- Perihal (jika ada) -->\n                <tr>\n          <td style=\"padding:12px 40px 0 40px;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\n                       text-transform:uppercase;letter-spacing:0.5px;\">\n              Keterangan Tidak Mampu\n            </p>\n            <p style=\"margin:2px 0 0 0;font-size:14px;font-weight:600;color:#202124;\n                       font-family:\'Poppins\',Arial,sans-serif;\">\n              SURAT TIDAK MAMPU\n            </p>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;text-align:center;\">\n            <a href=\"https://bontomarannu.test/user/surat/31\" target=\"_blank\"\n               style=\"display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;\n                      font-family:\'Poppins\',Arial,sans-serif;font-size:14px;font-weight:500;\n                      padding:10px 28px;border-radius:4px;\">\n              Lihat Selengkapnya\n            </a>\n          </td>\n        </tr>\n\n                <tr>\n          <td style=\"padding:24px 40px 0 40px;\">\n            <div style=\"height:1px;background:#e0e0e0;\"></div>\n          </td>\n        </tr>\n\n        <tr><td style=\"padding-bottom:4px;\"></td></tr>\n\n\n        <!-- Footer -->\n        <tr>\n          <td style=\"padding:20px 40px 28px 40px;text-align:center;\">\n            <p style=\"margin:0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;line-height:1.6;\">\n              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.\n            </p>\n            <p style=\"margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:\'Poppins\',Arial,sans-serif;\">\n              &copy; 2026 Website Desa Bonto Marannu\n            </p>\n          </td>\n        </tr>\n\n      </table>\n    </td>\n  </tr>\n</table>\n</body>\n</html>', 0, NULL, NULL, 0, NULL, NULL, '2026-05-26 02:48:53', '2026-05-26 02:48:53');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_albums`
--

CREATE TABLE `gallery_albums` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_album` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `tanggal_waktu` datetime NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery_albums`
--

INSERT INTO `gallery_albums` (`id`, `nama_album`, `deskripsi`, `tanggal_waktu`, `thumbnail`, `created_by`, `created_at`, `updated_at`) VALUES
(9, 'Pelatihan UMKM Masyarakat', 'Dokumentasi pelatihan pengembangan usaha kecil masyarakat desa untuk meningkatkan keterampilan dan perekonomian warga.', '2026-01-03 10:44:00', 'uploads/gallery/1779594800_69ed27e17468b6df4503.webp', 2, '2026-01-03 10:44:48', '2026-05-24 11:53:20'),
(10, 'Momen pelayanan kesehatan balita dan ibu yang dilaksanakan rutin sebagai bentuk perhatian desa terhadap kesehatan masyarakat.', 'Momen pelayanan kesehatan balita dan ibu yang dilaksanakan rutin sebagai bentuk perhatian desa terhadap kesehatan masyarakat.', '2026-01-03 10:45:00', 'uploads/gallery/1779594735_1f86028a8f1bf7e8a7fa.webp', 2, '2026-01-03 10:45:24', '2026-05-24 11:52:15'),
(12, 'Pemandangan Alam Bontomarannu', 'Koleksi foto suasana alam dan lingkungan Desa Bontomarannu yang asri, hijau, dan nyaman dipandang.', '2026-01-03 10:45:00', 'uploads/gallery/1779594763_b2d22c72f9751a61990e.webp', 2, '2026-01-03 10:45:56', '2026-05-24 11:52:43'),
(15, 'Festival Budaya Desa', 'Kumpulan foto kegiatan seni dan budaya yang menampilkan tarian tradisional, musik daerah, dan penampilan masyarakat desa.', '2026-01-04 11:45:00', 'uploads/gallery/1779594553_7960a70efd8a504bbd8a.webp', 2, '2026-01-04 11:46:55', '2026-05-24 11:49:13'),
(17, 'Gotong Royong Warga Desa', 'Dokumentasi kegiatan gotong royong masyarakat Desa Bontomarannu dalam menjaga kebersihan lingkungan dan mempererat kebersamaan antarwarga.', '2026-05-10 17:48:00', 'uploads/gallery/1779594438_f71875d5c3816a97dddb.webp', 2, '2026-05-10 17:49:07', '2026-05-24 11:47:18');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_media`
--

CREATE TABLE `gallery_media` (
  `id` bigint UNSIGNED NOT NULL,
  `album_id` bigint UNSIGNED NOT NULL,
  `media_type` enum('foto','video_link') COLLATE utf8mb4_unicode_ci NOT NULL,
  `media_path` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery_media`
--

INSERT INTO `gallery_media` (`id`, `album_id`, `media_type`, `media_path`, `created_at`) VALUES
(33, 17, 'foto', 'uploads/gallery/1779594370_8833495b7894f06e05c9.webp', '2026-05-24 11:46:10'),
(34, 17, 'foto', 'uploads/gallery/1779594438_58895e56454ea369753d.webp', '2026-05-24 11:47:18'),
(35, 15, 'foto', 'uploads/gallery/1779594553_721cf88dc42db43f1959.webp', '2026-05-24 11:49:13'),
(36, 10, 'foto', 'uploads/gallery/1779594735_ca75fd6a7dafa20bff7a.webp', '2026-05-24 11:52:15'),
(37, 12, 'foto', 'uploads/gallery/1779594763_ff3f94d983b8ce3648b0.webp', '2026-05-24 11:52:43'),
(38, 9, 'foto', 'uploads/gallery/1779594800_62a65721420ceae30b37.webp', '2026-05-24 11:53:20');

-- --------------------------------------------------------

--
-- Table structure for table `geografi_desa`
--

CREATE TABLE `geografi_desa` (
  `id` int UNSIGNED NOT NULL,
  `maps_url` text COLLATE utf8mb4_general_ci,
  `maps_embed_url` text COLLATE utf8mb4_general_ci,
  `luas_wilayah` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `batas_wilayah` text COLLATE utf8mb4_general_ci,
  `kondisi_geografis` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `geografi_desa`
--

INSERT INTO `geografi_desa` (`id`, `maps_url`, `maps_embed_url`, `luas_wilayah`, `batas_wilayah`, `kondisi_geografis`, `created_at`, `updated_at`) VALUES
(1, NULL, 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7943.595994392008!2d119.91249047356614!3d-5.447609309467859!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbeb0e2a1e219d5%3A0x964855ec2fd33e44!2sBonto%20Marannu%2C%20Kec.%20Uluere%2C%20Kabupaten%20Bantaeng%2C%20Sulawesi%20Selatan!5e0!3m2!1sid!2sid!4v1768373212743!5m2!1sid!2sid', '67,29 km²', 'Sebelah Utara : Kabupaten Gowa\r\nSebelah Selatan : Desa Bonto Lojong\r\nSebelah Timur : Kabupaten Bulukumba\r\nSebelah Barat : Desa Bonto Tangnga', 'Desa Bonto Marannu merupakan salah satu desa yang terletak di Kecamatan Ulu Ere, Kabupaten Bantaeng, Provinsi Sulawesi Selatan. Desa ini berada di wilayah dataran tinggi dengan kondisi alam yang sejuk serta memiliki hamparan lahan pertanian dan perkebunan yang cukup luas. Sebagian besar masyarakat Desa Bonto Marannu bermata pencaharian di sektor pertanian, sehingga kondisi geografis desa sangat mendukung aktivitas perkebunan dan pertanian masyarakat. Akses menuju desa dapat ditempuh melalui jalur darat dengan kondisi wilayah yang didominasi perbukitan dan area hijau alami.', '2026-01-14 06:40:47', '2026-05-22 14:16:33');

-- --------------------------------------------------------

--
-- Table structure for table `inventaris_desa`
--

CREATE TABLE `inventaris_desa` (
  `id` int UNSIGNED NOT NULL,
  `nama_barang` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total` int NOT NULL DEFAULT '0',
  `status` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Baik',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventaris_desa`
--

INSERT INTO `inventaris_desa` (`id`, `nama_barang`, `jenis`, `foto`, `total`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Jembatan', 'Bangunan / Gedung', 'uploads/inventaris/1768392915_d8f4a82e126e7aee340a.jpg', 12, 'Baik', '2026-01-14 12:15:15', '2026-01-14 12:15:15'),
(2, 'ADSADASD', 'Bangunan / Gedung', 'uploads/inventaris/1779162341_4f536885ac61d8ecd570.webp', 1, 'Rusak Berat', '2026-05-19 03:45:41', '2026-05-23 14:48:45');

-- --------------------------------------------------------

--
-- Table structure for table `letters`
--

CREATE TABLE `letters` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_unik` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `assigned_staff_id` bigint UNSIGNED DEFAULT NULL,
  `judul_perihal` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe_surat` enum('Keterangan Usaha','Keterangan Tidak Mampu','Keterangan Belum Menikah','Keterangan Domisili','Undangan','Lain Lain') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Lain Lain',
  `isi_surat` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Menunggu','Dibaca','Diterima','Ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `sent_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `read_at` datetime DEFAULT NULL,
  `replied_at` datetime DEFAULT NULL,
  `catatan_penolakan` text COLLATE utf8mb4_unicode_ci,
  `decided_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `letters`
--

INSERT INTO `letters` (`id`, `kode_unik`, `user_id`, `assigned_staff_id`, `judul_perihal`, `tipe_surat`, `isi_surat`, `status`, `sent_at`, `read_at`, `replied_at`, `catatan_penolakan`, `decided_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 3, 5, 'tes', '', 'tes', '', '2026-01-01 02:21:55', '2026-01-01 02:22:03', '2026-01-03 23:39:40', NULL, NULL, '2026-01-01 10:21:55', '2026-05-07 07:18:46'),
(3, NULL, 3, 2, 'OKE', '', '121231', 'Dibaca', '2026-01-02 10:42:51', '2026-01-02 23:10:16', NULL, NULL, NULL, '2026-01-02 18:42:51', '2026-01-03 07:10:16'),
(4, NULL, 3, NULL, 'OKE', '', 'WQWQW', '', '2026-01-03 00:13:14', NULL, NULL, NULL, NULL, '2026-01-03 08:13:14', '2026-05-07 07:18:46'),
(6, NULL, 3, NULL, '212 EFRAD', '', 'ASDADA', '', '2026-01-03 00:13:25', NULL, NULL, NULL, NULL, '2026-01-03 08:13:25', '2026-05-07 07:18:46'),
(7, NULL, 3, NULL, 'ADSASD', '', 'ADSADADSA', '', '2026-01-03 00:13:30', NULL, NULL, NULL, NULL, '2026-01-03 08:13:30', '2026-05-07 07:18:46'),
(8, NULL, 3, NULL, 'DASDASDAD', '', 'ASDASD', '', '2026-01-03 00:13:34', NULL, NULL, NULL, NULL, '2026-01-03 08:13:34', '2026-05-07 07:18:46'),
(9, NULL, 3, NULL, 'ASDASDA', '', 'ADASDAD', '', '2026-01-03 00:13:39', NULL, NULL, NULL, NULL, '2026-01-03 08:13:39', '2026-05-07 07:18:46'),
(10, NULL, 3, 5, 'ADASDASD', '', 'ASDASDSAD', 'Dibaca', '2026-01-03 00:13:43', '2026-01-03 23:43:04', NULL, NULL, NULL, '2026-01-03 08:13:43', '2026-01-04 07:43:04'),
(11, NULL, 3, 2, 'ASDDSAD', '', 'DASDASDASD', 'Dibaca', '2026-01-03 00:13:48', '2026-01-03 00:14:49', NULL, NULL, NULL, '2026-01-03 08:13:48', '2026-01-03 08:14:49'),
(12, NULL, 3, 2, 'ADASDAS', '', 'ASDASDASD', 'Dibaca', '2026-01-03 00:13:52', '2026-01-03 22:55:59', NULL, NULL, NULL, '2026-01-03 08:13:52', '2026-01-04 06:55:59'),
(13, 'SURAT-20260104-C72F8A', 3, 5, 'TES KODE', '', 'EDAEAWE', 'Dibaca', '2026-01-04 00:04:04', '2026-01-04 00:04:14', NULL, NULL, NULL, '2026-01-04 08:04:04', '2026-01-04 08:04:14'),
(14, 'SURAT-20260104-AA614A', 3, 2, 'surat bansos', '', 'tes', '', '2026-01-04 03:39:49', '2026-01-04 03:40:07', '2026-01-04 03:44:03', NULL, NULL, '2026-01-04 11:39:49', '2026-05-07 07:18:46'),
(15, 'SURAT-20260105-7D17A0', 3, 2, 'OKE', 'Keterangan Usaha', '212121', 'Dibaca', '2026-01-05 05:24:58', '2026-01-05 23:19:25', NULL, NULL, NULL, '2026-01-05 13:24:58', '2026-01-06 07:19:25'),
(16, 'SURAT-20260106-BC5033', 3, 2, '1212', 'Keterangan Tidak Mampu', '121212', 'Dibaca', '2026-01-06 00:17:43', '2026-01-06 00:17:48', NULL, NULL, NULL, '2026-01-06 08:17:43', '2026-01-06 08:17:48'),
(17, 'SURAT-20260106-E505B1', 3, 2, 'TES 12212', 'Keterangan Belum Menikah', '1212', 'Dibaca', '2026-01-06 01:27:27', '2026-01-06 01:27:33', NULL, NULL, NULL, '2026-01-06 09:27:27', '2026-01-06 09:27:33'),
(18, 'SURAT-20260106-AAED80', 3, 2, 'tes 1212', 'Keterangan Domisili', 'qweqe', 'Dibaca', '2026-01-06 01:52:47', '2026-01-06 01:52:53', NULL, NULL, NULL, '2026-01-06 09:52:47', '2026-01-06 09:52:53'),
(19, 'SURAT-20260106-47E56D', 3, 2, 'WEQA', 'Undangan', 'ADADSAD', 'Dibaca', '2026-01-06 02:01:59', '2026-01-06 02:02:10', NULL, NULL, NULL, '2026-01-06 10:01:59', '2026-01-06 10:02:10'),
(21, 'SURAT-20260108-F8F305', 3, 2, 'TES NOTIF', 'Keterangan Usaha', '121212', 'Ditolak', '2026-01-08 12:18:51', '2026-01-08 12:19:53', NULL, 'SDA', '2026-05-10 12:37:15', '2026-01-08 20:18:51', '2026-05-10 20:37:15'),
(22, 'SURAT-20260108-008E6C', 3, 2, 'TES NOTIF', 'Keterangan Tidak Mampu', '1212', 'Diterima', '2026-01-08 12:45:31', '2026-01-08 13:05:09', NULL, '', '2026-05-08 13:50:10', '2026-01-08 20:45:31', '2026-05-08 21:50:10'),
(24, 'SURAT-20260108-354FAF', 3, 2, 'tes notif 2', 'Keterangan Usaha', 'asdada', 'Diterima', '2026-01-08 13:02:28', '2026-01-11 12:09:00', NULL, 'OMKE GAS', '2026-05-07 00:16:39', '2026-01-08 21:02:28', '2026-05-07 08:16:39'),
(25, 'SURAT-20260510-DC94DA', 3, 2, 'DAS', 'Keterangan Usaha', 'ASDASD SDFSD SDF SDF SED FDSF SDF', 'Dibaca', '2026-05-10 12:36:57', '2026-05-10 12:41:24', NULL, NULL, NULL, '2026-05-10 20:36:57', '2026-05-25 20:52:54'),
(26, 'SURAT-20260518-7F5873', 3, 2, 'TES SURAT', 'Keterangan Usaha', 'TES SAGAFAFSDF ASFD SAD', 'Diterima', '2026-05-18 01:55:28', '2026-05-18 01:55:59', NULL, 'OKE', '2026-05-18 02:10:01', '2026-05-18 09:55:28', '2026-05-18 10:10:01'),
(28, 'SURAT-20260525-3802E7', 3, 2, 'TES NOTIF', 'Keterangan Usaha', 'SDFFFFFFFFFFFFFFFF', 'Diterima', '2026-05-25 09:46:39', '2026-05-25 09:48:15', NULL, 'OKE', '2026-05-25 09:49:39', '2026-05-25 17:46:39', '2026-05-25 17:49:39'),
(31, 'SURAT-20260526-6D8540', 3, 2, 'SURAT TIDAK MAMPU', 'Keterangan Tidak Mampu', 'BUAT DAFTAR BEASISWA', 'Diterima', '2026-05-26 02:48:01', '2026-05-26 02:48:07', NULL, '', '2026-05-26 02:48:40', '2026-05-26 10:48:01', '2026-05-26 10:48:40');

-- --------------------------------------------------------

--
-- Table structure for table `letter_attachments`
--

CREATE TABLE `letter_attachments` (
  `id` bigint UNSIGNED NOT NULL,
  `letter_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint UNSIGNED DEFAULT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `letter_attachments`
--

INSERT INTO `letter_attachments` (`id`, `letter_id`, `file_path`, `original_name`, `mime_type`, `file_size`, `uploaded_at`) VALUES
(1, 1, 'uploads/letters/1767234115_ca2cea433cc57846a7e0.pdf', 'Rancangan_Fitur_Website_Desa_Padang_Loang.pdf', 'application/pdf', 36764, '2026-01-01 10:21:55'),
(3, 4, 'uploads/letters/1767399194_1504f7a49c471aa14d85.pdf', 'Markdown to PDF (2).pdf', 'application/pdf', 50128, '2026-01-03 08:13:14'),
(4, 13, 'uploads/letters/1767485044_7555d2958f650f1bed3d.pdf', 'Rekap_Laporan_Disiplin_Hakim_Januari_2026.pdf', 'application/pdf', 133271, '2026-01-04 08:04:04'),
(5, 14, 'uploads/letters/1767497989_45517ca9c10fa19c6f30.pdf', 'Rekap_Laporan_Disiplin_Hakim_Januari_2026 (1).pdf', 'application/pdf', 134332, '2026-01-04 11:39:49'),
(6, 16, 'uploads/letters/1767658663_4aee14e4333f9ee0f0a4.pdf', 'Surat_Keterangan_Tidak_Mampu (1).pdf', 'application/pdf', 137516, '2026-01-06 08:17:43'),
(7, 26, 'uploads/letters/1779069328_0cf16def21ef8aa3e743.docx', 'Template_Keterangan_Usaha (11).docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 139709, '2026-05-18 09:55:28'),
(8, 26, 'uploads/letters/1779069328_ba94a75799968a3304bc.pdf', 'Pengesahan Skripsi_TA 220204622006 Muhammad Alif Qadri.pdf', 'application/pdf', 883827, '2026-05-18 09:55:28'),
(10, 28, 'uploads/letters/1779702399_290e13071c8830cc623d.jpg', 'ridho-tarian-1.jpg', 'image/jpeg', 223923, '2026-05-25 17:46:39'),
(11, 28, 'uploads/letters/1779702399_a4f948b8b047a6239818.docx', 'Template_Keterangan_Domisili (2).docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 139658, '2026-05-25 17:46:39'),
(12, 31, 'uploads/letters/1779763681_23ab07dd51103ee8873c.docx', 'Template_Keterangan_Tidak_Mampu (11).docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 139749, '2026-05-26 10:48:01');

-- --------------------------------------------------------

--
-- Table structure for table `letter_replies`
--

CREATE TABLE `letter_replies` (
  `id` bigint UNSIGNED NOT NULL,
  `letter_id` bigint UNSIGNED NOT NULL,
  `staff_id` bigint UNSIGNED NOT NULL,
  `reply_text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `letter_replies`
--

INSERT INTO `letter_replies` (`id`, `letter_id`, `staff_id`, `reply_text`, `created_at`) VALUES
(4, 1, 2, 'OKE', '2026-01-01 11:37:14'),
(5, 1, 2, '1', '2026-01-02 18:25:55'),
(6, 1, 5, 'YA', '2026-01-04 07:39:40'),
(7, 14, 2, 'oke', '2026-01-04 11:44:03'),
(16, 24, 2, 'OMKE GAS', '2026-05-07 08:16:39'),
(17, 24, 2, 'TES', '2026-05-07 08:17:28'),
(19, 21, 2, 'SDA', '2026-05-10 20:37:15'),
(20, 26, 2, 'OKE', '2026-05-18 10:10:01'),
(21, 26, 20, 'oke', '2026-05-18 10:20:58'),
(22, 26, 20, 'ini', '2026-05-18 10:21:10'),
(23, 28, 2, 'OKE', '2026-05-25 17:49:39'),
(25, 31, 2, 'ini suratnya', '2026-05-26 10:48:53');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-01-02-101717', 'App\\Database\\Migrations\\CreatePerangkatDesaTable', 'default', 'App', 1767349148, 1),
(2, '2026-01-03-000000', 'App\\Database\\Migrations\\AddKodeUnikToLettersTable', 'default', 'App', 1767484983, 2),
(3, '2026-01-04-000000', 'App\\Database\\Migrations\\ChangeTipeSuratToEnum', 'default', 'App', 1767589677, 3),
(4, '2026-01-06-000000', 'App\\Database\\Migrations\\CreateEmailQueueTable', 'default', 'App', 1767875431, 4),
(5, '2026-01-12-025949', 'App\\Database\\Migrations\\AddJenisKelaminToUserProfiles', 'default', 'App', 1768187091, 5),
(6, '2026-01-14-035118', 'App\\Database\\Migrations\\DropProjectTables', 'default', 'App', 1768362702, 6),
(7, '2026-01-14-055608', 'App\\Database\\Migrations\\DropPopulationColumnsFromDesaProfile', 'default', 'App', 1768370187, 7),
(8, '2026-01-14-060043', 'App\\Database\\Migrations\\RefactorDesaProfileSchema', 'default', 'App', 1768370646, 8),
(9, '2026-01-14-060342', 'App\\Database\\Migrations\\RestructureDesaProfile', 'default', 'App', 1768370646, 8),
(10, '2026-01-14-062016', 'App\\Database\\Migrations\\AddVisiMisiToDesaProfile', 'default', 'App', 1768371637, 9),
(11, '2026-01-14-062617', 'App\\Database\\Migrations\\CreateGeografiDesaTable', 'default', 'App', 1768371997, 10),
(12, '2026-01-14-113838', 'App\\Database\\Migrations\\CreateInventarisDesaTable', 'default', 'App', 1768390746, 11),
(13, '2026-01-14-131346', 'App\\Database\\Migrations\\CreatePengumumanTable', 'default', 'App', 1768396469, 12),
(14, '2026-01-14-141316', 'App\\Database\\Migrations\\CreatePengaduanTable', 'default', 'App', 1768400025, 13),
(15, '2026-05-07-000000', 'App\\Database\\Migrations\\UpdateLetterStatusEnum', 'default', 'App', 1778109526, 14),
(16, '2026-05-07-010000', 'App\\Database\\Migrations\\CreateUmkmTable', 'default', 'App', 1778115439, 15),
(17, '2026-05-07-010001', 'App\\Database\\Migrations\\CreateUmkmEcommerceTable', 'default', 'App', 1778115439, 15),
(18, '2026-05-07-010002', 'App\\Database\\Migrations\\CreateUmkmProdukTable', 'default', 'App', 1778115439, 15),
(19, '2026-05-07-010003', 'App\\Database\\Migrations\\CreateUmkmProdukGambarTable', 'default', 'App', 1778115439, 15),
(20, '2026-05-07-010004', 'App\\Database\\Migrations\\CreatePariwisataTable', 'default', 'App', 1778115439, 15),
(21, '2026-05-07-010005', 'App\\Database\\Migrations\\CreatePariwisataGambarTable', 'default', 'App', 1778115439, 15),
(22, '2026-05-07-010006', 'App\\Database\\Migrations\\AddRelatedUmkmIdToNotifications', 'default', 'App', 1778115439, 15),
(23, '2026-05-08-010007', 'App\\Database\\Migrations\\AddRelatedPengaduanIdToNotifications', 'default', 'App', 1778244354, 16),
(24, '2026-05-12-000001', 'App\\Database\\Migrations\\AddFotoTokoToUmkm', 'default', 'App', 1778561685, 17),
(25, '2026-05-18-000001', 'App\\Database\\Migrations\\AddLastSeenAtToUsers', 'default', 'App', 1779062457, 18),
(26, '2026-05-18-000002', 'App\\Database\\Migrations\\AddJenisKelaminToUserProfiles', 'default', 'App', 1779077466, 19),
(27, '2026-05-22-000001', 'App\\Database\\Migrations\\AddKontakToDesaProfile', 'default', 'App', 1779447090, 20),
(28, '2026-05-25-210000', 'App\\Database\\Migrations\\AddSecurityQuestionToUsers', 'default', 'App', 1779715305, 21);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_waktu` datetime NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isi` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `judul`, `tanggal_waktu`, `thumbnail`, `isi`, `created_by`, `created_at`, `updated_at`) VALUES
(10, 'Penyaluran Bantuan Bibit Tanaman untuk Warga', '2026-01-03 11:00:00', 'uploads/news/1779534438_1a5603955508351cebf0.webp', '<p><strong>Dummy Information</strong></p><p>Pemerintah Desa Bontomarannu melakukan penyaluran bantuan bibit tanaman kepada masyarakat sebagai bentuk dukungan terhadap ketahanan pangan dan penghijauan lingkungan desa. Bibit yang dibagikan terdiri dari beberapa jenis tanaman produktif dan tanaman pelindung yang dapat ditanam di pekarangan rumah warga.</p><p>Program ini mendapat respons positif dari masyarakat karena dinilai dapat membantu meningkatkan hasil pertanian sekaligus memperindah lingkungan sekitar. Pemerintah desa juga mengimbau warga agar bersama-sama menjaga dan merawat tanaman yang telah diberikan agar manfaatnya dapat dirasakan dalam jangka panjang.</p><p><em>Informasi ini hanya bersifat dummy dan digunakan untuk kebutuhan contoh konten website.</em></p>', 2, '2026-01-03 11:01:02', '2026-05-23 19:07:18'),
(11, 'Sosialisasi Kebersihan Lingkungan dan Pengelolaan Sampah', '2026-01-03 11:01:00', 'uploads/news/1779534115_c159755d9ae19ae036ae.webp', '<p><strong>Dummy Information</strong></p><p><strong><em>Desa Bontomarannu mengadakan kegiatan sosialisasi kebersihan lingkungan yang diikuti oleh masyarakat dari berbagai dusun. Kegiatan ini bertujuan meningkatkan kesadaran warga mengenai pentingnya menjaga kebersihan lingkungan serta pengelolaan sampah rumah tangga secara mandiri.</em></strong></p><p><strong><em>Dalam kegiatan tersebut, pemerintah desa juga mengajak masyarakat untuk mulai memilah sampah organik dan non-organik demi menciptakan lingkungan yang lebih sehat dan nyaman. Program kerja bakti bulanan juga direncanakan akan kembali diaktifkan secara rutin.</em></strong></p><p><em>Konten ini hanyalah dummy dan bukan informasi resmi.</em></p>', 2, '2026-01-03 11:01:27', '2026-05-23 19:01:55'),
(12, 'Pelatihan UMKM untuk Warga Desa Bontomarannu', '2026-01-03 11:01:00', 'uploads/news/1779534249_64cf7ac9ea7309570ed0.webp', '<p><strong>Dummy Information</strong></p><p>Pemerintah Desa Bontomarannu bersama kelompok pemuda desa mengadakan pelatihan pengembangan UMKM bagi masyarakat. Pelatihan ini membahas cara pemasaran produk lokal melalui media sosial serta strategi meningkatkan kualitas produk rumahan.</p><p>Kegiatan tersebut mendapat antusias tinggi dari warga karena dinilai mampu membantu meningkatkan perekonomian masyarakat desa. Beberapa peserta juga diberikan simulasi pembuatan konten promosi sederhana agar produk lokal lebih mudah dikenal masyarakat luas.</p><p><em>Informasi ini hanya digunakan sebagai data dummy atau contoh tampilan.</em></p>', 2, '2026-01-03 11:01:42', '2026-05-23 19:04:09'),
(13, 'Persiapan Festival Budaya dan Pentas Seni Desa', '2026-01-03 11:01:00', 'uploads/news/1779534312_da927b61deb9e0194ae0.webp', '<p><strong>Dummy Information</strong></p><p>Dalam rangka mempererat hubungan antarwarga, Desa Bontomarannu dikabarkan tengah mempersiapkan kegiatan festival budaya dan pentas seni tingkat desa. Acara ini direncanakan melibatkan pelajar, karang taruna, serta masyarakat umum dari berbagai dusun.</p><p>Berbagai penampilan seperti tari tradisional, musik daerah, dan pameran hasil kerajinan lokal akan menjadi bagian dari kegiatan tersebut. Pemerintah desa berharap kegiatan ini dapat menjadi wadah hiburan sekaligus pelestarian budaya lokal di tengah perkembangan zaman modern.</p><p><em>Tulisan ini hanya bersifat dummy untuk kebutuhan desain atau pengembangan website.</em></p>', 2, '2026-01-03 11:02:00', '2026-05-23 19:05:13'),
(19, 'Peningkatan Jalan Dusun di Desa Bontomarannu', '2026-05-18 20:55:00', 'uploads/news/1779534070_b4f92634dfc9896c5942.webp', '<p>Masyarakat Desa Bonto Marannu, Kecamatan Ulu Ere, Kabupaten Bantaeng, kembali melaksanakan kegiatan gotong royong bersama sebagai bentuk kepedulian terhadap kebersihan lingkungan desa. Kegiatan tersebut diikuti oleh aparat desa, pemuda, serta masyarakat dari berbagai dusun yang secara bersama-sama membersihkan area jalan desa, saluran air, dan fasilitas umum.</p><p>Kegiatan gotong royong ini dilaksanakan sejak pagi hari dan berlangsung dengan penuh semangat kebersamaan. Selain membersihkan lingkungan, warga juga melakukan penataan beberapa titik yang sebelumnya dipenuhi rumput liar agar terlihat lebih rapi dan nyaman. Pemerintah desa menyampaikan bahwa kegiatan seperti ini akan terus dilaksanakan secara rutin guna menjaga kebersihan dan kesehatan lingkungan masyarakat.</p><p>Kepala Desa Bonto Marannu mengungkapkan bahwa partisipasi masyarakat dalam kegiatan sosial menjadi salah satu kekuatan utama desa dalam menciptakan lingkungan yang aman dan nyaman. Menurutnya, budaya gotong royong yang masih terjaga hingga saat ini merupakan nilai positif yang harus terus dipertahankan oleh seluruh masyarakat desa, khususnya generasi muda.</p><p>Selain mempererat hubungan antarwarga, kegiatan tersebut juga menjadi sarana meningkatkan kesadaran masyarakat akan pentingnya menjaga kebersihan lingkungan. Warga berharap kegiatan gotong royong tidak hanya dilakukan pada momen tertentu saja, tetapi dapat menjadi kebiasaan bersama demi menciptakan desa yang bersih dan sehat.</p><p>Melalui kegiatan ini, Pemerintah Desa Bonto Marannu berharap semangat kebersamaan masyarakat dapat terus tumbuh dan menjadi contoh positif dalam mendukung pembangunan desa. Dengan lingkungan yang bersih dan masyarakat yang kompak, Desa Bonto Marannu diharapkan mampu terus berkembang menjadi desa yang maju, nyaman, dan sejahtera.</p>', 2, '2026-05-18 20:55:49', '2026-05-23 19:01:10');

-- --------------------------------------------------------

--
-- Table structure for table `news_media`
--

CREATE TABLE `news_media` (
  `id` bigint UNSIGNED NOT NULL,
  `news_id` bigint UNSIGNED NOT NULL,
  `media_type` enum('foto','video_link') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'foto',
  `media_path` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_media`
--

INSERT INTO `news_media` (`id`, `news_id`, `media_type`, `media_path`, `created_at`) VALUES
(17, 10, 'foto', 'uploads/news/1767409262_c16f5d3be3752cb20139.jpg', '2026-01-03 11:01:02'),
(18, 10, 'foto', 'uploads/news/1767409262_2959389618a46da2c79c.jpg', '2026-01-03 11:01:02'),
(24, 13, 'foto', 'uploads/news/1767409320_d1f89e4e303395672b5e.jpg', '2026-01-03 11:02:00'),
(27, 13, 'foto', 'uploads/news/1779280254_f4c19b34b5a3bd2885ae.webp', '2026-05-20 20:30:54'),
(32, 19, 'foto', 'uploads/news/1779534044_b93e959d85e838906c98.webp', '2026-05-23 19:00:44'),
(33, 19, 'foto', 'uploads/news/1779534044_01864be07a2f3423c919.webp', '2026-05-23 19:00:44'),
(34, 19, 'foto', 'uploads/news/1779534044_94c24fb0154c5d77487b.webp', '2026-05-23 19:00:44'),
(35, 11, 'foto', 'uploads/news/1779534154_a49425d578c497c965b2.webp', '2026-05-23 19:02:34'),
(36, 12, 'foto', 'uploads/news/1779534249_2a9cd3e3638c5d9cc84f.webp', '2026-05-23 19:04:09');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `related_letter_id` bigint UNSIGNED DEFAULT NULL,
  `related_reply_id` bigint UNSIGNED DEFAULT NULL,
  `related_umkm_id` int UNSIGNED DEFAULT NULL,
  `related_pengaduan_id` int UNSIGNED DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `read_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `related_letter_id`, `related_reply_id`, `related_umkm_id`, `related_pengaduan_id`, `is_read`, `created_at`, `read_at`) VALUES
(24, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: TES KODE', 13, NULL, NULL, NULL, 0, '2026-01-04 00:04:04', NULL),
(27, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: surat bansos', 14, NULL, NULL, NULL, 0, '2026-01-04 03:39:49', NULL),
(31, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: OKE', 15, NULL, NULL, NULL, 0, '2026-01-05 05:24:59', NULL),
(34, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: 1212', 16, NULL, NULL, NULL, 0, '2026-01-06 00:17:43', NULL),
(37, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: TES 12212', 17, NULL, NULL, NULL, 0, '2026-01-06 01:27:27', NULL),
(40, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: tes 1212', 18, NULL, NULL, NULL, 0, '2026-01-06 01:52:47', NULL),
(43, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: WEQA', 19, NULL, NULL, NULL, 0, '2026-01-06 02:01:59', NULL),
(46, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: TES NOTIF', 21, NULL, NULL, NULL, 0, '2026-01-08 12:18:58', NULL),
(49, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: TES NOTIF', 22, NULL, NULL, NULL, 0, '2026-01-08 12:45:31', NULL),
(54, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari Warga Desa Padang Loang', 24, NULL, NULL, NULL, 0, '2026-01-08 13:02:28', NULL),
(68, 5, 'new_umkm', 'Pengajuan UMKM Baru', 'Warga Desa Padang Loang mengajukan toko UMKM: \"TES 1\"', NULL, NULL, 2, NULL, 0, '2026-05-07 01:02:45', NULL),
(70, 5, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Muhammad Alif Qadri - Lahirnya Penguasa Sawit', NULL, NULL, NULL, 5, 0, '2026-05-08 12:48:38', NULL),
(72, 5, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari TES122 - Kerja Bakti Bersama', NULL, NULL, NULL, 6, 0, '2026-05-08 12:55:22', NULL),
(76, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari Warga Desa Padang Loang', 25, NULL, NULL, NULL, 0, '2026-05-10 12:36:57', NULL),
(80, 5, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari TES - GHHJKHJK', NULL, NULL, NULL, 7, 0, '2026-05-11 05:49:08', NULL),
(82, 5, 'new_umkm', 'Pengajuan UMKM Baru', 'Warga Desa Padang Loang mengajukan toko UMKM: \"TES 2\"', NULL, NULL, 3, NULL, 0, '2026-05-12 13:28:18', NULL),
(88, 5, 'new_umkm', 'Pengajuan UMKM Baru', 'Warga Desa Padang Loang mengajukan toko UMKM: \"ASDASD\"', NULL, NULL, 6, NULL, 0, '2026-05-12 13:47:44', NULL),
(90, 5, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Warga Desa Padang Loang - GHHJKHJK', NULL, NULL, NULL, 8, 0, '2026-05-13 01:25:26', NULL),
(92, 5, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Warga Desa Padang Loang - DAASD', NULL, NULL, NULL, 9, 0, '2026-05-13 01:25:33', NULL),
(95, 5, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Warga Desa Padang Loang - OI', NULL, NULL, NULL, 10, 0, '2026-05-18 01:23:02', NULL),
(96, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari Warga Desa Padang Loang', 26, NULL, NULL, NULL, 0, '2026-05-18 01:55:28', NULL),
(97, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari Warga Desa Padang Loang', 26, NULL, NULL, NULL, 0, '2026-05-18 01:55:28', NULL),
(98, 3, 'letter_read', 'Surat Anda telah dibaca', 'Surat Anda: TES SURAT telah dibaca oleh Staf Bahlil', 26, NULL, NULL, NULL, 0, '2026-05-18 01:55:59', NULL),
(99, 3, 'letter_accepted', 'Surat Anda diterima', 'Surat Anda: TES SURAT telah diterima oleh Staf Bahlil', 26, NULL, NULL, NULL, 0, '2026-05-18 02:10:01', NULL),
(100, 3, 'reply', 'Surat Anda dibalas', 'Balasan baru dari staff untuk surat: TES SURAT', 26, 21, NULL, NULL, 0, '2026-05-18 02:20:58', NULL),
(101, 3, 'reply', 'Surat Anda dibalas', 'Balasan baru dari staff untuk surat: TES SURAT', 26, 22, NULL, NULL, 0, '2026-05-18 02:21:10', NULL),
(102, 2, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Warga Desa Padang Loang - SADSA', NULL, NULL, NULL, 23, 0, '2026-05-18 11:02:36', NULL),
(103, 5, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Warga Desa Padang Loang - SADSA', NULL, NULL, NULL, 23, 0, '2026-05-18 11:02:36', NULL),
(104, 20, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Warga Desa Padang Loang - SADSA', NULL, NULL, NULL, 23, 0, '2026-05-18 11:02:36', NULL),
(108, 2, 'new_umkm', 'Pengajuan UMKM Baru', 'Warga Desa Padang Loang mengajukan toko UMKM: \"TOKO AMANAH\"', NULL, NULL, 7, NULL, 1, '2026-05-19 00:36:10', '2026-05-19 00:39:56'),
(109, 5, 'new_umkm', 'Pengajuan UMKM Baru', 'Warga Desa Padang Loang mengajukan toko UMKM: \"TOKO AMANAH\"', NULL, NULL, 7, NULL, 0, '2026-05-19 00:36:10', NULL),
(110, 20, 'new_umkm', 'Pengajuan UMKM Baru', 'Warga Desa Padang Loang mengajukan toko UMKM: \"TOKO AMANAH\"', NULL, NULL, 7, NULL, 0, '2026-05-19 00:36:10', NULL),
(111, 3, 'umkm_approved', 'UMKM Anda Disetujui', 'Toko \"TOKO AMANAH\" telah disetujui dan kini tampil di halaman publik.', NULL, NULL, 7, NULL, 0, '2026-05-19 01:25:39', NULL),
(112, 2, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Muhammad Alif Qadri - Lahirnya Penguasa Sawit', NULL, NULL, NULL, 24, 0, '2026-05-25 05:06:08', NULL),
(113, 5, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Muhammad Alif Qadri - Lahirnya Penguasa Sawit', NULL, NULL, NULL, 24, 0, '2026-05-25 05:06:08', NULL),
(114, 20, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Muhammad Alif Qadri - Lahirnya Penguasa Sawit', NULL, NULL, NULL, 24, 0, '2026-05-25 05:06:08', NULL),
(115, 2, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Warga Desa Padang Loang - SDAAS', NULL, NULL, NULL, 25, 0, '2026-05-25 05:07:47', NULL),
(116, 5, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Warga Desa Padang Loang - SDAAS', NULL, NULL, NULL, 25, 0, '2026-05-25 05:07:47', NULL),
(117, 20, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Warga Desa Padang Loang - SDAAS', NULL, NULL, NULL, 25, 0, '2026-05-25 05:07:47', NULL),
(118, 2, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Warga Desa Padang Loang - dasd', NULL, NULL, NULL, 26, 0, '2026-05-25 05:10:39', NULL),
(119, 5, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Warga Desa Padang Loang - dasd', NULL, NULL, NULL, 26, 0, '2026-05-25 05:10:39', NULL),
(120, 20, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Warga Desa Padang Loang - dasd', NULL, NULL, NULL, 26, 0, '2026-05-25 05:10:39', NULL),
(121, 2, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari dff - sdfffffff', NULL, NULL, NULL, 27, 0, '2026-05-25 05:12:01', NULL),
(122, 5, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari dff - sdfffffff', NULL, NULL, NULL, 27, 0, '2026-05-25 05:12:01', NULL),
(123, 20, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari dff - sdfffffff', NULL, NULL, NULL, 27, 0, '2026-05-25 05:12:01', NULL),
(124, 2, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Muhammad Alif Qadri - wrwe', NULL, NULL, NULL, 28, 0, '2026-05-25 05:12:13', NULL),
(125, 5, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Muhammad Alif Qadri - wrwe', NULL, NULL, NULL, 28, 0, '2026-05-25 05:12:13', NULL),
(126, 20, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Muhammad Alif Qadri - wrwe', NULL, NULL, NULL, 28, 0, '2026-05-25 05:12:13', NULL),
(127, 2, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Warga Desa Padang Loang - afasdfsf', NULL, NULL, NULL, 29, 0, '2026-05-25 06:13:37', NULL),
(128, 5, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Warga Desa Padang Loang - afasdfsf', NULL, NULL, NULL, 29, 0, '2026-05-25 06:13:37', NULL),
(129, 20, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Warga Desa Padang Loang - afasdfsf', NULL, NULL, NULL, 29, 0, '2026-05-25 06:13:37', NULL),
(130, 2, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Muhammad Alif Qadri - SFDSFSDF', NULL, NULL, NULL, 30, 0, '2026-05-25 06:16:08', NULL),
(131, 5, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Muhammad Alif Qadri - SFDSFSDF', NULL, NULL, NULL, 30, 0, '2026-05-25 06:16:08', NULL),
(132, 20, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Muhammad Alif Qadri - SFDSFSDF', NULL, NULL, NULL, 30, 0, '2026-05-25 06:16:08', NULL),
(133, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari Warga Desa Padang Loang', 28, NULL, NULL, NULL, 0, '2026-05-25 09:46:39', NULL),
(134, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari Warga Desa Padang Loang', 28, NULL, NULL, NULL, 0, '2026-05-25 09:46:39', NULL),
(135, 20, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari Warga Desa Padang Loang', 28, NULL, NULL, NULL, 0, '2026-05-25 09:46:39', NULL),
(136, 3, 'letter_read', 'Surat Anda telah dibaca', 'Surat Anda: TES NOTIF telah dibaca oleh Staf Bahlil', 28, NULL, NULL, NULL, 1, '2026-05-25 09:48:15', '2026-05-26 02:27:47'),
(137, 3, 'letter_accepted', 'Surat Anda diterima', 'Surat Anda: TES NOTIF telah diterima oleh Staf Bahlil', 28, NULL, NULL, NULL, 1, '2026-05-25 09:49:39', '2026-05-26 02:22:24'),
(138, 2, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari TES - DFGFDGFD', NULL, NULL, NULL, 31, 0, '2026-05-25 09:51:36', NULL),
(139, 5, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari TES - DFGFDGFD', NULL, NULL, NULL, 31, 0, '2026-05-25 09:51:36', NULL),
(140, 20, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari TES - DFGFDGFD', NULL, NULL, NULL, 31, 0, '2026-05-25 09:51:36', NULL),
(141, 2, 'new_umkm', 'Pengajuan UMKM Baru', 'Warga Desa Padang Loang mengajukan toko UMKM: \"Toko Sumber Jaya\"', NULL, NULL, 8, NULL, 0, '2026-05-25 10:04:52', NULL),
(142, 5, 'new_umkm', 'Pengajuan UMKM Baru', 'Warga Desa Padang Loang mengajukan toko UMKM: \"Toko Sumber Jaya\"', NULL, NULL, 8, NULL, 0, '2026-05-25 10:04:52', NULL),
(143, 20, 'new_umkm', 'Pengajuan UMKM Baru', 'Warga Desa Padang Loang mengajukan toko UMKM: \"Toko Sumber Jaya\"', NULL, NULL, 8, NULL, 0, '2026-05-25 10:04:52', NULL),
(144, 1, 'new_registration', 'Akun Baru Terdaftar', 'Pengguna baru \"Expect\" (alifqadry01@gmail.com) telah berhasil mendaftar dan memverifikasi akunnya.', NULL, NULL, NULL, NULL, 1, '2026-05-25 10:30:14', '2026-05-25 10:34:09'),
(145, 1, 'new_registration', 'Akun Baru Terdaftar', 'Pengguna baru \"Expect\" (alifqadry01@gmail.com) telah berhasil mendaftar dan memverifikasi akunnya.', NULL, NULL, NULL, NULL, 1, '2026-05-25 10:32:05', '2026-05-25 10:34:10'),
(146, 2, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Warga Desa Padang Loang - sad dasd sa sada sd asd', NULL, NULL, NULL, 32, 1, '2026-05-25 10:41:20', '2026-05-26 02:27:27'),
(147, 5, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Warga Desa Padang Loang - sad dasd sa sada sd asd', NULL, NULL, NULL, 32, 0, '2026-05-25 10:41:20', NULL),
(148, 20, 'new_pengaduan', 'Pengaduan Baru Masuk', 'Pengaduan baru dari Warga Desa Padang Loang - sad dasd sa sada sd asd', NULL, NULL, NULL, 32, 0, '2026-05-25 10:41:20', NULL),
(155, 3, 'reply', 'Surat Anda dibalas', 'Balasan baru dari Bahlil Lahadalia untuk surat: TES NOTIF', 28, NULL, NULL, NULL, 1, '2026-05-26 02:47:07', '2026-05-26 02:47:20'),
(156, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari Natalius Pigai', 31, NULL, NULL, NULL, 0, '2026-05-26 02:48:01', NULL),
(157, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari Natalius Pigai', 31, NULL, NULL, NULL, 0, '2026-05-26 02:48:01', NULL),
(158, 20, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari Natalius Pigai', 31, NULL, NULL, NULL, 0, '2026-05-26 02:48:01', NULL),
(159, 3, 'letter_read', 'Surat Anda telah dibaca', 'Surat Anda: SURAT TIDAK MAMPU telah dibaca oleh Bahlil Lahadalia', 31, NULL, NULL, NULL, 0, '2026-05-26 02:48:07', NULL),
(160, 3, 'letter_accepted', 'Surat Anda diterima', 'Surat Anda: SURAT TIDAK MAMPU telah diterima oleh Bahlil Lahadalia', 31, NULL, NULL, NULL, 0, '2026-05-26 02:48:40', NULL),
(161, 3, 'reply', 'Surat Anda dibalas', 'Balasan baru dari Bahlil Lahadalia untuk surat: SURAT TIDAK MAMPU', 31, 25, NULL, NULL, 0, '2026-05-26 02:48:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pariwisata`
--

CREATE TABLE `pariwisata` (
  `id` int UNSIGNED NOT NULL,
  `nama_tempat` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `alamat` text COLLATE utf8mb4_general_ci,
  `maps_embed_url` text COLLATE utf8mb4_general_ci,
  `thumbnail` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pariwisata`
--

INSERT INTO `pariwisata` (`id`, `nama_tempat`, `deskripsi`, `alamat`, `maps_embed_url`, `thumbnail`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Kebun Strawberry Uluere', 'Wisata kebun strawberry di daerah dataran tinggi dengan udara sejuk.', 'SDADSAD', 'https://maps.google.com/maps?q=-5.2576281,119.5000431&z=15&output=embed', 'uploads/pariwisata/1779595212_d926f9c82abb00e982f5.webp', 2, '2026-05-11 10:56:56', '2026-05-24 04:00:12'),
(2, 'Hutan Pinus Campaga', 'Area hutan pinus yang sering dijadikan lokasi camping dan foto aesthetic.', 'ASDASDAS', '', 'uploads/pariwisata/1779595146_cec877221030b0915cf3.webp', 2, '2026-05-20 14:37:07', '2026-05-24 03:59:06'),
(3, 'Bukit Loka', 'Spot wisata alam dengan pemandangan perbukitan dan area camping yang sering dikunjungi anak muda.', 'DASDAS', '', 'uploads/pariwisata/1779595032_6bb59184c98d328c029c.webp', 2, '2026-05-20 14:37:15', '2026-05-24 03:57:12'),
(4, 'Air Terjun Bissappu', 'Air terjun populer dengan suasana alam hijau dan udara dingin khas pegunungan.', 'ADASDASD', '', 'uploads/pariwisata/1779594960_da4469b1b1e3aa34c843.webp', 2, '2026-05-20 14:37:31', '2026-05-24 03:56:00'),
(5, 'Pantai Seruni', 'Ikon wisata Bantaeng dengan area taman, jalur pejalan kaki, dan pemandangan laut yang bagus buat santai sore.', 'ASDAS', '', 'uploads/pariwisata/1779594923_90ee4d25996140375d77.webp', 2, '2026-05-20 14:37:43', '2026-05-25 04:17:04');

-- --------------------------------------------------------

--
-- Table structure for table `pariwisata_gambar`
--

CREATE TABLE `pariwisata_gambar` (
  `id` int UNSIGNED NOT NULL,
  `pariwisata_id` int UNSIGNED NOT NULL,
  `gambar_path` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pariwisata_gambar`
--

INSERT INTO `pariwisata_gambar` (`id`, `pariwisata_id`, `gambar_path`, `created_at`) VALUES
(3, 1, 'uploads/pariwisata/1779155333_43e9d4d726fb38bbb0d0.webp', '2026-05-19 01:48:54'),
(6, 5, 'uploads/pariwisata/1779594923_30382efb39916a33743c.webp', '2026-05-24 03:55:24'),
(7, 2, 'uploads/pariwisata/1779595146_0412ee3d202079380c37.webp', '2026-05-24 03:59:06'),
(8, 5, 'uploads/pariwisata/1779682624_bf5a4e767ca7fe7cd619.webp', '2026-05-25 04:17:04');

-- --------------------------------------------------------

--
-- Table structure for table `pengaduan`
--

CREATE TABLE `pengaduan` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kontak` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `perihal` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `isi` text COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('pending','processed') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengaduan`
--

INSERT INTO `pengaduan` (`id`, `user_id`, `nama`, `kontak`, `perihal`, `isi`, `foto`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'WANDI', '1221212', 'Tes', 'WOI TES FITUR', 'uploads/pengaduan/1768400535_0af708c6446a9db36b48.gif', 'pending', '2026-01-14 14:22:15', '2026-01-14 14:22:15'),
(2, NULL, 'akun guest', '121212', 'Tes', 'tes', NULL, 'pending', '2026-01-14 14:25:56', '2026-01-14 14:25:56'),
(3, NULL, 'Warga Desa Padang Loang', '1212', 'TES KE DUA KALINYA', '1212', NULL, 'pending', '2026-01-14 14:58:23', '2026-01-14 14:58:23'),
(4, NULL, 'Prabowo', 'alifqadry@gmail.com', 'Tes 3X KALINYA', 'Tes 3X KALINYA', 'uploads/pengaduan/1768402735_3bb34104e30538b7c525.png', 'pending', '2026-01-14 14:58:55', '2026-01-14 14:58:55'),
(5, NULL, 'Muhammad Alif Qadri', 'alifqadry@gmail.com', 'Lahirnya Penguasa Sawit', 'dasdsad', NULL, 'pending', '2026-05-08 12:48:37', '2026-05-08 12:48:37'),
(6, NULL, 'TES122', 'alifqadry@gmail.com', 'Kerja Bakti Bersama', 'vbfc', NULL, 'pending', '2026-05-08 12:55:22', '2026-05-08 12:55:22'),
(7, NULL, 'TES', 'sirlippyy14@gmail.com', 'GHHJKHJK', 'BJNKJNKL.JNL', 'uploads/pengaduan/1778478548_e7a28973513123202726.png', 'pending', '2026-05-11 05:49:08', '2026-05-11 05:49:08'),
(10, NULL, 'Warga Desa Padang Loang', 'OI', 'OI', 'OI', NULL, 'pending', '2026-05-18 01:23:01', '2026-05-18 01:23:01'),
(11, NULL, 'Test User', '0000', 'Pengaduan Test 1', 'Ini adalah pengaduan dummy untuk test limit', NULL, 'pending', '2026-05-18 10:54:56', '2026-05-18 10:54:56'),
(12, NULL, 'Test User', '0000', 'Pengaduan Test 2', 'Ini adalah pengaduan dummy untuk test limit', NULL, 'pending', '2026-05-18 10:54:56', '2026-05-18 10:54:56'),
(13, NULL, 'Test User', '0000', 'Pengaduan Test 3', 'Ini adalah pengaduan dummy untuk test limit', NULL, 'pending', '2026-05-18 10:54:56', '2026-05-18 10:54:56'),
(14, NULL, 'Test User', '0000', 'Pengaduan Test 1', 'Ini adalah pengaduan dummy untuk test limit', NULL, 'pending', '2026-05-18 10:59:06', '2026-05-18 10:59:06'),
(15, NULL, 'Test User', '0000', 'Pengaduan Test 2', 'Ini adalah pengaduan dummy untuk test limit', NULL, 'pending', '2026-05-18 10:59:06', '2026-05-18 10:59:06'),
(16, NULL, 'Test User', '0000', 'Pengaduan Test 3', 'Ini adalah pengaduan dummy untuk test limit', NULL, 'pending', '2026-05-18 10:59:06', '2026-05-18 10:59:06'),
(17, NULL, 'Test Limit Dummy 1', '1234567890', 'Pengaduan Uji Coba Limit', 'Ini adalah pengaduan otomatis untuk menguji fitur rate limit (cooldown).', NULL, 'pending', '2026-05-18 11:02:15', '2026-05-18 11:02:15'),
(18, NULL, 'Test Limit Dummy 2', '1234567890', 'Pengaduan Uji Coba Limit', 'Ini adalah pengaduan otomatis untuk menguji fitur rate limit (cooldown).', NULL, 'pending', '2026-05-18 11:02:16', '2026-05-18 11:02:16'),
(19, NULL, 'Test Limit Dummy 3', '1234567890', 'Pengaduan Uji Coba Limit', 'Ini adalah pengaduan otomatis untuk menguji fitur rate limit (cooldown).', NULL, 'pending', '2026-05-18 11:02:16', '2026-05-18 11:02:16'),
(20, NULL, 'Test Limit Dummy 1', '1234567890', 'Pengaduan Uji Coba Limit', 'Ini adalah pengaduan otomatis untuk menguji fitur rate limit (cooldown).', NULL, 'pending', '2026-05-18 11:02:19', '2026-05-18 11:02:19'),
(21, NULL, 'Test Limit Dummy 2', '1234567890', 'Pengaduan Uji Coba Limit', 'Ini adalah pengaduan otomatis untuk menguji fitur rate limit (cooldown).', NULL, 'pending', '2026-05-18 11:02:19', '2026-05-18 11:02:19'),
(22, NULL, 'Test Limit Dummy 3', '1234567890', 'Pengaduan Uji Coba Limit', 'Ini adalah pengaduan otomatis untuk menguji fitur rate limit (cooldown).', NULL, 'pending', '2026-05-18 11:02:19', '2026-05-18 11:02:19'),
(23, NULL, 'Warga Desa Padang Loang', 'SADASD', 'SADSA', 'ASDASD', NULL, 'pending', '2026-05-18 11:02:36', '2026-05-18 11:02:36'),
(24, NULL, 'Muhammad Alif Qadri', '081234567890', 'Lahirnya Penguasa Sawit', 'DSSSSSSSSS', NULL, 'pending', '2026-05-25 05:06:08', '2026-05-25 05:06:08'),
(25, NULL, 'Warga Desa Padang Loang', '081234567890', 'SDAAS', 'ASDASADSAASDFASDF', NULL, 'pending', '2026-05-25 05:07:47', '2026-05-25 05:07:47'),
(26, NULL, 'Warga Desa Padang Loang', 'dsada', 'dasd', 'asdasdadsds adasdsdasd', NULL, 'pending', '2026-05-25 05:10:39', '2026-05-25 05:10:39'),
(27, NULL, 'dff', 'fsfsf', 'sdfffffff', 'sdfffffffffffffffffffffff', NULL, 'pending', '2026-05-25 05:12:01', '2026-05-25 05:12:01'),
(28, NULL, 'Muhammad Alif Qadri', 'werw', 'wrwe', 'rwrewrwrwe', NULL, 'pending', '2026-05-25 05:12:13', '2026-05-25 05:12:13'),
(29, NULL, 'Warga Desa Padang Loang', '081234567890', 'afasdfsf', 'sfdsfsdf sdfsdfsdffsdsd', NULL, 'pending', '2026-05-25 06:13:37', '2026-05-25 06:13:37'),
(30, NULL, 'Muhammad Alif Qadri', 'DFSF', 'SFDSFSDF', 'SDFSDFSSSSSSSSSSSSSSSSSSS', 'uploads/pengaduan/1779689768_d0fdaa019fe5432b6735.jpg', 'pending', '2026-05-25 06:16:08', '2026-05-25 06:16:08'),
(32, NULL, 'Warga Desa Padang Loang', '081234567890', 'sad dasd sa sada sd asd', 'sadasdas sadasdas sadasdas', NULL, 'pending', '2026-05-25 10:41:20', '2026-05-25 10:41:20');

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` int UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `isi` text COLLATE utf8mb4_general_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`id`, `judul`, `isi`, `thumbnail`, `foto`, `created_at`, `updated_at`) VALUES
(2, 'Penyesuaian Jadwal Pelayanan Desa', 'Dummy Announcement\r\n\r\nPelayanan administrasi desa sementara mengalami penyesuaian jadwal karena adanya kegiatan internal kantor desa. Masyarakat diharapkan menyesuaikan waktu pengurusan administrasi selama pemberitahuan ini berlaku.\r\n\r\nInformasi ini hanya bersifat dummy.', 'uploads/pengumuman/1779594303_59a16119677fb9830835.webp', 'uploads/pengumuman/1768399270_a5b9bb06386bd341fcb1.jpeg', '2026-01-14 14:01:10', '2026-05-24 03:45:03'),
(7, 'Pelaksanaan Posyandu Bulanan Desa', 'Dummy Announcement\r\n\r\nKegiatan Posyandu bulanan akan kembali dilaksanakan di balai desa minggu ini. Pelayanan meliputi pemeriksaan kesehatan balita, penimbangan berat badan, dan pemberian vitamin bagi anak-anak.\r\n\r\nKonten ini hanya digunakan sebagai dummy website.', 'uploads/pengumuman/1779594262_b36ef5f4b505b10a1482.webp', 'uploads/pengumuman/1779271323_998c4dff8c5daa319cea.webp', '2026-05-20 10:02:03', '2026-05-24 03:44:22'),
(8, 'Jadwal Pembagian Bantuan Sosial', 'Dummy Announcement\r\n\r\nMasyarakat penerima bantuan sosial diharapkan hadir di kantor desa sesuai jadwal yang telah ditentukan. Warga diminta membawa identitas diri untuk proses verifikasi data agar pembagian bantuan berjalan lancar dan tertib.\r\n\r\nKonten ini hanya bersifat dummy.', 'uploads/pengumuman/1779594213_aeb8cc66bfc16806b13d.webp', 'uploads/pengumuman/1779275763_47fce967227c734b214d.webp', '2026-05-20 11:16:03', '2026-05-24 03:43:33'),
(9, 'Kerja Bakti Bersama Warga Desa', 'Dummy Announcement\r\n\r\nDiberitahukan kepada seluruh masyarakat Desa Bontomarannu bahwa kegiatan kerja bakti lingkungan akan dilaksanakan pada hari Minggu pagi di masing-masing dusun. Kegiatan ini bertujuan menjaga kebersihan lingkungan dan mempererat kerja sama antarwarga.\r\n\r\nKonten ini hanya bersifat dummy.', 'uploads/pengumuman/1779534869_601f599e0e7229f66bdd.webp', 'uploads/pengumuman/1779534869_7cc056cca1507e17e214.webp', '2026-05-20 11:16:24', '2026-05-23 11:14:29');

-- --------------------------------------------------------

--
-- Table structure for table `perangkat_desa`
--

CREATE TABLE `perangkat_desa` (
  `id` int UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kontak` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perangkat_desa`
--

INSERT INTO `perangkat_desa` (`id`, `nama`, `foto`, `jabatan`, `kontak`, `created_at`, `updated_at`) VALUES
(1, 'Alif', 'uploads/perangkat_desa/1767397678_18cf895b803a893bfe02.webp', 'Kepala Desa', '081234567890', '2026-01-02 10:21:52', '2026-01-02 23:47:58'),
(3, 'AGIL', 'uploads/perangkat_desa/1767498632_843bdebba9901730186e.webp', 'Admin Desa', '131212', '2026-01-04 03:50:33', '2026-01-10 14:11:38');

-- --------------------------------------------------------

--
-- Table structure for table `reply_attachments`
--

CREATE TABLE `reply_attachments` (
  `id` bigint UNSIGNED NOT NULL,
  `reply_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint UNSIGNED DEFAULT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reply_attachments`
--

INSERT INTO `reply_attachments` (`id`, `reply_id`, `file_path`, `original_name`, `mime_type`, `file_size`, `uploaded_at`) VALUES
(3, 7, 'uploads/replies/1767498243_2725612dadcf58f3aa5f.pdf', 'Rekap_Laporan_Disiplin_Hakim_Januari_2026 (1).pdf', 'application/pdf', 134332, '2026-01-04 11:44:03'),
(4, 16, 'uploads/replies/1778112999_74a729a29f5dee998fa3.pdf', 'Surat_Keterangan_Usaha (6).pdf', 'application/pdf', 108792, '2026-05-07 08:16:39'),
(5, 17, 'uploads/replies/1778113049_8b80507766048dc84c26.docx', 'Surat_Pernyataan_MASSIPA.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 14737, '2026-05-07 08:17:29'),
(6, 20, 'uploads/replies/1779070201_5a16615ed4b8f1b82893.docx', 'Template_Keterangan_Domisili (2).docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 139658, '2026-05-18 10:10:01'),
(7, 20, 'uploads/replies/1779070201_8d2bc01b24d33c2810f2.docx', 'Template_Undangan (6).docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 139716, '2026-05-18 10:10:01'),
(8, 20, 'uploads/replies/1779070201_43f7e95f163c4019840c.jpg', 'Logo_Bantaeng.jpg', 'image/jpeg', 994720, '2026-05-18 10:10:01'),
(9, 20, 'uploads/replies/1779070201_e2c4345f3acafc30e4ab.pdf', 'registrasi.unm.ac.id_wisuda_cetak.php_token=ec0cabd96bdc5850719596c0062782f6.pdf', 'application/pdf', 109560, '2026-05-18 10:10:01'),
(10, 22, 'uploads/replies/1779070870_737b675b0a2b9cc1e0d4.docx', '1779069328_0cf16def21ef8aa3e743.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 139709, '2026-05-18 10:21:10'),
(11, 23, 'uploads/replies/1779702579_38bc7f72d2f58158d439.jpg', 'pengumuman.jpg', 'image/jpeg', 254732, '2026-05-25 17:49:39'),
(13, 25, 'uploads/replies/1779763733_428dfd85ed1a3a3388b7.docx', 'Surat_Keterangan_Tidak_Mampu_SURAT-20260526-6D8540.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 139824, '2026-05-26 10:48:53');

-- --------------------------------------------------------

--
-- Table structure for table `umkm`
--

CREATE TABLE `umkm` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL COMMENT 'null jika dibuat langsung oleh staff',
  `nama_toko` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `alamat` text COLLATE utf8mb4_general_ci,
  `maps_embed_url` text COLLATE utf8mb4_general_ci,
  `kontak` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_toko` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `alasan_tolak` text COLLATE utf8mb4_general_ci,
  `created_by` int UNSIGNED DEFAULT NULL,
  `approved_by` int UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `umkm`
--

INSERT INTO `umkm` (`id`, `user_id`, `nama_toko`, `deskripsi`, `alamat`, `maps_embed_url`, `kontak`, `foto_toko`, `status`, `alasan_tolak`, `created_by`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 'TES', 'ASDAD', 'SDAASDAS', '', '081234567890', NULL, 'approved', NULL, 2, 2, '2026-05-07 01:00:48', '2026-05-07 01:00:48', '2026-05-07 01:00:48'),
(2, 3, 'TES 1', 'ASDASD', 'ASDA', '', '323232', 'uploads/umkm/toko/1778561809_4d9ad342b05e93cd8b1e.webp', 'approved', NULL, 3, 2, '2026-05-08 13:51:07', '2026-05-07 01:02:44', '2026-05-12 04:56:49'),
(3, 3, 'TES 2', 'dassad', 'dasasd', '', '323232', NULL, 'rejected', 'perbaiki', 3, 2, '2026-05-12 13:29:39', '2026-05-12 13:28:18', '2026-05-12 13:29:39'),
(6, 3, 'ASDASD', 'ADASD', '', 'https://maps.google.com/maps?q=-5.1593128,119.4085167&z=15&output=embed', '', NULL, 'pending', NULL, 3, NULL, NULL, '2026-05-12 13:47:44', '2026-05-12 14:01:16'),
(7, 3, 'TOKO AMANAH TOKO AMANAH TOKO AMANAH TOKO AMANAH', 'OKE GUYS\r\nSAYA CUMAN\r\nMAU TES OKE GUYS\r\n SAYA CUMANSAYA CUMANSAYA CUMANSAYA CUMANSAYA CUMANSAYA CUMANSAYA CUMANSAYA CUMANSAYA CUMANSAYA CUMANSAYA CUMANSAYA CUMANSAYA CUMANSAYA CUMANSAYA CUMANSAYA CUMANSAYA CUMANSAYA CUMANSAYA CUMANSAYA CUMAN\r\nMAU TESOKE GUYS\r\nSAYA CUMAN\r\nMAU TES', 'ADSAD', 'https://maps.google.com/maps?q=-5.1557157,119.4302116&z=15&output=embed', '081234567890', 'uploads/umkm/toko/1779150969_59d393a3f9c9b5757da6.webp', 'approved', NULL, 3, 2, '2026-05-19 01:25:39', '2026-05-19 00:36:10', '2026-05-25 03:57:11'),
(8, 3, 'Toko Sumber Jaya', 'omke gs omke gas', 'Jalan ahmad yani', '', '081234567890', 'uploads/umkm/toko/1779703492_963e02a617fa8d1b3817.webp', 'pending', NULL, 3, NULL, NULL, '2026-05-25 10:04:52', '2026-05-25 10:04:52');

-- --------------------------------------------------------

--
-- Table structure for table `umkm_ecommerce`
--

CREATE TABLE `umkm_ecommerce` (
  `id` int UNSIGNED NOT NULL,
  `umkm_id` int UNSIGNED NOT NULL,
  `platform` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'nama platform bebas, e.g. Shopee, Tokopedia',
  `url` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `umkm_ecommerce`
--

INSERT INTO `umkm_ecommerce` (`id`, `umkm_id`, `platform`, `url`, `created_at`) VALUES
(19, 7, 'Shopee', 'https://shopee.co.id/fantechstore#product_list', '2026-05-25 03:57:11'),
(20, 7, 'Tokopedia', 'https://maps.app.goo.gl/PS6K3FhEfD4PKxuJ8', '2026-05-25 03:57:11');

-- --------------------------------------------------------

--
-- Table structure for table `umkm_produk`
--

CREATE TABLE `umkm_produk` (
  `id` int UNSIGNED NOT NULL,
  `umkm_id` int UNSIGNED NOT NULL,
  `nama_produk` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `harga` decimal(15,2) DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `umkm_produk`
--

INSERT INTO `umkm_produk` (`id`, `umkm_id`, `nama_produk`, `harga`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 1, 'TES', '5000.00', 'DSADA', '2026-05-07 01:00:48', '2026-05-19 01:31:39'),
(2, 2, 'TESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTES', '5000.00', 'DASDTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTESTES', '2026-05-07 01:02:44', '2026-05-12 11:03:53'),
(4, 7, 'Keripik Pisang Bontomarannu', '5000.00', ' Keripik pisang khas Desa Bontomarannu yang dibuat dari pisang pilihan dengan cita rasa gurih dan renyah. Cocok dijadikan camilan sehari-hari maupun oleh-oleh khas desa.', '2026-05-19 00:41:29', '2026-05-23 14:12:33'),
(5, 7, 'ok', '122121.00', 'asdad', '2026-05-19 01:48:09', '2026-05-19 01:48:09'),
(6, 7, 'adasd', '0.00', 'adasd', '2026-05-19 01:48:09', '2026-05-19 01:48:09'),
(7, 7, 'AIR', '5000.00', 'R', '2026-05-21 13:06:18', '2026-05-21 13:06:18'),
(8, 7, 'AIR', '5000.00', 'SDFDF', '2026-05-21 13:06:43', '2026-05-21 13:06:43'),
(9, 7, 'AIR', '5000.00', 'SDF', '2026-05-21 13:06:44', '2026-05-21 13:06:44'),
(10, 7, '32', '232.00', '', '2026-05-25 03:57:11', '2026-05-25 03:57:11'),
(11, 7, 'dfsfs', '5000.00', '', '2026-05-25 03:57:11', '2026-05-25 03:57:11'),
(12, 7, '2323', '2323.00', '', '2026-05-25 03:57:11', '2026-05-25 03:57:11'),
(13, 8, 'Air', '1000000.00', 'oke gas', '2026-05-25 10:04:52', '2026-05-25 10:04:52');

-- --------------------------------------------------------

--
-- Table structure for table `umkm_produk_gambar`
--

CREATE TABLE `umkm_produk_gambar` (
  `id` int UNSIGNED NOT NULL,
  `produk_id` int UNSIGNED NOT NULL,
  `gambar_path` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `umkm_produk_gambar`
--

INSERT INTO `umkm_produk_gambar` (`id`, `produk_id`, `gambar_path`, `created_at`) VALUES
(1, 1, 'uploads/umkm/1778115648_6a4b2ed3e7ce05faebb3.webp', '2026-05-07 01:00:49'),
(2, 2, 'uploads/umkm/1778115764_a88c5e870c9eb221e66b.webp', '2026-05-07 01:02:45'),
(5, 4, 'uploads/umkm/1779151289_eff981756cd221a70923.webp', '2026-05-19 00:41:29'),
(6, 5, 'uploads/umkm/1779155289_f55ff031485b97d1428c.webp', '2026-05-19 01:48:09'),
(7, 6, 'uploads/umkm/1779155289_afddddb6c804b5951c65.webp', '2026-05-19 01:48:09'),
(8, 7, 'uploads/umkm/1779368778_88a7fdf53be6a52f8683.webp', '2026-05-21 13:06:18'),
(9, 8, 'uploads/umkm/1779368803_146cb1db26933366abde.webp', '2026-05-21 13:06:44'),
(10, 9, 'uploads/umkm/1779368804_b7e3513d35472fb93ec2.webp', '2026-05-21 13:06:44'),
(11, 4, 'uploads/umkm/1779544277_a4c1d287aec976e24ff4.webp', '2026-05-23 13:51:18'),
(12, 13, 'uploads/umkm/1779703492_9f74571bbadc590ba02f.webp', '2026-05-25 10:04:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `security_question` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `security_answer_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','staf','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `last_seen_at` datetime DEFAULT NULL,
  `firebase_uid` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `security_question`, `security_answer_hash`, `role`, `status`, `is_verified`, `last_seen_at`, `firebase_uid`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'anggajej181@gmail.com', '$2a$12$ab76mupW2LsWiBMzpKaL8e/PKm844v173kNHdRupbs0V3IhM2cZya', NULL, NULL, 'admin', 'aktif', 1, '2026-05-26 11:46:34', NULL, '2026-01-01 08:19:16', '2026-05-26 11:46:34'),
(2, 'staf', 'alifqadry15@gmail.com', '$2y$10$KaKFT2HhnSMkCeBy91TffepzXyiDtvlXWsvLimqn/SP9LmJdsU1PC', NULL, NULL, 'staf', 'aktif', 1, '2026-06-13 09:20:20', NULL, '2026-01-01 08:19:16', '2026-06-13 09:20:20'),
(3, 'user', 'alifqadry@gmail.com', '$2y$10$L42e110nW3V6fNwgAhCB0uMlj/bqnonCF/umWznfpkyJMNbME6p3e', 'Siapa presiden pertama yang ada di indonesia untuk saat ini?', '$2y$10$rcQ/kLbLEnTfVrDLE7glFe6HMzYxKLnQVVt5i7Kr/AjDRX3MAqRl6', 'user', 'aktif', 1, '2026-06-12 22:23:18', '2fLfPB6h5pgLSBw3axx0S8etNqx2', '2026-01-01 08:19:16', '2026-06-12 22:23:18'),
(5, 'staf2', 'admin1@padangloang.id', '$2y$10$FE5AjPwUzXIps3eh0VOKyu.g0g.UfC4z9ZUldwMCpJXyI5xf/LzBm', NULL, NULL, 'staf', 'aktif', 1, NULL, NULL, '2026-01-04 07:38:31', '2026-05-18 08:06:07'),
(15, 'alif1', 'alifqadry10@gmail.com', '$2y$10$e79X/73rp6tu6rP8TPDlgOXgwzty6vE0t4a3AXrlWI9qfTimyJkj2', NULL, NULL, 'user', 'aktif', 1, NULL, NULL, '2026-01-06 22:31:25', NULL),
(16, 'alif_cursor', 'alifcursor4@gmail.com', '$2y$10$nJV/fkUSh.uKenU83Cwi9eXmAk.7nBBWlmDD6koICL2H4KAIJpLjS', NULL, NULL, 'user', 'aktif', 1, NULL, 'VrS57QIcrQdXmsyt1Z5mVGNNaT32', '2026-01-09 09:28:54', NULL),
(17, 'sensui', 'sensui641@gmail.com', '$2y$10$jlE76MFCMLnhVZKx7ZD6WuR.H4CS5tvdCmZh7KNF4Nsttar95M6vu', NULL, NULL, 'user', 'aktif', 1, NULL, 'cxJGxCbbkBaGVygK4SioLIjynst1', '2026-01-09 12:04:01', NULL),
(18, 'massipaptamakassar', 'massipa.ptamakassar@gmail.com', '$2y$10$gIBxyEE1kn4ka8g30fDF3.MK.leeAAJlocp/kp2borUPuCS41cM5e', NULL, NULL, 'user', 'aktif', 1, NULL, 'tIE3NQnhrIVh4BhGiJ8OOM6iPjw1', '2026-01-09 12:04:38', '2026-05-18 08:07:23'),
(19, 'LIPPP', 'alifgpt12D@gmail.com', '$2y$10$wn1CSvnWZzPnlxgIlr7/seH6uLmnuRgHZ636TR.Cc9CSBICzvlyPC', NULL, NULL, 'user', 'aktif', 1, '2026-05-18 08:16:44', NULL, '2026-05-18 08:16:44', '2026-05-18 08:16:44'),
(20, 'staff2', 'alifgdsdspt12@gmail.com', '$2y$10$SoLjKmZgsSbCgWu6u7DzTOMHc8.yHhb7VEPubVvoV/rsecCiwc7hO', NULL, NULL, 'staf', 'aktif', 1, '2026-05-18 10:20:46', NULL, '2026-05-18 10:20:20', '2026-05-18 10:20:46');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `user_id` bigint UNSIGNED NOT NULL,
  `foto_profil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_lengkap` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_lahir` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agama` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`user_id`, `foto_profil`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pekerjaan`, `nik`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'uploads/profile/1779761964_466998a961bfc15adf09.webp', 'Admin Mr Bean', '', '0000-00-00', '', '', 'Administrator Sistem', NULL, 'Kantor Desa Padang Loang', '2026-01-01 08:19:16', '2026-05-26 10:19:24'),
(2, 'uploads/profile/1779761690_3d005d78a1e76a7b1fd6.webp', 'Bahlil Lahadalia', 'Maros', '1973-02-06', 'Laki-laki', 'Islam', 'Staf Pelayanan Desa', '7371095304060003', 'Kantor Desa Padang Loang', '2026-01-01 08:19:16', '2026-05-26 10:15:50'),
(3, 'uploads/profile/1779761318_bcdcd46905a2da8f949f.webp', 'Natalius Pigai', 'Bulukumba', '2026-01-05', 'Laki-laki', 'Islam', 'Petani', '7312011503980001', 'Desa Padang Loang, Kecamatan Ujung Loe', '2026-01-01 08:19:16', '2026-05-26 10:09:40'),
(5, NULL, 'STAFF 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-04 07:38:31', NULL),
(15, NULL, 'alif1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-06 22:31:25', NULL),
(16, NULL, 'Alif Cursor', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-09 09:28:54', NULL),
(17, NULL, 'sen sui', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-09 12:04:01', NULL),
(18, NULL, 'Massipa PTA Makassar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-09 12:04:38', NULL),
(19, NULL, 'LIPPP', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-18 08:16:44', NULL),
(20, NULL, 'staff', '', '0000-00-00', NULL, '', '', NULL, '', '2026-05-18 10:20:20', '2026-05-18 10:20:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `desa_profile`
--
ALTER TABLE `desa_profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_desa_updated_by` (`updated_by`);

--
-- Indexes for table `email_queue`
--
ALTER TABLE `email_queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `is_sent` (`is_sent`),
  ADD KEY `processing_token` (`processing_token`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `gallery_albums`
--
ALTER TABLE `gallery_albums`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_album_datetime` (`tanggal_waktu`),
  ADD KEY `fk_album_created_by` (`created_by`);

--
-- Indexes for table `gallery_media`
--
ALTER TABLE `gallery_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_gallery_media_album` (`album_id`);

--
-- Indexes for table `geografi_desa`
--
ALTER TABLE `geografi_desa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventaris_desa`
--
ALTER TABLE `inventaris_desa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `letters`
--
ALTER TABLE `letters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_letters_user` (`user_id`),
  ADD KEY `idx_letters_staff` (`assigned_staff_id`),
  ADD KEY `idx_letters_status` (`status`);

--
-- Indexes for table `letter_attachments`
--
ALTER TABLE `letter_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_letter_attach_letter` (`letter_id`);

--
-- Indexes for table `letter_replies`
--
ALTER TABLE `letter_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_replies_letter` (`letter_id`),
  ADD KEY `fk_reply_staff` (`staff_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_news_datetime` (`tanggal_waktu`),
  ADD KEY `fk_news_created_by` (`created_by`);

--
-- Indexes for table `news_media`
--
ALTER TABLE `news_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_news_media_news` (`news_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notif_user` (`user_id`),
  ADD KEY `idx_notif_read` (`is_read`),
  ADD KEY `idx_notif_created` (`created_at`),
  ADD KEY `fk_notif_letter` (`related_letter_id`),
  ADD KEY `fk_notif_reply` (`related_reply_id`);

--
-- Indexes for table `pariwisata`
--
ALTER TABLE `pariwisata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pariwisata_gambar`
--
ALTER TABLE `pariwisata_gambar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pariwisata_id` (`pariwisata_id`);

--
-- Indexes for table `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `perangkat_desa`
--
ALTER TABLE `perangkat_desa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reply_attachments`
--
ALTER TABLE `reply_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reply_attach_reply` (`reply_id`);

--
-- Indexes for table `umkm`
--
ALTER TABLE `umkm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `umkm_ecommerce`
--
ALTER TABLE `umkm_ecommerce`
  ADD PRIMARY KEY (`id`),
  ADD KEY `umkm_id` (`umkm_id`);

--
-- Indexes for table `umkm_produk`
--
ALTER TABLE `umkm_produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `umkm_id` (`umkm_id`);

--
-- Indexes for table `umkm_produk_gambar`
--
ALTER TABLE `umkm_produk_gambar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_users_username` (`username`),
  ADD UNIQUE KEY `uk_users_email` (`email`),
  ADD UNIQUE KEY `uk_users_firebase_uid` (`firebase_uid`),
  ADD KEY `idx_users_role` (`role`),
  ADD KEY `idx_users_status` (`status`),
  ADD KEY `idx_users_firebase_uid` (`firebase_uid`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `uk_profiles_nik` (`nik`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `desa_profile`
--
ALTER TABLE `desa_profile`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `email_queue`
--
ALTER TABLE `email_queue`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT for table `gallery_albums`
--
ALTER TABLE `gallery_albums`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `gallery_media`
--
ALTER TABLE `gallery_media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `geografi_desa`
--
ALTER TABLE `geografi_desa`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventaris_desa`
--
ALTER TABLE `inventaris_desa`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `letters`
--
ALTER TABLE `letters`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `letter_attachments`
--
ALTER TABLE `letter_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `letter_replies`
--
ALTER TABLE `letter_replies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `news_media`
--
ALTER TABLE `news_media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `pariwisata`
--
ALTER TABLE `pariwisata`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pariwisata_gambar`
--
ALTER TABLE `pariwisata_gambar`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pengaduan`
--
ALTER TABLE `pengaduan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `perangkat_desa`
--
ALTER TABLE `perangkat_desa`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `reply_attachments`
--
ALTER TABLE `reply_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `umkm`
--
ALTER TABLE `umkm`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `umkm_ecommerce`
--
ALTER TABLE `umkm_ecommerce`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `umkm_produk`
--
ALTER TABLE `umkm_produk`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `umkm_produk_gambar`
--
ALTER TABLE `umkm_produk_gambar`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `desa_profile`
--
ALTER TABLE `desa_profile`
  ADD CONSTRAINT `fk_desa_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `gallery_albums`
--
ALTER TABLE `gallery_albums`
  ADD CONSTRAINT `fk_album_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `gallery_media`
--
ALTER TABLE `gallery_media`
  ADD CONSTRAINT `fk_gallery_media_album` FOREIGN KEY (`album_id`) REFERENCES `gallery_albums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `letters`
--
ALTER TABLE `letters`
  ADD CONSTRAINT `fk_letters_staff` FOREIGN KEY (`assigned_staff_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_letters_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `letter_attachments`
--
ALTER TABLE `letter_attachments`
  ADD CONSTRAINT `fk_letter_attach_letter` FOREIGN KEY (`letter_id`) REFERENCES `letters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `letter_replies`
--
ALTER TABLE `letter_replies`
  ADD CONSTRAINT `fk_reply_letter` FOREIGN KEY (`letter_id`) REFERENCES `letters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reply_staff` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_news_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `news_media`
--
ALTER TABLE `news_media`
  ADD CONSTRAINT `fk_news_media_news` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notif_letter` FOREIGN KEY (`related_letter_id`) REFERENCES `letters` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notif_reply` FOREIGN KEY (`related_reply_id`) REFERENCES `letter_replies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reply_attachments`
--
ALTER TABLE `reply_attachments`
  ADD CONSTRAINT `fk_reply_attach_reply` FOREIGN KEY (`reply_id`) REFERENCES `letter_replies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `fk_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
