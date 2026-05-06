-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2026 at 04:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `desa_padang_loang`
--

-- --------------------------------------------------------

--
-- Table structure for table `desa_profile`
--

CREATE TABLE `desa_profile` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `visi` text DEFAULT NULL,
  `misi` text DEFAULT NULL,
  `jumlah_penduduk` int(10) UNSIGNED DEFAULT 0,
  `jumlah_kk` int(10) UNSIGNED DEFAULT 0,
  `penduduk_sementara` int(10) UNSIGNED DEFAULT 0,
  `jumlah_laki` int(10) UNSIGNED DEFAULT 0,
  `jumlah_perempuan` int(10) UNSIGNED DEFAULT 0,
  `mutasi_penduduk` int(10) UNSIGNED DEFAULT 0,
  `kontak_wa` varchar(30) DEFAULT NULL,
  `kontak_email` varchar(100) DEFAULT NULL,
  `alamat_kantor` text DEFAULT NULL,
  `maps_url` text DEFAULT NULL,
  `deskripsi_lokasi` text DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `desa_profile`
--

INSERT INTO `desa_profile` (`id`, `visi`, `misi`, `jumlah_penduduk`, `jumlah_kk`, `penduduk_sementara`, `jumlah_laki`, `jumlah_perempuan`, `mutasi_penduduk`, `kontak_wa`, `kontak_email`, `alamat_kantor`, `maps_url`, `deskripsi_lokasi`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'OKE GAS', 'OMKE GAS', 900, 1, 0, 0, 0, 0, '', '', '', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15885.693312990974!2d120.24210471207235!3d-5.503993002757931!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbc06d55f454905%3A0x8df79009fa3a8cce!2sPadang%20Loang%2C%20Kec.%20Ujung%20Loe%2C%20Kabupaten%20Bulukumba%2C%20Sulawesi%20Selatan!5e0!3m2!1sid!2sid!4v1767236370468!5m2!1sid!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', '', 2, '2026-01-01 07:48:50', '2026-01-01 10:59:47');

-- --------------------------------------------------------

--
-- Table structure for table `email_queue`
--

CREATE TABLE `email_queue` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `recipient` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `is_sent` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = pending, 2 = processing, 1 = sent',
  `processing_token` varchar(64) DEFAULT NULL,
  `processing_at` datetime DEFAULT NULL,
  `fail_count` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `last_error` text DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_queue`
--

INSERT INTO `email_queue` (`id`, `recipient`, `subject`, `body`, `is_sent`, `processing_token`, `processing_at`, `fail_count`, `last_error`, `sent_at`, `created_at`, `updated_at`) VALUES
(13, 'alifqadry14@gmail.com', 'Notifikasi - Surat Baru Masuk', '\n        <!DOCTYPE html>\n        <html>\n        <head>\n            <meta charset=\'UTF-8\'>\n            <style>\n                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }\n                .container { max-width: 600px; margin: 0 auto; padding: 20px; }\n                .header { background-color: #007bff; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }\n                .content { background-color: #f8f9fa; padding: 30px; border-radius: 0 0 5px 5px; }\n                .message-box { background-color: white; border-left: 4px solid #007bff; padding: 20px; margin: 20px 0; border-radius: 5px; }\n                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }\n            </style>\n        </head>\n        <body>\n            <div class=\'container\'>\n                <div class=\'header\'>\n                    <h2>Notifikasi</h2>\n                </div>\n                <div class=\'content\'>\n                    <p>Halo <strong>staf</strong>,</p>\n                    \n                    <div class=\'message-box\'>\n                        <h3 style=\'margin-top: 0; color: #007bff;\'>Surat Baru Masuk</h3>\n                        <p style=\'margin-bottom: 0;\'>Surat baru dari user: TES NOTIF 1</p>\n                    </div>\n                    \n                    \n            <div style=\'background-color: white; border: 1px solid #dee2e6; padding: 15px; margin: 20px 0; border-radius: 5px;\'>\n                <h4 style=\'margin-top: 0; margin-bottom: 15px; color: #007bff;\'>Detail Surat</h4>\n                <p style=\'margin: 8px 0;\'><strong>Judul Surat:</strong> TES NOTIF 1</p>\n                <p style=\'margin: 8px 0;\'><strong>Tipe Surat:</strong> Undangan</p>\n            </div>\n                    \n                    \n            <div style=\'text-align: center; margin: 30px 0;\'>\n                <a href=\'http://localhost:8080/staff/surat/23\' style=\'display: inline-block; background-color: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;\'>Lihat Detail</a>\n            </div>\n                    \n                    <p style=\'margin-top: 20px; color: #666; font-size: 14px;\'>Email ini dikirim secara otomatis dari sistem Website Padang Loang.</p>\n                </div>\n                <div class=\'footer\'>\n                    <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>\n                    <p>&copy; 2026 Website Padang Loang. All rights reserved.</p>\n                </div>\n            </div>\n        </body>\n        </html>', 1, NULL, '2026-01-08 12:56:40', 0, NULL, '2026-01-08 12:56:47', '2026-01-08 12:56:39', '2026-01-08 12:56:47'),
(14, 'admin1@padangloang.id', 'Notifikasi - Surat Baru Masuk', '\n        <!DOCTYPE html>\n        <html>\n        <head>\n            <meta charset=\'UTF-8\'>\n            <style>\n                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }\n                .container { max-width: 600px; margin: 0 auto; padding: 20px; }\n                .header { background-color: #007bff; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }\n                .content { background-color: #f8f9fa; padding: 30px; border-radius: 0 0 5px 5px; }\n                .message-box { background-color: white; border-left: 4px solid #007bff; padding: 20px; margin: 20px 0; border-radius: 5px; }\n                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }\n            </style>\n        </head>\n        <body>\n            <div class=\'container\'>\n                <div class=\'header\'>\n                    <h2>Notifikasi</h2>\n                </div>\n                <div class=\'content\'>\n                    <p>Halo <strong>staf2</strong>,</p>\n                    \n                    <div class=\'message-box\'>\n                        <h3 style=\'margin-top: 0; color: #007bff;\'>Surat Baru Masuk</h3>\n                        <p style=\'margin-bottom: 0;\'>Surat baru dari user: TES NOTIF 1</p>\n                    </div>\n                    \n                    \n            <div style=\'background-color: white; border: 1px solid #dee2e6; padding: 15px; margin: 20px 0; border-radius: 5px;\'>\n                <h4 style=\'margin-top: 0; margin-bottom: 15px; color: #007bff;\'>Detail Surat</h4>\n                <p style=\'margin: 8px 0;\'><strong>Judul Surat:</strong> TES NOTIF 1</p>\n                <p style=\'margin: 8px 0;\'><strong>Tipe Surat:</strong> Undangan</p>\n            </div>\n                    \n                    \n            <div style=\'text-align: center; margin: 30px 0;\'>\n                <a href=\'http://localhost:8080/staff/surat/23\' style=\'display: inline-block; background-color: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;\'>Lihat Detail</a>\n            </div>\n                    \n                    <p style=\'margin-top: 20px; color: #666; font-size: 14px;\'>Email ini dikirim secara otomatis dari sistem Website Padang Loang.</p>\n                </div>\n                <div class=\'footer\'>\n                    <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>\n                    <p>&copy; 2026 Website Padang Loang. All rights reserved.</p>\n                </div>\n            </div>\n        </body>\n        </html>', 1, NULL, '2026-01-08 12:56:47', 0, NULL, '2026-01-08 12:56:53', '2026-01-08 12:56:39', '2026-01-08 12:56:53'),
(15, 'alifqadry@gmail.com', 'Notifikasi - Surat Anda telah dibaca', '\n        <!DOCTYPE html>\n        <html>\n        <head>\n            <meta charset=\'UTF-8\'>\n            <style>\n                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }\n                .container { max-width: 600px; margin: 0 auto; padding: 20px; }\n                .header { background-color: #17a2b8; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }\n                .content { background-color: #f8f9fa; padding: 30px; border-radius: 0 0 5px 5px; }\n                .message-box { background-color: white; border-left: 4px solid #17a2b8; padding: 20px; margin: 20px 0; border-radius: 5px; }\n                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }\n            </style>\n        </head>\n        <body>\n            <div class=\'container\'>\n                <div class=\'header\'>\n                    <h2>Notifikasi</h2>\n                </div>\n                <div class=\'content\'>\n                    <p>Halo <strong>user</strong>,</p>\n                    \n                    <div class=\'message-box\'>\n                        <h3 style=\'margin-top: 0; color: #17a2b8;\'>Surat Anda telah dibaca</h3>\n                        <p style=\'margin-bottom: 0;\'>Surat Anda: TES NOTIF 1 telah dibaca oleh staff</p>\n                    </div>\n                    \n                    \n            <div style=\'background-color: white; border: 1px solid #dee2e6; padding: 15px; margin: 20px 0; border-radius: 5px;\'>\n                <h4 style=\'margin-top: 0; margin-bottom: 15px; color: #17a2b8;\'>Detail Surat</h4>\n                <p style=\'margin: 8px 0;\'><strong>Judul Surat:</strong> TES NOTIF 1</p>\n                <p style=\'margin: 8px 0;\'><strong>Tipe Surat:</strong> Undangan</p>\n            </div>\n                    \n                    \n            <div style=\'text-align: center; margin: 30px 0;\'>\n                <a href=\'http://localhost:8080/user/surat/23\' style=\'display: inline-block; background-color: #17a2b8; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;\'>Lihat Detail</a>\n            </div>\n                    \n                    <p style=\'margin-top: 20px; color: #666; font-size: 14px;\'>Email ini dikirim secara otomatis dari sistem Website Padang Loang.</p>\n                </div>\n                <div class=\'footer\'>\n                    <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>\n                    <p>&copy; 2026 Website Padang Loang. All rights reserved.</p>\n                </div>\n            </div>\n        </body>\n        </html>', 1, NULL, '2026-01-08 12:57:21', 0, NULL, '2026-01-08 12:57:28', '2026-01-08 12:57:20', '2026-01-08 12:57:28'),
(16, 'alifqadry14@gmail.com', 'Notifikasi - Surat Baru Masuk', '\n        <!DOCTYPE html>\n        <html>\n        <head>\n            <meta charset=\'UTF-8\'>\n            <style>\n                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }\n                .container { max-width: 600px; margin: 0 auto; padding: 20px; }\n                .header { background-color: #007bff; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }\n                .content { background-color: #f8f9fa; padding: 30px; border-radius: 0 0 5px 5px; }\n                .message-box { background-color: white; border-left: 4px solid #007bff; padding: 20px; margin: 20px 0; border-radius: 5px; }\n                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }\n            </style>\n        </head>\n        <body>\n            <div class=\'container\'>\n                <div class=\'header\'>\n                    <h2>Notifikasi</h2>\n                </div>\n                <div class=\'content\'>\n                    <p>Halo <strong>staf</strong>,</p>\n                    \n                    <div class=\'message-box\'>\n                        <h3 style=\'margin-top: 0; color: #007bff;\'>Surat Baru Masuk</h3>\n                        <p style=\'margin-bottom: 0;\'>Surat baru dari Warga Desa Padang Loang</p>\n                    </div>\n                    \n                    \n            <div style=\'background-color: white; border: 1px solid #dee2e6; padding: 15px; margin: 20px 0; border-radius: 5px;\'>\n                <h4 style=\'margin-top: 0; margin-bottom: 15px; color: #007bff;\'>Detail Surat</h4>\n                <p style=\'margin: 8px 0;\'><strong>Judul Surat:</strong> tes notif 2</p>\n                <p style=\'margin: 8px 0;\'><strong>Tipe Surat:</strong> Keterangan Usaha</p>\n            </div>\n                    \n                    \n            <div style=\'text-align: center; margin: 30px 0;\'>\n                <a href=\'http://localhost:8080/staff/surat/24\' style=\'display: inline-block; background-color: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;\'>Lihat Detail</a>\n            </div>\n                    \n                    <p style=\'margin-top: 20px; color: #666; font-size: 14px;\'>Email ini dikirim secara otomatis dari sistem Website Padang Loang.</p>\n                </div>\n                <div class=\'footer\'>\n                    <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>\n                    <p>&copy; 2026 Website Padang Loang. All rights reserved.</p>\n                </div>\n            </div>\n        </body>\n        </html>', 1, NULL, '2026-01-08 13:02:28', 0, NULL, '2026-01-08 13:02:35', '2026-01-08 13:02:28', '2026-01-08 13:02:35'),
(17, 'admin1@padangloang.id', 'Notifikasi - Surat Baru Masuk', '\n        <!DOCTYPE html>\n        <html>\n        <head>\n            <meta charset=\'UTF-8\'>\n            <style>\n                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }\n                .container { max-width: 600px; margin: 0 auto; padding: 20px; }\n                .header { background-color: #007bff; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }\n                .content { background-color: #f8f9fa; padding: 30px; border-radius: 0 0 5px 5px; }\n                .message-box { background-color: white; border-left: 4px solid #007bff; padding: 20px; margin: 20px 0; border-radius: 5px; }\n                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }\n            </style>\n        </head>\n        <body>\n            <div class=\'container\'>\n                <div class=\'header\'>\n                    <h2>Notifikasi</h2>\n                </div>\n                <div class=\'content\'>\n                    <p>Halo <strong>staf2</strong>,</p>\n                    \n                    <div class=\'message-box\'>\n                        <h3 style=\'margin-top: 0; color: #007bff;\'>Surat Baru Masuk</h3>\n                        <p style=\'margin-bottom: 0;\'>Surat baru dari Warga Desa Padang Loang</p>\n                    </div>\n                    \n                    \n            <div style=\'background-color: white; border: 1px solid #dee2e6; padding: 15px; margin: 20px 0; border-radius: 5px;\'>\n                <h4 style=\'margin-top: 0; margin-bottom: 15px; color: #007bff;\'>Detail Surat</h4>\n                <p style=\'margin: 8px 0;\'><strong>Judul Surat:</strong> tes notif 2</p>\n                <p style=\'margin: 8px 0;\'><strong>Tipe Surat:</strong> Keterangan Usaha</p>\n            </div>\n                    \n                    \n            <div style=\'text-align: center; margin: 30px 0;\'>\n                <a href=\'http://localhost:8080/staff/surat/24\' style=\'display: inline-block; background-color: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;\'>Lihat Detail</a>\n            </div>\n                    \n                    <p style=\'margin-top: 20px; color: #666; font-size: 14px;\'>Email ini dikirim secara otomatis dari sistem Website Padang Loang.</p>\n                </div>\n                <div class=\'footer\'>\n                    <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>\n                    <p>&copy; 2026 Website Padang Loang. All rights reserved.</p>\n                </div>\n            </div>\n        </body>\n        </html>', 1, NULL, '2026-01-08 13:02:35', 0, NULL, '2026-01-08 13:02:43', '2026-01-08 13:02:28', '2026-01-08 13:02:43'),
(18, 'alifqadry@gmail.com', 'Notifikasi - Surat Anda dibalas', '\n        <!DOCTYPE html>\n        <html>\n        <head>\n            <meta charset=\'UTF-8\'>\n            <style>\n                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }\n                .container { max-width: 600px; margin: 0 auto; padding: 20px; }\n                .header { background-color: #28a745; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }\n                .content { background-color: #f8f9fa; padding: 30px; border-radius: 0 0 5px 5px; }\n                .message-box { background-color: white; border-left: 4px solid #28a745; padding: 20px; margin: 20px 0; border-radius: 5px; }\n                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }\n            </style>\n        </head>\n        <body>\n            <div class=\'container\'>\n                <div class=\'header\'>\n                    <h2>Notifikasi</h2>\n                </div>\n                <div class=\'content\'>\n                    <p>Halo <strong>user</strong>,</p>\n                    \n                    <div class=\'message-box\'>\n                        <h3 style=\'margin-top: 0; color: #28a745;\'>Surat Anda dibalas</h3>\n                        <p style=\'margin-bottom: 0;\'>Balasan baru dari Staf Desa Padang Loang untuk surat: TES NOTIF 1</p>\n                    </div>\n                    \n                    \n            <div style=\'background-color: white; border: 1px solid #dee2e6; padding: 15px; margin: 20px 0; border-radius: 5px;\'>\n                <h4 style=\'margin-top: 0; margin-bottom: 15px; color: #28a745;\'>Detail Surat</h4>\n                <p style=\'margin: 8px 0;\'><strong>Judul Surat:</strong> TES NOTIF 1</p>\n                <p style=\'margin: 8px 0;\'><strong>Tipe Surat:</strong> Undangan</p>\n            </div>\n                    \n                    \n            <div style=\'text-align: center; margin: 30px 0;\'>\n                <a href=\'http://localhost:8080/user/surat/23\' style=\'display: inline-block; background-color: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;\'>Lihat Detail</a>\n            </div>\n                    \n                    <p style=\'margin-top: 20px; color: #666; font-size: 14px;\'>Email ini dikirim secara otomatis dari sistem Website Padang Loang.</p>\n                </div>\n                <div class=\'footer\'>\n                    <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>\n                    <p>&copy; 2026 Website Padang Loang. All rights reserved.</p>\n                </div>\n            </div>\n        </body>\n        </html>', 1, NULL, '2026-01-08 13:03:25', 0, NULL, '2026-01-08 13:03:31', '2026-01-08 13:03:24', '2026-01-08 13:03:31'),
(19, 'alifqadry@gmail.com', 'Notifikasi - Surat Anda telah dibaca', '\n        <!DOCTYPE html>\n        <html>\n        <head>\n            <meta charset=\'UTF-8\'>\n            <style>\n                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }\n                .container { max-width: 600px; margin: 0 auto; padding: 20px; }\n                .header { background-color: #17a2b8; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }\n                .header-logo { max-width: 150px; height: auto; margin-bottom: 15px; }\n                .content { background-color: #f8f9fa; padding: 30px; border-radius: 0 0 5px 5px; }\n                .message-box { background-color: white; border-left: 4px solid #17a2b8; padding: 20px; margin: 20px 0; border-radius: 5px; }\n                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }\n            </style>\n        </head>\n        <body>\n            <div class=\'container\'>\n                <div class=\'header\'>\n                    <img src=\'https://image2url.com/r2/bucket3/images/1767877217074-b28d4740-b35f-464b-bacc-ed74a092dd01.webp\' alt=\'Logo Desa Padang Loang\' class=\'header-logo\' style=\'max-width: 150px; height: auto; margin-bottom: 15px; display: block; margin-left: auto; margin-right: auto;\'>\n                    <h2 style=\'margin: 0;\'>Notifikasi</h2>\n                </div>\n                <div class=\'content\'>\n                    <p>Halo <strong>user</strong>,</p>\n                    \n                    <div class=\'message-box\'>\n                        <h3 style=\'margin-top: 0; color: #17a2b8;\'>Surat Anda telah dibaca</h3>\n                        <p style=\'margin-bottom: 0;\'>Surat Anda: TES NOTIF telah dibaca oleh Staf Desa Padang Loang</p>\n                    </div>\n                    \n                    \n            <div style=\'background-color: white; border: 1px solid #dee2e6; padding: 15px; margin: 20px 0; border-radius: 5px;\'>\n                <h4 style=\'margin-top: 0; margin-bottom: 15px; color: #17a2b8;\'>Detail Surat</h4>\n                <p style=\'margin: 8px 0;\'><strong>Judul Surat:</strong> TES NOTIF</p>\n                <p style=\'margin: 8px 0;\'><strong>Tipe Surat:</strong> Keterangan Tidak Mampu</p>\n            </div>\n                    \n                    \n            <div style=\'text-align: center; margin: 30px 0;\'>\n                <a href=\'http://localhost:8080/user/surat/22\' style=\'display: inline-block; background-color: #17a2b8; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;\'>Lihat Detail</a>\n            </div>\n                    \n                    <p style=\'margin-top: 20px; color: #666; font-size: 14px;\'>Email ini dikirim secara otomatis dari sistem Website Padang Loang.</p>\n                </div>\n                <div class=\'footer\'>\n                    <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>\n                    <p>&copy; 2026 Website Padang Loang. All rights reserved.</p>\n                </div>\n            </div>\n        </body>\n        </html>', 1, NULL, '2026-01-08 13:05:10', 0, NULL, '2026-01-08 13:05:16', '2026-01-08 13:05:09', '2026-01-08 13:05:16'),
(20, 'alifqadry@gmail.com', 'Notifikasi - Surat Anda dibalas', '\n        <!DOCTYPE html>\n        <html>\n        <head>\n            <meta charset=\'UTF-8\'>\n            <style>\n                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }\n                .container { max-width: 600px; margin: 0 auto; padding: 20px; }\n                .header { background-color: #28a745; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }\n                .header-logo { max-width: 150px; height: auto; margin-bottom: 15px; }\n                .content { background-color: #f8f9fa; padding: 30px; border-radius: 0 0 5px 5px; }\n                .message-box { background-color: white; border-left: 4px solid #28a745; padding: 20px; margin: 20px 0; border-radius: 5px; }\n                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }\n            </style>\n        </head>\n        <body>\n            <div class=\'container\'>\n                <div class=\'header\'>\n                    <img src=\'https://image2url.com/r2/bucket3/images/1767877217074-b28d4740-b35f-464b-bacc-ed74a092dd01.webp\' alt=\'Logo Desa Padang Loang\' class=\'header-logo\' style=\'max-width: 150px; height: auto; margin-bottom: 15px; display: block; margin-left: auto; margin-right: auto;\'>\n                    <h2 style=\'margin: 0;\'>Notifikasi</h2>\n                </div>\n                <div class=\'content\'>\n                    <p>Halo <strong>user</strong>,</p>\n                    \n                    <div class=\'message-box\'>\n                        <h3 style=\'margin-top: 0; color: #28a745;\'>Surat Anda dibalas</h3>\n                        <p style=\'margin-bottom: 0;\'>Balasan baru dari Staf Desa Padang Loang untuk surat: TES NOTIF 1</p>\n                    </div>\n                    \n                    \n            <div style=\'background-color: white; border: 1px solid #dee2e6; padding: 15px; margin: 20px 0; border-radius: 5px;\'>\n                <h4 style=\'margin-top: 0; margin-bottom: 15px; color: #28a745;\'>Detail Surat</h4>\n                <p style=\'margin: 8px 0;\'><strong>Judul Surat:</strong> TES NOTIF 1</p>\n                <p style=\'margin: 8px 0;\'><strong>Tipe Surat:</strong> Undangan</p>\n            </div>\n                    \n                    \n            <div style=\'text-align: center; margin: 30px 0;\'>\n                <a href=\'http://localhost:8080/user/surat/23\' style=\'display: inline-block; background-color: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;\'>Lihat Detail</a>\n            </div>\n                    \n                    <p style=\'margin-top: 20px; color: #666; font-size: 14px;\'>Email ini dikirim secara otomatis dari sistem Website Padang Loang.</p>\n                </div>\n                <div class=\'footer\'>\n                    <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>\n                    <p>&copy; 2026 Website Padang Loang. All rights reserved.</p>\n                </div>\n            </div>\n        </body>\n        </html>', 1, NULL, '2026-01-08 13:05:36', 0, NULL, '2026-01-08 13:05:42', '2026-01-08 13:05:36', '2026-01-08 13:05:42'),
(21, 'alifqadry@gmail.com', 'Reset Password - Kode OTP', '\n        <!DOCTYPE html>\n        <html>\n        <head>\n            <meta charset=\'UTF-8\'>\n            <style>\n                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }\n                .container { max-width: 600px; margin: 0 auto; padding: 20px; }\n                .header { background-color: #dc3545; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }\n                .content { background-color: #f8f9fa; padding: 30px; border-radius: 0 0 5px 5px; }\n                .otp-box { background-color: white; border: 2px dashed #dc3545; padding: 20px; text-align: center; margin: 20px 0; border-radius: 5px; }\n                .otp-code { font-size: 32px; font-weight: bold; color: #dc3545; letter-spacing: 5px; }\n                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }\n            </style>\n        </head>\n        <body>\n            <div class=\'container\'>\n                <div class=\'header\'>\n                    <h2>Reset Password</h2>\n                </div>\n                <div class=\'content\'>\n                    <p>Halo <strong>user</strong>,</p>\n                    <p>Kami menerima permintaan untuk mereset password akun Anda. Gunakan kode OTP berikut untuk melanjutkan:</p>\n                    \n                    <div class=\'otp-box\'>\n                        <div class=\'otp-code\'>483053</div>\n                    </div>\n                    \n                    <p>Kode OTP ini berlaku selama <strong>1 jam</strong>. Jangan bagikan kode ini kepada siapa pun.</p>\n                    <p>Jika Anda tidak meminta reset password, abaikan email ini dan password Anda tidak akan berubah.</p>\n                </div>\n                <div class=\'footer\'>\n                    <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>\n                    <p>&copy; 2026 Website Padang Loang. All rights reserved.</p>\n                </div>\n            </div>\n        </body>\n        </html>', 1, NULL, '2026-01-08 13:10:29', 0, NULL, '2026-01-08 13:10:38', '2026-01-08 13:10:29', '2026-01-08 13:10:38'),
(22, 'alifqadry@gmail.com', 'Reset Password - Kode OTP', '\n        <!DOCTYPE html>\n        <html>\n        <head>\n            <meta charset=\'UTF-8\'>\n            <style>\n                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }\n                .container { max-width: 600px; margin: 0 auto; padding: 20px; }\n                .header { background-color: #dc3545; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }\n                .content { background-color: #f8f9fa; padding: 30px; border-radius: 0 0 5px 5px; }\n                .otp-box { background-color: white; border: 2px dashed #dc3545; padding: 20px; text-align: center; margin: 20px 0; border-radius: 5px; }\n                .otp-code { font-size: 32px; font-weight: bold; color: #dc3545; letter-spacing: 5px; }\n                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }\n            </style>\n        </head>\n        <body>\n            <div class=\'container\'>\n                <div class=\'header\'>\n                    <h2>Reset Password</h2>\n                </div>\n                <div class=\'content\'>\n                    <p>Halo <strong>user</strong>,</p>\n                    <p>Kami menerima permintaan untuk mereset password akun Anda. Gunakan kode OTP berikut untuk melanjutkan:</p>\n                    \n                    <div class=\'otp-box\'>\n                        <div class=\'otp-code\'>684532</div>\n                    </div>\n                    \n                    <p>Kode OTP ini berlaku selama <strong>1 jam</strong>. Jangan bagikan kode ini kepada siapa pun.</p>\n                    <p>Jika Anda tidak meminta reset password, abaikan email ini dan password Anda tidak akan berubah.</p>\n                </div>\n                <div class=\'footer\'>\n                    <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>\n                    <p>&copy; 2026 Website Padang Loang. All rights reserved.</p>\n                </div>\n            </div>\n        </body>\n        </html>', 1, NULL, '2026-01-08 13:11:15', 0, NULL, '2026-01-08 13:11:21', '2026-01-08 13:11:15', '2026-01-08 13:11:21'),
(23, 'alifqadry@gmail.com', 'Reset Password - Kode OTP', '\n        <!DOCTYPE html>\n        <html>\n        <head>\n            <meta charset=\'UTF-8\'>\n            <meta name=\'viewport\' content=\'width=device-width, initial-scale=1.0\'>\n        </head>\n        <body style=\'margin: 0; padding: 0; font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4;\'>\n            <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'background-color: #f4f4f4;\'>\n                <tr>\n                    <td align=\'center\' style=\'padding: 20px 0;\'>\n                        <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'600\' style=\'max-width: 600px; background-color: white; border-radius: 5px; overflow: hidden;\'>\n                            <tr>\n                                <td style=\'background-color: #dc3545; padding: 20px; text-align: center;\'>\n                                    <img src=\'https://image2url.com/r2/bucket3/images/1767877728084-f96e49d3-6eda-406d-875b-ce1d2ce76a27.png\' alt=\'Desa Padang Loang\' width=\'600\' style=\'width: 100%; max-width: 600px; height: auto; display: block; border: 0; outline: none; text-decoration: none;\'>\n                                </td>\n                            </tr>\n                            <tr>\n                                <td style=\'background-color: #f8f9fa; padding: 30px;\'>\n                                    <p style=\'margin: 0 0 15px 0;\'>Halo <strong>user</strong>,</p>\n                                    <p style=\'margin: 0 0 20px 0;\'>Kami menerima permintaan untuk mereset password akun Anda. Gunakan kode OTP berikut untuk melanjutkan:</p>\n                                    \n                                    <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'background-color: white; border: 2px dashed #dc3545; margin: 20px 0;\'>\n                                        <tr>\n                                            <td style=\'padding: 20px; text-align: center;\'>\n                                                <div style=\'font-size: 32px; font-weight: bold; color: #dc3545; letter-spacing: 5px;\'>715977</div>\n                                            </td>\n                                        </tr>\n                                    </table>\n                                    \n                                    <p style=\'margin: 20px 0 15px 0;\'>Kode OTP ini berlaku selama <strong>1 jam</strong>. Jangan bagikan kode ini kepada siapa pun.</p>\n                                    <p style=\'margin: 0;\'>Jika Anda tidak meminta reset password, abaikan email ini dan password Anda tidak akan berubah.</p>\n                                </td>\n                            </tr>\n                            <tr>\n                                <td style=\'text-align: center; padding: 20px; color: #666; font-size: 12px; background-color: #f8f9fa;\'>\n                                    <p style=\'margin: 0 0 5px 0;\'>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>\n                                    <p style=\'margin: 0;\'>&copy; 2026 Website Padang Loang. All rights reserved.</p>\n                                </td>\n                            </tr>\n                        </table>\n                    </td>\n                </tr>\n            </table>\n        </body>\n        </html>', 1, NULL, '2026-01-08 13:15:17', 0, NULL, '2026-01-08 13:15:24', '2026-01-08 13:15:17', '2026-01-08 13:15:24'),
(24, 'alifqadry@gmail.com', 'Notifikasi - Surat Anda dibalas', '\n        <!DOCTYPE html>\n        <html>\n        <head>\n            <meta charset=\'UTF-8\'>\n            <meta name=\'viewport\' content=\'width=device-width, initial-scale=1.0\'>\n            <style>\n                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }\n                .container { max-width: 600px; margin: 0 auto; padding: 0; }\n                .header { background-color: #28a745; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }\n                .header img { max-width: 100%; height: auto; display: block; margin: 0 auto; border: 0; }\n                .content { background-color: #f8f9fa; padding: 30px; border-radius: 0 0 5px 5px; }\n                .message-box { background-color: white; border-left: 4px solid #28a745; padding: 20px; margin: 20px 0; border-radius: 5px; }\n                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }\n            </style>\n        </head>\n        <body>\n            <div class=\'container\'>\n                <div class=\'header\' style=\'background-color: #28a745; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;\'>\n                    <img src=\'https://image2url.com/r2/bucket3/images/1767877728084-f96e49d3-6eda-406d-875b-ce1d2ce76a27.png\' alt=\'Desa Padang Loang\' style=\'max-width: 100%; width: auto; height: auto; display: block; margin: 0 auto; border: 0; outline: none; text-decoration: none;\'>\n                </div>\n                            <tr>\n                                <td style=\'background-color: #f8f9fa; padding: 30px;\'>\n                                    <p style=\'margin: 0 0 15px 0;\'>Halo <strong>user</strong>,</p>\n                                    \n                                    <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'background-color: white; border-left: 4px solid #28a745; margin: 20px 0;\'>\n                                        <tr>\n                                            <td style=\'padding: 20px;\'>\n                                                <h3 style=\'margin: 0 0 10px 0; color: #28a745; font-size: 18px;\'>Surat Anda dibalas</h3>\n                                                <p style=\'margin: 0;\'>Balasan baru dari Staf Desa Padang Loang untuk surat: TES NOTIF 1</p>\n                                            </td>\n                                        </tr>\n                                    </table>\n                                    \n                                    \n                                    <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'background-color: white; border: 1px solid #dee2e6; margin: 20px 0;\'>\n                                        <tr>\n                                            <td style=\'padding: 15px;\'>\n                                                <h4 style=\'margin: 0 0 15px 0; color: #28a745; font-size: 16px;\'>Detail Surat</h4>\n                                                <p style=\'margin: 8px 0;\'><strong>Judul Surat:</strong> TES NOTIF 1</p>\n                                                <p style=\'margin: 8px 0;\'><strong>Tipe Surat:</strong> Undangan</p>\n                                            </td>\n                                        </tr>\n                                    </table>\n                                    \n                                    \n                                    <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'margin: 30px 0;\'>\n                                        <tr>\n                                            <td align=\'center\'>\n                                                <a href=\'http://localhost:8080/user/surat/23\' style=\'display: inline-block; background-color: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;\'>Lihat Detail</a>\n                                            </td>\n                                        </tr>\n                                    </table>\n                                    \n                                    <p style=\'margin-top: 20px; color: #666; font-size: 14px;\'>Email ini dikirim secara otomatis dari sistem Website Padang Loang.</p>\n                                </td>\n                            </tr>\n                            <tr>\n                                <td style=\'text-align: center; padding: 20px; color: #666; font-size: 12px; background-color: #f8f9fa;\'>\n                                    <p style=\'margin: 0 0 5px 0;\'>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>\n                                    <p style=\'margin: 0;\'>&copy; 2026 Website Padang Loang. All rights reserved.</p>\n                                </td>\n                            </tr>\n                        </table>\n                    </td>\n                </tr>\n            </table>\n        </body>\n        </html>', 1, NULL, '2026-01-08 13:16:02', 0, NULL, '2026-01-08 13:16:08', '2026-01-08 13:16:01', '2026-01-08 13:16:08'),
(25, 'alifqadry@gmail.com', 'Notifikasi - Surat Anda dibalas', '\n        <!DOCTYPE html>\n        <html>\n        <head>\n            <meta charset=\'UTF-8\'>\n            <meta name=\'viewport\' content=\'width=device-width, initial-scale=1.0\'>\n        </head>\n        <body style=\'margin: 0; padding: 0; font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4;\'>\n            <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'background-color: #f4f4f4;\'>\n                <tr>\n                    <td align=\'center\' style=\'padding: 20px 0;\'>\n                        <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'600\' style=\'max-width: 600px; background-color: white; border-radius: 5px; overflow: hidden;\'>\n                            <tr>\n                                <td style=\'background-color: #28a745; padding: 0; text-align: center;\'>\n                                    <img src=\'https://image2url.com/r2/bucket3/images/1767877728084-f96e49d3-6eda-406d-875b-ce1d2ce76a27.png\' alt=\'Desa Padang Loang\' width=\'600\' style=\'width: 100%; max-width: 600px; height: auto; display: block; border: 0; outline: none; text-decoration: none;\'>\n                                </td>\n                            </tr>\n                            <tr>\n                                <td style=\'background-color: #f8f9fa; padding: 30px;\'>\n                                    <p style=\'margin: 0 0 15px 0;\'>Halo <strong>user</strong>,</p>\n                                    \n                                    <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'background-color: white; border-left: 4px solid #28a745; margin: 20px 0;\'>\n                                        <tr>\n                                            <td style=\'padding: 20px;\'>\n                                                <h3 style=\'margin: 0 0 10px 0; color: #28a745; font-size: 18px; font-weight: bold;\'>Surat Anda dibalas</h3>\n                                                <p style=\'margin: 0;\'>Balasan baru dari Staf Desa Padang Loang untuk surat: TES NOTIF 1</p>\n                                            </td>\n                                        </tr>\n                                    </table>\n                                    \n                                    \n                                    <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'background-color: white; border: 1px solid #dee2e6; margin: 20px 0;\'>\n                                        <tr>\n                                            <td style=\'padding: 15px;\'>\n                                                <h4 style=\'margin: 0 0 15px 0; color: #28a745; font-size: 16px;\'>Detail Surat</h4>\n                                                <p style=\'margin: 8px 0;\'><strong>Judul Surat:</strong> TES NOTIF 1</p>\n                                                <p style=\'margin: 8px 0;\'><strong>Tipe Surat:</strong> Undangan</p>\n                                            </td>\n                                        </tr>\n                                    </table>\n                                    \n                                    \n                                    <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'margin: 30px 0 20px 0;\'>\n                                        <tr>\n                                            <td align=\'center\'>\n                                                <a href=\'http://localhost:8080/user/surat/23\' style=\'display: inline-block; background-color: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;\'>Lihat Detail</a>\n                                            </td>\n                                        </tr>\n                                    </table>\n                                    \n                                    <p style=\'margin-top: 20px; color: #666; font-size: 14px;\'>Email ini dikirim secara otomatis dari sistem Website Padang Loang.</p>\n                                </td>\n                            </tr>\n                            <tr>\n                                <td style=\'text-align: center; padding: 20px; color: #666; font-size: 12px; background-color: #f8f9fa;\'>\n                                    <p style=\'margin: 0 0 5px 0;\'>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>\n                                    <p style=\'margin: 0;\'>&copy; 2026 Website Padang Loang. All rights reserved.</p>\n                                </td>\n                            </tr>\n                        </table>\n                    </td>\n                </tr>\n            </table>\n        </body>\n        </html>', 1, NULL, '2026-01-08 13:42:42', 0, NULL, '2026-01-08 13:42:50', '2026-01-08 13:42:42', '2026-01-08 13:42:50'),
(26, 'alifqadry@gmail.com', 'Reset Password - Kode OTP', '\n        <!DOCTYPE html>\n        <html>\n        <head>\n            <meta charset=\'UTF-8\'>\n            <meta name=\'viewport\' content=\'width=device-width, initial-scale=1.0\'>\n        </head>\n        <body style=\'margin: 0; padding: 0; font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4;\'>\n            <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'background-color: #f4f4f4;\'>\n                <tr>\n                    <td align=\'center\' style=\'padding: 20px 0;\'>\n                        <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'600\' style=\'max-width: 600px; background-color: white; border-radius: 5px; overflow: hidden;\'>\n                            <tr>\n                                <td style=\'background-color: #dc3545; padding: 20px; text-align: center;\'>\n                                    <img src=\'https://image2url.com/r2/bucket3/images/1767877728084-f96e49d3-6eda-406d-875b-ce1d2ce76a27.png\' alt=\'Desa Padang Loang\' width=\'600\' style=\'width: 100%; max-width: 600px; height: auto; display: block; border: 0; outline: none; text-decoration: none;\'>\n                                </td>\n                            </tr>\n                            <tr>\n                                <td style=\'background-color: #f8f9fa; padding: 30px;\'>\n                                    <p style=\'margin: 0 0 15px 0;\'>Halo <strong>user</strong>,</p>\n                                    <p style=\'margin: 0 0 20px 0;\'>Kami menerima permintaan untuk mereset password akun Anda. Gunakan kode OTP berikut untuk melanjutkan:</p>\n                                    \n                                    <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'background-color: white; border: 2px dashed #dc3545; margin: 20px 0;\'>\n                                        <tr>\n                                            <td style=\'padding: 20px; text-align: center;\'>\n                                                <div style=\'font-size: 32px; font-weight: bold; color: #dc3545; letter-spacing: 5px;\'>142141</div>\n                                            </td>\n                                        </tr>\n                                    </table>\n                                    \n                                    <p style=\'margin: 20px 0 15px 0;\'>Kode OTP ini berlaku selama <strong>1 jam</strong>. Jangan bagikan kode ini kepada siapa pun.</p>\n                                    <p style=\'margin: 0;\'>Jika Anda tidak meminta reset password, abaikan email ini dan password Anda tidak akan berubah.</p>\n                                </td>\n                            </tr>\n                            <tr>\n                                <td style=\'text-align: center; padding: 20px; color: #666; font-size: 12px; background-color: #f8f9fa;\'>\n                                    <p style=\'margin: 0 0 5px 0;\'>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>\n                                    <p style=\'margin: 0;\'>&copy; 2026 Website Padang Loang. All rights reserved.</p>\n                                </td>\n                            </tr>\n                        </table>\n                    </td>\n                </tr>\n            </table>\n        </body>\n        </html>', 1, NULL, '2026-01-08 13:43:35', 0, NULL, '2026-01-08 13:43:43', '2026-01-08 13:43:35', '2026-01-08 13:43:43');
INSERT INTO `email_queue` (`id`, `recipient`, `subject`, `body`, `is_sent`, `processing_token`, `processing_at`, `fail_count`, `last_error`, `sent_at`, `created_at`, `updated_at`) VALUES
(27, 'alifqadry@gmail.com', 'Notifikasi - Surat Anda dibalas', '\n        <!DOCTYPE html>\n        <html>\n        <head>\n            <meta charset=\'UTF-8\'>\n            <meta name=\'viewport\' content=\'width=device-width, initial-scale=1.0\'>\n        </head>\n        <body style=\'margin: 0; padding: 0; font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4;\'>\n            <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'background-color: #f4f4f4;\'>\n                <tr>\n                    <td align=\'center\' style=\'padding: 20px 0;\'>\n                        <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'600\' style=\'max-width: 600px; background-color: white; border-radius: 5px; overflow: hidden;\'>\n                            <tr>\n                                <td style=\'background-color: #28a745; padding: 0; text-align: center;\'>\n                                    <img src=\'https://image2url.com/r2/bucket3/images/1767877728084-f96e49d3-6eda-406d-875b-ce1d2ce76a27.png\' alt=\'Desa Padang Loang\' width=\'600\' style=\'width: 100%; max-width: 600px; height: auto; display: block; border: 0; outline: none; text-decoration: none;\'>\n                                </td>\n                            </tr>\n                            <tr>\n                                <td style=\'background-color: #f8f9fa; padding: 30px;\'>\n                                    <p style=\'margin: 0 0 15px 0;\'>Halo <strong>user</strong>,</p>\n                                    \n                                    <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'background-color: white; border-left: 4px solid #28a745; margin: 20px 0;\'>\n                                        <tr>\n                                            <td style=\'padding: 20px;\'>\n                                                <h3 style=\'margin: 0 0 10px 0; color: #28a745; font-size: 18px; font-weight: bold;\'>Surat Anda dibalas</h3>\n                                                <p style=\'margin: 0;\'>Balasan baru dari Staf Desa Padang Loang untuk surat: TES NOTIF 1</p>\n                                            </td>\n                                        </tr>\n                                    </table>\n                                    \n                                    \n                                    <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'background-color: white; border: 1px solid #dee2e6; margin: 20px 0;\'>\n                                        <tr>\n                                            <td style=\'padding: 15px;\'>\n                                                <h4 style=\'margin: 0 0 15px 0; color: #28a745; font-size: 16px;\'>Detail Surat</h4>\n                                                <p style=\'margin: 8px 0;\'><strong>Judul Surat:</strong> TES NOTIF 1</p>\n                                                <p style=\'margin: 8px 0;\'><strong>Tipe Surat:</strong> Undangan</p>\n                                            </td>\n                                        </tr>\n                                    </table>\n                                    \n                                    \n                                    <table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'margin: 30px 0 20px 0;\'>\n                                        <tr>\n                                            <td align=\'center\'>\n                                                <a href=\'http://localhost:8080/user/surat/23\' style=\'display: inline-block; background-color: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;\'>Lihat Detail</a>\n                                            </td>\n                                        </tr>\n                                    </table>\n                                    \n                                    <p style=\'margin-top: 20px; color: #666; font-size: 14px;\'>Email ini dikirim secara otomatis dari sistem Website Padang Loang.</p>\n                                </td>\n                            </tr>\n                            <tr>\n                                <td style=\'text-align: center; padding: 20px; color: #666; font-size: 12px; background-color: #f8f9fa;\'>\n                                    <p style=\'margin: 0 0 5px 0;\'>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>\n                                    <p style=\'margin: 0;\'>&copy; 2026 Website Padang Loang. All rights reserved.</p>\n                                </td>\n                            </tr>\n                        </table>\n                    </td>\n                </tr>\n            </table>\n        </body>\n        </html>', 1, NULL, '2026-01-08 13:44:03', 0, NULL, '2026-01-08 13:44:10', '2026-01-08 13:44:03', '2026-01-08 13:44:10'),
(28, 'alifqadry@gmail.com', 'Notifikasi - Surat Anda dibalas', '<!DOCTYPE html><html><head><meta charset=\'UTF-8\'><meta name=\'viewport\' content=\'width=device-width, initial-scale=1.0\'></head><body style=\'margin:0;padding:0;font-family:Arial,sans-serif;line-height:1.6;color:#333;background-color:#f4f4f4\'><table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'background-color:#f4f4f4\'><tr><td align=\'center\' style=\'padding:20px 0\'><table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'600\' style=\'max-width:600px;background-color:white;border-radius:5px;overflow:hidden\'><tr><td style=\'background-color:#28a745;padding:0;text-align:center\'><img src=\'https://image2url.com/r2/bucket3/images/1767877728084-f96e49d3-6eda-406d-875b-ce1d2ce76a27.png\' alt=\'Desa Padang Loang\' width=\'600\' style=\'width:100%;max-width:600px;height:auto;display:block;border:0;outline:none;text-decoration:none\'></td></tr><tr><td style=\'background-color:#f8f9fa;padding:30px\'><p style=\'margin:0 0 15px 0\'>Halo <strong>user</strong>,</p><table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'background-color:white;border-left:4px solid #28a745;margin:20px 0\'><tr><td style=\'padding:20px\'><h3 style=\'margin:0 0 10px 0;color:#28a745;font-size:18px;font-weight:bold\'>Surat Anda dibalas</h3><p style=\'margin:0\'>Balasan baru dari Staf Desa Padang Loang untuk surat: TES NOTIF 1</p></td></tr></table><table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'background-color: white; border: 1px solid #dee2e6; margin: 20px 0;\'><tr><td style=\'padding: 15px;\'><h4 style=\'margin: 0 0 15px 0; color: #28a745; font-size: 16px;\'>Detail Surat</h4><p style=\'margin: 8px 0;\'><strong>Judul Surat:</strong> TES NOTIF 1</p><p style=\'margin: 8px 0;\'><strong>Tipe Surat:</strong> Undangan</p></td></tr></table><table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'100%\' style=\'margin: 30px 0 20px 0;\'><tr><td align=\'center\'><a href=\'http://localhost:8080/user/surat/23\' style=\'display: inline-block; background-color: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;\'>Lihat Detail</a></td></tr></table><p style=\'margin-top:20px;color:#666;font-size:14px\'>Email ini dikirim secara otomatis dari sistem Website Padang Loang.</p></td></tr><tr><td style=\'text-align:center;padding:20px;color:#666;font-size:12px;background-color:#f8f9fa\'><p style=\'margin:0 0 5px 0\'>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p><p style=\'margin:0\'>&copy; 2026 Website Padang Loang. All rights reserved.</p></td></tr></table></td></tr></table></body></html>', 1, NULL, '2026-01-08 13:50:34', 0, NULL, '2026-01-08 13:50:40', '2026-01-08 13:50:33', '2026-01-08 13:50:40'),
(29, 'alifqadry@gmail.com', 'Notifikasi - Surat Anda dibalas', '<!DOCTYPE html><html lang=\'id\'><head><meta charset=\'UTF-8\'><meta http-equiv=\'X-UA-Compatible\' content=\'IE=edge\'><meta name=\'viewport\' content=\'width=device-width,initial-scale=1.0\'><link href=\'https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap\' rel=\'stylesheet\'></head><body style=\'margin:0;padding:0;background:#f5f7fb;font-family:Poppins,Arial,sans-serif;color:#1f2933\'><table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' align=\'center\' width=\'100%\' style=\'background:#f5f7fb;padding:24px 0\'><tr><td align=\'center\'><table role=\'presentation\' cellspacing=\'0\' cellpadding=\'0\' border=\'0\' width=\'560\' style=\'background:#ffffff;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.05);border:1px solid #e5e7eb;overflow:hidden\'><tr><td style=\'padding:24px 24px 8px 24px;text-align:center\'><img src=\'https://image2url.com/r2/bucket3/images/1767877728084-f96e49d3-6eda-406d-875b-ce1d2ce76a27.png\' alt=\'Desa Padang Loang\' style=\'max-width:100%;height:auto;display:block;margin:0 auto 12px auto\'></td></tr><tr><td style=\'padding:0 24px 8px 24px\'><h1 style=\'margin:0;font-size:22px;font-weight:600;color:#111827\'>Halo user,</h1></td></tr><tr><td style=\'padding:0 24px 16px 24px\'><div style=\'background:#f9fafb;border-left:4px solid #10b981;border-radius:8px;padding:16px\'><h2 style=\'margin:0 0 8px 0;font-size:18px;font-weight:600;color:#10b981\'>Surat Anda dibalas</h2><p style=\'margin:0;font-size:14px;line-height:1.6;color:#4b5563\'>Balasan baru dari Staf Desa Padang Loang untuk surat: TES NOTIF 1</p></div></td></tr><tr><td style=\'padding:0 24px 16px 24px\'><div style=\'background:#f9fafb;border-left:4px solid #10b981;border-radius:8px;padding:16px\'><h3 style=\'margin:0 0 12px 0;font-size:16px;font-weight:600;color:#10b981\'>Detail Surat</h3><p style=\'margin:8px 0;font-size:14px;color:#4b5563\'><strong>Judul Surat:</strong> TES NOTIF 1</p><p style=\'margin:8px 0;font-size:14px;color:#4b5563\'><strong>Tipe Surat:</strong> Undangan</p></div></td></tr><tr><td style=\'padding:0 24px 16px 24px;text-align:center\'><a href=\'http://localhost:8080/user/surat/23\' style=\'display:inline-block;background:#10b981;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:10px;font-weight:600;font-size:14px\'>Lihat Detail</a></td></tr><tr><td style=\'padding:14px 24px 18px 24px;font-size:12px;line-height:1.6;color:#6b7280;text-align:center\'>Email ini dikirim secara otomatis dari sistem Website Padang Loang.</td></tr><tr><td style=\'padding:0 24px 24px 24px;font-size:13px;color:#9ca3af;text-align:center\'>&copy; 2026 Website Padang Loang. All rights reserved.</td></tr></table></td></tr></table></body></html>', 1, NULL, '2026-01-08 13:56:16', 0, NULL, '2026-01-08 13:56:22', '2026-01-08 13:56:15', '2026-01-08 13:56:22');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_albums`
--

CREATE TABLE `gallery_albums` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_album` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_waktu` datetime NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery_albums`
--

INSERT INTO `gallery_albums` (`id`, `nama_album`, `deskripsi`, `tanggal_waktu`, `thumbnail`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'TES 2', 'ADASD', '2026-01-01 09:08:00', 'uploads/gallery/1767229733_6bfb37fbe345cbc6ccbe.png', 2, '2026-01-01 01:08:53', '2026-01-01 10:20:56'),
(3, 'TES 3', 'OKE TES', '2026-01-03 10:40:00', 'uploads/gallery/1767408072_561d3c784e9d9620ef3d.webp', 2, '2026-01-03 10:41:12', NULL),
(4, 'TES 4', 'A', '2026-01-03 10:41:00', 'uploads/gallery/1767408103_93a34376cb412e771d08.webp', 2, '2026-01-03 10:41:44', NULL),
(5, 'TES 1', 'DSASDA', '2026-01-03 10:42:00', 'uploads/gallery/1767408136_4567e41614b30eed4ea3.webp', 2, '2026-01-03 10:42:17', NULL),
(6, 'TES 5', 'TEDFS', '2026-01-03 10:42:00', 'uploads/gallery/1767408185_84536017ff0af4cf2041.webp', 2, '2026-01-03 10:43:05', NULL),
(7, 'TES 6', 'DASSDA', '2026-01-03 10:43:00', 'uploads/gallery/1767408226_82de27f2666b78253240.webp', 2, '2026-01-03 10:43:46', NULL),
(8, 'TES 7', 'AWEEA', '2026-01-03 10:43:00', 'uploads/gallery/1767408254_4fd05cecded5abbf0062.webp', 2, '2026-01-03 10:44:15', NULL),
(9, 'TES 8', '121212121', '2026-01-03 10:44:00', 'uploads/gallery/1767408288_a13f27e30d6decc5e7d7.webp', 2, '2026-01-03 10:44:48', NULL),
(10, 'TES 9', '21SER ERA', '2026-01-03 10:45:00', 'uploads/gallery/1767408324_aee03183a2dfd16495cb.webp', 2, '2026-01-03 10:45:24', NULL),
(11, 'TES 10', 'QWEQE', '2026-01-03 10:45:00', 'uploads/gallery/1767408341_13e0b97f04919988b85a.webp', 2, '2026-01-03 10:45:41', NULL),
(12, 'TES 11', '121', '2026-01-03 10:45:00', 'uploads/gallery/1767408356_1f90bf9ad24d78fc617d.webp', 2, '2026-01-03 10:45:56', NULL),
(13, 'TES 12', 'AESEAWE', '2026-01-03 10:50:00', 'uploads/gallery/1767408644_558d34fba3350e01bc95.webp', 2, '2026-01-03 10:50:44', NULL),
(14, 'TES 13', 'QWEQAWE', '2026-01-03 10:50:00', 'uploads/gallery/1767408660_ca559eab387ebc1884f7.webp', 2, '2026-01-03 10:51:00', NULL),
(15, 'Album Jembatan', 'tes', '2026-01-04 11:45:00', NULL, 2, '2026-01-04 11:46:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gallery_media`
--

CREATE TABLE `gallery_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `album_id` bigint(20) UNSIGNED NOT NULL,
  `media_type` enum('foto','video_link') NOT NULL,
  `media_path` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery_media`
--

INSERT INTO `gallery_media` (`id`, `album_id`, `media_type`, `media_path`, `created_at`) VALUES
(3, 2, 'foto', 'uploads/gallery/1767229733_cddac87982222e99defa.jpg', '2026-01-01 01:08:53'),
(4, 2, 'foto', 'uploads/gallery/1767229733_8c435bfe69f795a75391.jpg', '2026-01-01 01:08:53'),
(7, 2, 'video_link', 'https://www.youtube.com/watch?v=TDSpCCqWMho', '2026-01-01 10:34:33'),
(11, 2, 'foto', 'uploads/gallery/1767357346_4a2ea771cdc6110c5407.webp', '2026-01-02 20:35:46'),
(12, 15, 'video_link', 'https://www.youtube.com/watch?v=UC95OyVzHuw', '2026-01-04 11:46:55');

-- --------------------------------------------------------

--
-- Table structure for table `letters`
--

CREATE TABLE `letters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_unik` varchar(50) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `judul_perihal` varchar(200) NOT NULL,
  `tipe_surat` enum('Keterangan Usaha','Keterangan Tidak Mampu','Keterangan Belum Menikah','Keterangan Domisili','Undangan','Lain Lain') NOT NULL DEFAULT 'Lain Lain',
  `isi_surat` longtext NOT NULL,
  `status` enum('Terkirim','Dibaca','Dibalas') NOT NULL DEFAULT 'Terkirim',
  `sent_at` datetime NOT NULL DEFAULT current_timestamp(),
  `read_at` datetime DEFAULT NULL,
  `replied_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `letters`
--

INSERT INTO `letters` (`id`, `kode_unik`, `user_id`, `assigned_staff_id`, `judul_perihal`, `tipe_surat`, `isi_surat`, `status`, `sent_at`, `read_at`, `replied_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 3, 5, 'tes', '', 'tes', 'Dibalas', '2026-01-01 02:21:55', '2026-01-01 02:22:03', '2026-01-03 23:39:40', '2026-01-01 10:21:55', '2026-01-04 07:39:40'),
(3, NULL, 3, 2, 'OKE', '', '121231', 'Dibaca', '2026-01-02 10:42:51', '2026-01-02 23:10:16', NULL, '2026-01-02 18:42:51', '2026-01-03 07:10:16'),
(4, NULL, 3, NULL, 'OKE', '', 'WQWQW', 'Terkirim', '2026-01-03 00:13:14', NULL, NULL, '2026-01-03 08:13:14', NULL),
(5, NULL, 3, NULL, '1212', '', '121212', 'Terkirim', '2026-01-03 00:13:20', NULL, NULL, '2026-01-03 08:13:20', NULL),
(6, NULL, 3, NULL, '212 EFRAD', '', 'ASDADA', 'Terkirim', '2026-01-03 00:13:25', NULL, NULL, '2026-01-03 08:13:25', NULL),
(7, NULL, 3, NULL, 'ADSASD', '', 'ADSADADSA', 'Terkirim', '2026-01-03 00:13:30', NULL, NULL, '2026-01-03 08:13:30', NULL),
(8, NULL, 3, NULL, 'DASDASDAD', '', 'ASDASD', 'Terkirim', '2026-01-03 00:13:34', NULL, NULL, '2026-01-03 08:13:34', NULL),
(9, NULL, 3, NULL, 'ASDASDA', '', 'ADASDAD', 'Terkirim', '2026-01-03 00:13:39', NULL, NULL, '2026-01-03 08:13:39', NULL),
(10, NULL, 3, 5, 'ADASDASD', '', 'ASDASDSAD', 'Dibaca', '2026-01-03 00:13:43', '2026-01-03 23:43:04', NULL, '2026-01-03 08:13:43', '2026-01-04 07:43:04'),
(11, NULL, 3, 2, 'ASDDSAD', '', 'DASDASDASD', 'Dibaca', '2026-01-03 00:13:48', '2026-01-03 00:14:49', NULL, '2026-01-03 08:13:48', '2026-01-03 08:14:49'),
(12, NULL, 3, 2, 'ADASDAS', '', 'ASDASDASD', 'Dibaca', '2026-01-03 00:13:52', '2026-01-03 22:55:59', NULL, '2026-01-03 08:13:52', '2026-01-04 06:55:59'),
(13, 'SURAT-20260104-C72F8A', 3, 5, 'TES KODE', '', 'EDAEAWE', 'Dibaca', '2026-01-04 00:04:04', '2026-01-04 00:04:14', NULL, '2026-01-04 08:04:04', '2026-01-04 08:04:14'),
(14, 'SURAT-20260104-AA614A', 3, 2, 'surat bansos', '', 'tes', 'Dibalas', '2026-01-04 03:39:49', '2026-01-04 03:40:07', '2026-01-04 03:44:03', '2026-01-04 11:39:49', '2026-01-04 11:44:03'),
(15, 'SURAT-20260105-7D17A0', 3, 2, 'OKE', 'Keterangan Usaha', '212121', 'Dibaca', '2026-01-05 05:24:58', '2026-01-05 23:19:25', NULL, '2026-01-05 13:24:58', '2026-01-06 07:19:25'),
(16, 'SURAT-20260106-BC5033', 3, 2, '1212', 'Keterangan Tidak Mampu', '121212', 'Dibaca', '2026-01-06 00:17:43', '2026-01-06 00:17:48', NULL, '2026-01-06 08:17:43', '2026-01-06 08:17:48'),
(17, 'SURAT-20260106-E505B1', 3, 2, 'TES 12212', 'Keterangan Belum Menikah', '1212', 'Dibaca', '2026-01-06 01:27:27', '2026-01-06 01:27:33', NULL, '2026-01-06 09:27:27', '2026-01-06 09:27:33'),
(18, 'SURAT-20260106-AAED80', 3, 2, 'tes 1212', 'Keterangan Domisili', 'qweqe', 'Dibaca', '2026-01-06 01:52:47', '2026-01-06 01:52:53', NULL, '2026-01-06 09:52:47', '2026-01-06 09:52:53'),
(19, 'SURAT-20260106-47E56D', 3, 2, 'WEQA', 'Undangan', 'ADADSAD', 'Dibaca', '2026-01-06 02:01:59', '2026-01-06 02:02:10', NULL, '2026-01-06 10:01:59', '2026-01-06 10:02:10'),
(20, 'SURAT-20260108-A5AB69', 3, NULL, 'TES NOTIF', 'Keterangan Usaha', '1221', 'Terkirim', '2026-01-08 12:17:34', NULL, NULL, '2026-01-08 20:17:34', NULL),
(21, 'SURAT-20260108-F8F305', 3, 2, 'TES NOTIF', 'Keterangan Usaha', '121212', 'Dibaca', '2026-01-08 12:18:51', '2026-01-08 12:19:53', NULL, '2026-01-08 20:18:51', '2026-01-08 20:19:53'),
(22, 'SURAT-20260108-008E6C', 3, 2, 'TES NOTIF', 'Keterangan Tidak Mampu', '1212', 'Dibaca', '2026-01-08 12:45:31', '2026-01-08 13:05:09', NULL, '2026-01-08 20:45:31', '2026-01-08 21:05:09'),
(23, 'SURAT-20260108-A822F3', 3, 2, 'TES NOTIF 1', 'Undangan', '131312', 'Dibalas', '2026-01-08 12:56:39', '2026-01-08 12:57:20', '2026-01-08 13:56:15', '2026-01-08 20:56:39', '2026-01-08 21:56:15'),
(24, 'SURAT-20260108-354FAF', 3, NULL, 'tes notif 2', 'Keterangan Usaha', 'asdada', 'Terkirim', '2026-01-08 13:02:28', NULL, NULL, '2026-01-08 21:02:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `letter_attachments`
--

CREATE TABLE `letter_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `letter_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `letter_attachments`
--

INSERT INTO `letter_attachments` (`id`, `letter_id`, `file_path`, `original_name`, `mime_type`, `file_size`, `uploaded_at`) VALUES
(1, 1, 'uploads/letters/1767234115_ca2cea433cc57846a7e0.pdf', 'Rancangan_Fitur_Website_Desa_Padang_Loang.pdf', 'application/pdf', 36764, '2026-01-01 10:21:55'),
(3, 4, 'uploads/letters/1767399194_1504f7a49c471aa14d85.pdf', 'Markdown to PDF (2).pdf', 'application/pdf', 50128, '2026-01-03 08:13:14'),
(4, 13, 'uploads/letters/1767485044_7555d2958f650f1bed3d.pdf', 'Rekap_Laporan_Disiplin_Hakim_Januari_2026.pdf', 'application/pdf', 133271, '2026-01-04 08:04:04'),
(5, 14, 'uploads/letters/1767497989_45517ca9c10fa19c6f30.pdf', 'Rekap_Laporan_Disiplin_Hakim_Januari_2026 (1).pdf', 'application/pdf', 134332, '2026-01-04 11:39:49'),
(6, 16, 'uploads/letters/1767658663_4aee14e4333f9ee0f0a4.pdf', 'Surat_Keterangan_Tidak_Mampu (1).pdf', 'application/pdf', 137516, '2026-01-06 08:17:43');

-- --------------------------------------------------------

--
-- Table structure for table `letter_replies`
--

CREATE TABLE `letter_replies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `letter_id` bigint(20) UNSIGNED NOT NULL,
  `staff_id` bigint(20) UNSIGNED NOT NULL,
  `reply_text` longtext NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `letter_replies`
--

INSERT INTO `letter_replies` (`id`, `letter_id`, `staff_id`, `reply_text`, `created_at`) VALUES
(4, 1, 2, 'OKE', '2026-01-01 11:37:14'),
(5, 1, 2, '1', '2026-01-02 18:25:55'),
(6, 1, 5, 'YA', '2026-01-04 07:39:40'),
(7, 14, 2, 'oke', '2026-01-04 11:44:03'),
(8, 23, 2, 'oke', '2026-01-08 21:01:29'),
(10, 23, 2, 'TES', '2026-01-08 21:05:36'),
(11, 23, 2, 'TES', '2026-01-08 21:16:01'),
(12, 23, 2, '1', '2026-01-08 21:42:42'),
(13, 23, 2, '1', '2026-01-08 21:44:03'),
(14, 23, 2, '1', '2026-01-08 21:50:33'),
(15, 23, 2, '12', '2026-01-08 21:56:15');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-01-02-101717', 'App\\Database\\Migrations\\CreatePerangkatDesaTable', 'default', 'App', 1767349148, 1),
(2, '2026-01-03-000000', 'App\\Database\\Migrations\\AddKodeUnikToLettersTable', 'default', 'App', 1767484983, 2),
(3, '2026-01-04-000000', 'App\\Database\\Migrations\\ChangeTipeSuratToEnum', 'default', 'App', 1767589677, 3),
(4, '2026-01-06-000000', 'App\\Database\\Migrations\\CreateEmailQueueTable', 'default', 'App', 1767875431, 4);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(200) NOT NULL,
  `tanggal_waktu` datetime NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `isi` longtext NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `judul`, `tanggal_waktu`, `thumbnail`, `isi`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Perbedaan Obesitas pada Anak dan Orang Dewasa', '2026-01-01 09:49:00', 'uploads/news/1767232172_6483aa82d447e98e05a2.webp', 'dasdadas', 2, '2026-01-01 09:49:32', '2026-01-01 12:00:20'),
(2, 'tes fitur lagi', '2026-01-02 20:49:00', 'uploads/news/1767358191_32fca80c238fa4348283.jpg', '<p><strong>Bismillah</strong></p><p><br></p><p><img src=\"data:image/jpeg;base64,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\"></p><p><br></p><p>Oke </p>', 2, '2026-01-02 20:49:51', NULL),
(3, 'Pembagunan Jembatan 1', '2026-01-02 22:03:00', 'uploads/news/1767362602_242274c5924452b65ac1.jpg', '<h1><strong><em>Bismillah</em></strong></h1><p><br></p><p><img src=\"data:image/jpeg;base64,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\"></p><p>Oke  TES LAGI</p><iframe class=\"ql-video\" frameborder=\"0\" allowfullscreen=\"true\" src=\"https://www.youtube.com/embed/8fmaWD4JruQ?showinfo=0\"></iframe><p><br></p>', 2, '2026-01-02 22:03:22', '2026-01-02 22:03:53'),
(4, 'TES 4', '2026-01-03 10:55:00', 'uploads/news/1767408929_6c96264bdb3cb3b66f02.jpg', '<p><strong><em>DADAWDAWD</em></strong></p>', 2, '2026-01-03 10:55:29', NULL),
(5, 'TES 5', '2026-01-03 10:55:00', 'uploads/news/1767408945_b6253f3c61e1206428b3.jpg', '<p>SADASDADAS</p>', 2, '2026-01-03 10:55:45', NULL),
(6, 'TES 6', '2026-01-03 10:55:00', 'uploads/news/1767408962_5cd40f53c7ebf52c9f2a.jpg', '<p>ASDASD</p>', 2, '2026-01-03 10:56:02', NULL),
(7, 'TES 7', '2026-01-03 02:56:46', 'uploads/news/1767409006_b09425c6b22c08720e2a.jpg', '<p>WEQEWQEQ</p>', 2, '2026-01-03 10:56:46', NULL),
(8, 'TES 8', '2026-01-03 10:56:00', 'uploads/news/1767409052_1c6cc150926a3b302830.jpg', '<p>AWDAWWA</p>', 2, '2026-01-03 10:57:32', NULL),
(9, 'TES 9', '2026-01-03 10:57:00', 'uploads/news/1767409229_1f9533e6d147e78dad30.jpg', '<p>ASDASDA</p>', 2, '2026-01-03 10:58:03', '2026-01-03 11:00:29'),
(10, 'TES 10', '2026-01-03 11:00:00', 'uploads/news/1767409262_00e095464ce2a241d412.jpg', '<p>ADAD AWSDASD</p>', 2, '2026-01-03 11:01:02', NULL),
(11, 'TES 11', '2026-01-03 11:01:00', 'uploads/news/1767409287_bdd37f0084f01e6318d7.jpg', '<p>DSAASDA</p>', 2, '2026-01-03 11:01:27', NULL),
(12, 'TES 12', '2026-01-03 11:01:00', 'uploads/news/1767409302_c0e591bc450b2a6b0914.jpg', '<p>DASDASD</p>', 2, '2026-01-03 11:01:42', NULL),
(13, 'TES 13', '2026-01-03 11:01:00', 'uploads/news/1767409320_e2c163e20d00a0a7a90c.jpg', '<p>ADSADAS</p>', 2, '2026-01-03 11:02:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `news_media`
--

CREATE TABLE `news_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `news_id` bigint(20) UNSIGNED NOT NULL,
  `media_type` enum('foto','video_link') NOT NULL DEFAULT 'foto',
  `media_path` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_media`
--

INSERT INTO `news_media` (`id`, `news_id`, `media_type`, `media_path`, `created_at`) VALUES
(1, 1, 'video_link', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ&list=RDdQw4w9WgXcQ&start_radio=1', '2026-01-01 10:52:24'),
(2, 1, 'video_link', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ&list=RDdQw4w9WgXcQ&start_radio=1', '2026-01-01 10:52:24'),
(3, 4, 'foto', 'uploads/news/1767408929_4ed3a5baf617e3d61ed6.jpg', '2026-01-03 10:55:29'),
(4, 4, 'foto', 'uploads/news/1767408929_5d497a2356d026cefa33.jpg', '2026-01-03 10:55:29'),
(5, 5, 'foto', 'uploads/news/1767408945_8b159936bf568ffecbc4.jpg', '2026-01-03 10:55:45'),
(6, 5, 'foto', 'uploads/news/1767408945_7bb0150806cf80a211c7.jpg', '2026-01-03 10:55:45'),
(7, 6, 'foto', 'uploads/news/1767408962_36ed86e54f6f3f6110ab.jpg', '2026-01-03 10:56:02'),
(8, 6, 'foto', 'uploads/news/1767408962_10ed18d3a386d8f8efb3.jpg', '2026-01-03 10:56:02'),
(9, 7, 'foto', 'uploads/news/1767409006_90a126b7a73ce8ddb3ab.jpg', '2026-01-03 10:56:46'),
(10, 7, 'foto', 'uploads/news/1767409006_e776b9d1cbe4edd7ae82.jpg', '2026-01-03 10:56:46'),
(11, 8, 'foto', 'uploads/news/1767409052_2407724846cc6bb242a6.jpg', '2026-01-03 10:57:32'),
(12, 8, 'foto', 'uploads/news/1767409052_a9c1145ecb11c3cc5395.jpg', '2026-01-03 10:57:32'),
(15, 9, 'foto', 'uploads/news/1767409229_ec85ac2eea35c1fb540f.jpg', '2026-01-03 11:00:29'),
(16, 9, 'foto', 'uploads/news/1767409229_91a9dd296186570a3cf7.jpg', '2026-01-03 11:00:29'),
(17, 10, 'foto', 'uploads/news/1767409262_c16f5d3be3752cb20139.jpg', '2026-01-03 11:01:02'),
(18, 10, 'foto', 'uploads/news/1767409262_2959389618a46da2c79c.jpg', '2026-01-03 11:01:02'),
(19, 11, 'foto', 'uploads/news/1767409287_02e6607f52c72a503c74.jpg', '2026-01-03 11:01:27'),
(20, 11, 'foto', 'uploads/news/1767409287_4602819c3eb4d6f8caac.jpg', '2026-01-03 11:01:27'),
(21, 12, 'foto', 'uploads/news/1767409302_753527c67698e83d80c6.jpg', '2026-01-03 11:01:42'),
(22, 12, 'foto', 'uploads/news/1767409302_d102f33e95b6ed6f5e4e.jpg', '2026-01-03 11:01:42'),
(23, 13, 'foto', 'uploads/news/1767409320_bd0addfe408272b3af4a.jpg', '2026-01-03 11:02:00'),
(24, 13, 'foto', 'uploads/news/1767409320_d1f89e4e303395672b5e.jpg', '2026-01-03 11:02:00');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text DEFAULT NULL,
  `related_letter_id` bigint(20) UNSIGNED DEFAULT NULL,
  `related_reply_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `read_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `related_letter_id`, `related_reply_id`, `is_read`, `created_at`, `read_at`) VALUES
(6, 3, 'reply', 'Surat Anda dibalas', 'Balasan baru untuk surat: tes', 1, 4, 0, '2026-01-01 03:37:14', NULL),
(7, 3, 'reply', 'Surat Anda dibalas', 'Balasan baru untuk surat: tes', 1, 5, 0, '2026-01-02 10:25:55', NULL),
(8, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: OKE', 3, NULL, 0, '2026-01-02 10:42:51', NULL),
(9, 3, 'letter_read', 'Surat Anda telah dibaca', 'Surat Anda: OKE telah dibaca oleh staff', 3, NULL, 0, '2026-01-02 23:10:16', NULL),
(10, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: OKE', 4, NULL, 0, '2026-01-03 00:13:14', NULL),
(11, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: 1212', 5, NULL, 0, '2026-01-03 00:13:20', NULL),
(12, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: 212 EFRAD', 6, NULL, 0, '2026-01-03 00:13:25', NULL),
(13, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: ADSASD', 7, NULL, 0, '2026-01-03 00:13:30', NULL),
(14, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: DASDASDAD', 8, NULL, 0, '2026-01-03 00:13:34', NULL),
(15, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: ASDASDA', 9, NULL, 0, '2026-01-03 00:13:39', NULL),
(16, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: ADASDASD', 10, NULL, 0, '2026-01-03 00:13:43', NULL),
(17, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: ASDDSAD', 11, NULL, 0, '2026-01-03 00:13:48', NULL),
(18, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: ADASDAS', 12, NULL, 0, '2026-01-03 00:13:52', NULL),
(19, 3, 'letter_read', 'Surat Anda telah dibaca', 'Surat Anda: ASDDSAD telah dibaca oleh staff', 11, NULL, 0, '2026-01-03 00:14:49', NULL),
(20, 3, 'letter_read', 'Surat Anda telah dibaca', 'Surat Anda: ADASDAS telah dibaca oleh staff', 12, NULL, 0, '2026-01-03 22:55:59', NULL),
(21, 3, 'reply', 'Surat Anda dibalas', 'Balasan baru untuk surat: tes', 1, 6, 0, '2026-01-03 23:39:40', NULL),
(22, 3, 'letter_read', 'Surat Anda telah dibaca', 'Surat Anda: ADASDASD telah dibaca oleh staff', 10, NULL, 0, '2026-01-03 23:43:04', NULL),
(23, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: TES KODE', 13, NULL, 0, '2026-01-04 00:04:04', NULL),
(24, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: TES KODE', 13, NULL, 0, '2026-01-04 00:04:04', NULL),
(25, 3, 'letter_read', 'Surat Anda telah dibaca', 'Surat Anda: TES KODE telah dibaca oleh staff', 13, NULL, 0, '2026-01-04 00:04:14', NULL),
(26, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: surat bansos', 14, NULL, 0, '2026-01-04 03:39:49', NULL),
(27, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: surat bansos', 14, NULL, 0, '2026-01-04 03:39:49', NULL),
(28, 3, 'letter_read', 'Surat Anda telah dibaca', 'Surat Anda: surat bansos telah dibaca oleh staff', 14, NULL, 0, '2026-01-04 03:40:07', NULL),
(29, 3, 'reply', 'Surat Anda dibalas', 'Balasan baru untuk surat: surat bansos', 14, 7, 0, '2026-01-04 03:44:03', NULL),
(30, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: OKE', 15, NULL, 0, '2026-01-05 05:24:59', NULL),
(31, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: OKE', 15, NULL, 0, '2026-01-05 05:24:59', NULL),
(32, 3, 'letter_read', 'Surat Anda telah dibaca', 'Surat Anda: OKE telah dibaca oleh staff', 15, NULL, 0, '2026-01-05 23:19:25', NULL),
(33, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: 1212', 16, NULL, 0, '2026-01-06 00:17:43', NULL),
(34, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: 1212', 16, NULL, 0, '2026-01-06 00:17:43', NULL),
(35, 3, 'letter_read', 'Surat Anda telah dibaca', 'Surat Anda: 1212 telah dibaca oleh staff', 16, NULL, 0, '2026-01-06 00:17:48', NULL),
(36, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: TES 12212', 17, NULL, 0, '2026-01-06 01:27:27', NULL),
(37, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: TES 12212', 17, NULL, 0, '2026-01-06 01:27:27', NULL),
(38, 3, 'letter_read', 'Surat Anda telah dibaca', 'Surat Anda: TES 12212 telah dibaca oleh staff', 17, NULL, 0, '2026-01-06 01:27:33', NULL),
(39, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: tes 1212', 18, NULL, 0, '2026-01-06 01:52:47', NULL),
(40, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: tes 1212', 18, NULL, 0, '2026-01-06 01:52:47', NULL),
(41, 3, 'letter_read', 'Surat Anda telah dibaca', 'Surat Anda: tes 1212 telah dibaca oleh staff', 18, NULL, 0, '2026-01-06 01:52:53', NULL),
(42, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: WEQA', 19, NULL, 0, '2026-01-06 02:01:59', NULL),
(43, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: WEQA', 19, NULL, 0, '2026-01-06 02:01:59', NULL),
(44, 3, 'letter_read', 'Surat Anda telah dibaca', 'Surat Anda: WEQA telah dibaca oleh staff', 19, NULL, 0, '2026-01-06 02:02:10', NULL),
(45, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: TES NOTIF', 21, NULL, 0, '2026-01-08 12:18:51', NULL),
(46, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: TES NOTIF', 21, NULL, 0, '2026-01-08 12:18:58', NULL),
(47, 3, 'letter_read', 'Surat Anda telah dibaca', 'Surat Anda: TES NOTIF telah dibaca oleh staff', 21, NULL, 0, '2026-01-08 12:19:53', NULL),
(48, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: TES NOTIF', 22, NULL, 0, '2026-01-08 12:45:31', NULL),
(49, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: TES NOTIF', 22, NULL, 0, '2026-01-08 12:45:31', NULL),
(50, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: TES NOTIF 1', 23, NULL, 0, '2026-01-08 12:56:39', NULL),
(51, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari user: TES NOTIF 1', 23, NULL, 0, '2026-01-08 12:56:39', NULL),
(52, 3, 'letter_read', 'Surat Anda telah dibaca', 'Surat Anda: TES NOTIF 1 telah dibaca oleh staff', 23, NULL, 0, '2026-01-08 12:57:20', NULL),
(53, 2, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari Warga Desa Padang Loang', 24, NULL, 0, '2026-01-08 13:02:28', NULL),
(54, 5, 'new_letter', 'Surat Baru Masuk', 'Surat baru dari Warga Desa Padang Loang', 24, NULL, 0, '2026-01-08 13:02:28', NULL),
(55, 3, 'reply', 'Surat Anda dibalas', 'Balasan baru dari Staf Desa Padang Loang untuk surat: TES NOTIF 1', 23, NULL, 0, '2026-01-08 13:03:24', NULL),
(56, 3, 'letter_read', 'Surat Anda telah dibaca', 'Surat Anda: TES NOTIF telah dibaca oleh Staf Desa Padang Loang', 22, NULL, 0, '2026-01-08 13:05:09', NULL),
(57, 3, 'reply', 'Surat Anda dibalas', 'Balasan baru dari Staf Desa Padang Loang untuk surat: TES NOTIF 1', 23, 10, 0, '2026-01-08 13:05:36', NULL),
(58, 3, 'reply', 'Surat Anda dibalas', 'Balasan baru dari Staf Desa Padang Loang untuk surat: TES NOTIF 1', 23, 11, 0, '2026-01-08 13:16:01', NULL),
(59, 3, 'reply', 'Surat Anda dibalas', 'Balasan baru dari Staf Desa Padang Loang untuk surat: TES NOTIF 1', 23, 12, 0, '2026-01-08 13:42:42', NULL),
(60, 3, 'reply', 'Surat Anda dibalas', 'Balasan baru dari Staf Desa Padang Loang untuk surat: TES NOTIF 1', 23, 13, 0, '2026-01-08 13:44:03', NULL),
(61, 3, 'reply', 'Surat Anda dibalas', 'Balasan baru dari Staf Desa Padang Loang untuk surat: TES NOTIF 1', 23, 14, 0, '2026-01-08 13:50:33', NULL),
(62, 3, 'reply', 'Surat Anda dibalas', 'Balasan baru dari Staf Desa Padang Loang untuk surat: TES NOTIF 1', 23, 15, 0, '2026-01-08 13:56:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `perangkat_desa`
--

CREATE TABLE `perangkat_desa` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) NOT NULL,
  `kontak` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perangkat_desa`
--

INSERT INTO `perangkat_desa` (`id`, `nama`, `foto`, `jabatan`, `kontak`, `created_at`, `updated_at`) VALUES
(1, 'Alif', 'uploads/perangkat_desa/1767397678_18cf895b803a893bfe02.webp', 'Kepala Desa', '081234567890', '2026-01-02 10:21:52', '2026-01-02 23:47:58'),
(3, 'AGIL', 'uploads/perangkat_desa/1767498632_843bdebba9901730186e.webp', 'Kepala Desa', '131212', '2026-01-04 03:50:33', '2026-01-04 03:50:33');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(200) NOT NULL,
  `tanggal_waktu` datetime NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `deskripsi` longtext NOT NULL,
  `anggaran` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('Perencanaan','Proses','Ditunda','Selesai') NOT NULL DEFAULT 'Perencanaan',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `judul`, `tanggal_waktu`, `thumbnail`, `deskripsi`, `anggaran`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Pembangunan Jembatan 1', '2026-01-01 10:13:00', 'uploads/projects/1767233689_84299b7c88fa3508d887.jpg', 'mohon doanya', 36000000.00, 'Proses', 2, '2026-01-01 10:14:49', '2026-01-01 10:15:28'),
(2, 'TES 2', '2026-01-03 11:09:00', 'uploads/projects/1767409783_93ba478e0f8424df7111.jpg', 'WWEA', 12313213.00, 'Perencanaan', 2, '2026-01-03 11:09:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_media`
--

CREATE TABLE `project_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `media_type` enum('foto','video_link') NOT NULL DEFAULT 'foto',
  `media_path` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_media`
--

INSERT INTO `project_media` (`id`, `project_id`, `media_type`, `media_path`, `created_at`) VALUES
(1, 1, 'foto', 'uploads/projects/1767233689_96c955efbb86d41acb72.jpg', '2026-01-01 10:14:49'),
(2, 1, 'video_link', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ&list=RDdQw4w9WgXcQ&start_radio=1', '2026-01-01 10:51:19'),
(3, 2, 'foto', 'uploads/projects/1767409783_f25e98a36ab80e4b25e1.jpg', '2026-01-03 11:09:43'),
(4, 2, 'foto', 'uploads/projects/1767409783_7ffe197a95e0765afa7a.jpg', '2026-01-03 11:09:43');

-- --------------------------------------------------------

--
-- Table structure for table `reply_attachments`
--

CREATE TABLE `reply_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reply_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reply_attachments`
--

INSERT INTO `reply_attachments` (`id`, `reply_id`, `file_path`, `original_name`, `mime_type`, `file_size`, `uploaded_at`) VALUES
(3, 7, 'uploads/replies/1767498243_2725612dadcf58f3aa5f.pdf', 'Rekap_Laporan_Disiplin_Hakim_Januari_2026 (1).pdf', 'application/pdf', 134332, '2026-01-04 11:44:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','staf','user') NOT NULL DEFAULT 'user',
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `status`, `is_verified`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@padangloang.id', '$2a$12$ab76mupW2LsWiBMzpKaL8e/PKm844v173kNHdRupbs0V3IhM2cZya', 'admin', 'aktif', 1, '2026-01-01 08:19:16', '2026-01-01 10:25:29'),
(2, 'staf', 'alifqadry14@gmail.com', '$2y$10$KaKFT2HhnSMkCeBy91TffepzXyiDtvlXWsvLimqn/SP9LmJdsU1PC', 'staf', 'aktif', 1, '2026-01-01 08:19:16', '2026-01-08 20:16:23'),
(3, 'user', 'alifqadry@gmail.com', '$2a$12$ab76mupW2LsWiBMzpKaL8e/PKm844v173kNHdRupbs0V3IhM2cZya', 'user', 'aktif', 1, '2026-01-01 08:19:16', '2026-01-08 20:21:05'),
(5, 'staf2', 'admin1@padangloang.id', '$2y$10$FE5AjPwUzXIps3eh0VOKyu.g0g.UfC4z9ZUldwMCpJXyI5xf/LzBm', 'staf', 'aktif', 1, '2026-01-04 07:38:31', NULL),
(15, 'alif1', 'alifqadry10@gmail.com', '$2y$10$e79X/73rp6tu6rP8TPDlgOXgwzty6vE0t4a3AXrlWI9qfTimyJkj2', 'user', 'aktif', 1, '2026-01-06 22:31:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `nama_lengkap` varchar(120) DEFAULT NULL,
  `tempat_lahir` varchar(80) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `agama` varchar(30) DEFAULT NULL,
  `pekerjaan` varchar(80) DEFAULT NULL,
  `nik` varchar(30) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`user_id`, `foto_profil`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `agama`, `pekerjaan`, `nik`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'uploads/profile/1767354286_24d853bcbd2a9bda623b.webp', 'Administrator Desa Padang Loang', '', '0000-00-00', '', 'Administrator Sistem', NULL, 'Kantor Desa Padang Loang', '2026-01-01 08:19:16', '2026-01-02 19:44:46'),
(2, 'uploads/profile/1767354100_56f0958294bf51c88add.webp', 'Staf Desa Padang Loang', '', '0000-00-00', '', 'Staf Pelayanan Desa', NULL, 'Kantor Desa Padang Loang', '2026-01-01 08:19:16', '2026-01-02 19:44:21'),
(3, 'uploads/profile/1767354250_48bd6e2d10de82dc21ce.webp', 'Warga Desa Padang Loang', '', '2026-01-05', '', 'Masyarakat', NULL, 'Desa Padang Loang, Kecamatan Ujung Loe', '2026-01-01 08:19:16', '2026-01-05 20:58:54'),
(5, NULL, 'STAFF 2', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-04 07:38:31', NULL),
(15, NULL, 'alif1', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-06 22:31:25', NULL);

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
-- Indexes for table `perangkat_desa`
--
ALTER TABLE `perangkat_desa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_project_datetime` (`tanggal_waktu`),
  ADD KEY `idx_project_status` (`status`),
  ADD KEY `fk_projects_created_by` (`created_by`);

--
-- Indexes for table `project_media`
--
ALTER TABLE `project_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_project_media_project` (`project_id`);

--
-- Indexes for table `reply_attachments`
--
ALTER TABLE `reply_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reply_attach_reply` (`reply_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_users_username` (`username`),
  ADD UNIQUE KEY `uk_users_email` (`email`),
  ADD KEY `idx_users_role` (`role`),
  ADD KEY `idx_users_status` (`status`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `email_queue`
--
ALTER TABLE `email_queue`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `gallery_albums`
--
ALTER TABLE `gallery_albums`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `gallery_media`
--
ALTER TABLE `gallery_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `letters`
--
ALTER TABLE `letters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `letter_attachments`
--
ALTER TABLE `letter_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `letter_replies`
--
ALTER TABLE `letter_replies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `news_media`
--
ALTER TABLE `news_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `perangkat_desa`
--
ALTER TABLE `perangkat_desa`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `project_media`
--
ALTER TABLE `project_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reply_attachments`
--
ALTER TABLE `reply_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `fk_projects_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `project_media`
--
ALTER TABLE `project_media`
  ADD CONSTRAINT `fk_project_media_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
