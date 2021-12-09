-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 08, 2021 at 07:52 PM
-- Server version: 5.7.24
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
(4, '2018_08_08_100000_create_telescope_entries_table', 1),
(5, '2019_08_19_000000_create_failed_jobs_table', 1),
(6, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(7, '2021_12_03_061558_create_vehicles_table', 1),
(8, '2021_12_04_124304_create_vehicle_metas_table', 1),
(9, '2021_12_08_020228_create_locations_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `telescope_entries`
--

CREATE TABLE `telescope_entries` (
  `sequence` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `telescope_entries`
--

INSERT INTO `telescope_entries` (`sequence`, `uuid`, `batch_id`, `family_hash`, `should_display_on_index`, `type`, `content`, `created_at`) VALUES
(1, '9510c770-7021-40c9-b761-dff71141465a', '9510c770-8354-460d-bfe4-60516b354594', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"insert into `users` (`name`, `email`, `email_verified_at`, `password`, `role`, `updated_at`, `created_at`) values (\'Hamza Siddique\', \'6793siddique@gmail.com\', \'2021-12-08 14:49:40\', \'yIXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\', \'admin\', \'2021-12-08 14:49:40\', \'2021-12-08 14:49:40\')\",\"time\":\"5.11\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\database\\\\seeders\\\\DatabaseSeeder.php\",\"line\":23,\"hash\":\"dcbb78869a4f71da604b3bc23096381b\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:49:40'),
(2, '9510c770-729d-4e28-9897-bd3bf81a9e35', '9510c770-8354-460d-bfe4-60516b354594', NULL, 1, 'model', '{\"action\":\"created\",\"model\":\"App\\\\Models\\\\User:1\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:49:40'),
(3, '9510c770-7d30-4a3a-a444-da5f9ee0baf0', '9510c770-8354-460d-bfe4-60516b354594', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `updated_at`, `created_at`) values (\'Vida Aufderhar\', \'rrussel@example.org\', \'2021-12-08 14:49:40\', \'yIXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\', \'Vb4DJ0MvgR\', \'2021-12-08 14:49:40\', \'2021-12-08 14:49:40\')\",\"time\":\"1.81\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\database\\\\seeders\\\\DatabaseSeeder.php\",\"line\":26,\"hash\":\"2e79e0213d893e12ec0e51fb5ba5514a\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:49:40'),
(4, '9510c770-7d7e-4a29-a1e5-bb845673dbba', '9510c770-8354-460d-bfe4-60516b354594', NULL, 1, 'model', '{\"action\":\"created\",\"model\":\"App\\\\Models\\\\User:2\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:49:40'),
(5, '9510c770-7eaa-42fc-a160-92d1c75953c2', '9510c770-8354-460d-bfe4-60516b354594', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `updated_at`, `created_at`) values (\'Ms. Alverta Wisoky\', \'dauer@example.net\', \'2021-12-08 14:49:40\', \'yIXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\', \'fUo7RoJv2i\', \'2021-12-08 14:49:40\', \'2021-12-08 14:49:40\')\",\"time\":\"1.67\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\database\\\\seeders\\\\DatabaseSeeder.php\",\"line\":26,\"hash\":\"2e79e0213d893e12ec0e51fb5ba5514a\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:49:40'),
(6, '9510c770-7eeb-4ea8-a8c3-7ecd35ea20ea', '9510c770-8354-460d-bfe4-60516b354594', NULL, 1, 'model', '{\"action\":\"created\",\"model\":\"App\\\\Models\\\\User:3\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:49:40'),
(7, '9510c770-8023-48f6-9f29-aa316a7763ad', '9510c770-8354-460d-bfe4-60516b354594', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `updated_at`, `created_at`) values (\'Melissa Willms\', \'bins.marquis@example.com\', \'2021-12-08 14:49:40\', \'yIXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\', \'PG6KfBBGFR\', \'2021-12-08 14:49:40\', \'2021-12-08 14:49:40\')\",\"time\":\"1.90\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\database\\\\seeders\\\\DatabaseSeeder.php\",\"line\":26,\"hash\":\"2e79e0213d893e12ec0e51fb5ba5514a\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:49:40'),
(8, '9510c770-8059-4a79-acd5-0e2a6a7a98ba', '9510c770-8354-460d-bfe4-60516b354594', NULL, 1, 'model', '{\"action\":\"created\",\"model\":\"App\\\\Models\\\\User:4\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:49:40'),
(9, '9510c770-8132-4a85-979e-ca642c4b6e11', '9510c770-8354-460d-bfe4-60516b354594', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `updated_at`, `created_at`) values (\'Rudy Ondricka\', \'bailey.abbie@example.com\', \'2021-12-08 14:49:40\', \'yIXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\', \'pRObO6FYEN\', \'2021-12-08 14:49:40\', \'2021-12-08 14:49:40\')\",\"time\":\"1.11\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\database\\\\seeders\\\\DatabaseSeeder.php\",\"line\":26,\"hash\":\"2e79e0213d893e12ec0e51fb5ba5514a\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:49:40'),
(10, '9510c770-8178-499e-bd24-2463f0a43183', '9510c770-8354-460d-bfe4-60516b354594', NULL, 1, 'model', '{\"action\":\"created\",\"model\":\"App\\\\Models\\\\User:5\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:49:40'),
(11, '9510c770-826d-4454-bfa8-3d437d559525', '9510c770-8354-460d-bfe4-60516b354594', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `updated_at`, `created_at`) values (\'Joshuah Corkery\', \'ksipes@example.org\', \'2021-12-08 14:49:40\', \'yIXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\', \'58tNxZdDjt\', \'2021-12-08 14:49:40\', \'2021-12-08 14:49:40\')\",\"time\":\"1.44\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\database\\\\seeders\\\\DatabaseSeeder.php\",\"line\":26,\"hash\":\"2e79e0213d893e12ec0e51fb5ba5514a\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:49:40'),
(12, '9510c770-82a6-4a5a-bca1-2ebdfa57c126', '9510c770-8354-460d-bfe4-60516b354594', NULL, 1, 'model', '{\"action\":\"created\",\"model\":\"App\\\\Models\\\\User:6\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:49:40'),
(13, '9510c770-832d-4a82-b0a6-f87ef8845e68', '9510c770-8354-460d-bfe4-60516b354594', NULL, 1, 'command', '{\"command\":\"db:seed\",\"exit_code\":0,\"arguments\":{\"command\":\"db:seed\",\"class\":null},\"options\":{\"class\":\"Database\\\\Seeders\\\\DatabaseSeeder\",\"database\":null,\"force\":false,\"help\":false,\"quiet\":false,\"verbose\":false,\"version\":false,\"ansi\":null,\"no-interaction\":false,\"env\":null},\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:49:40'),
(14, '9510c77c-3f8c-43f0-95ad-780069230cbd', '9510c77c-4515-4b03-a534-11e9ff196c26', NULL, 1, 'debugbar', '{\"requestId\":\"Xb70b1ac9f2db8040cd2ba869c74bbac3\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:49:48'),
(15, '9510c77c-4364-487d-9c6e-9dc55dbdd819', '9510c77c-4515-4b03-a534-11e9ff196c26', NULL, 1, 'request', '{\"ip_address\":\"127.0.0.1\",\"uri\":\"\\/\",\"method\":\"GET\",\"controller_action\":\"\\\\Illuminate\\\\Routing\\\\RedirectController\",\"middleware\":[\"web\"],\"headers\":{\"host\":\"127.0.0.1:8000\",\"connection\":\"keep-alive\",\"sec-ch-ua\":\"\\\" Not A;Brand\\\";v=\\\"99\\\", \\\"Chromium\\\";v=\\\"96\\\", \\\"Google Chrome\\\";v=\\\"96\\\"\",\"sec-ch-ua-mobile\":\"?0\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"upgrade-insecure-requests\":\"1\",\"user-agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/96.0.4664.45 Safari\\/537.36\",\"accept\":\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.9\",\"sec-fetch-site\":\"none\",\"sec-fetch-mode\":\"navigate\",\"sec-fetch-user\":\"?1\",\"sec-fetch-dest\":\"document\",\"accept-encoding\":\"gzip, deflate, br\",\"accept-language\":\"en-US,en;q=0.9,ur;q=0.8,la;q=0.7,de;q=0.6\",\"cookie\":\"remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d=eyJpdiI6ImlkYTZ2TG43N3lVUWVWUmlzMUlyOFE9PSIsInZhbHVlIjoiMXNiVXh4aXNmTlNRaVM4Z2hYL3E3QmJuTGFFTDFWUVRtd3VQdzNuVU9aNU8vVy9EZFd1bjB6VnFTWGxqTU1jNTZ3UjBBWTlOZEZMWTd4T3RrbGI1VUNINDYrUzVFNHZaMHBIMmRXUXNCa2dpV3UxQmNSeFJMMHRSY3Q2ZTQvVGtpcE80VkZrOGdXejA0blQwQzJrK1JKQ3BIZUlxN3VnaUtuWG5IRDA0ZldjPSIsIm1hYyI6IjAwMWQzNjI2OGM2Mjc1ZGM0ZjNkZjY3Yjk2ZTZmZjQ2M2Y1ZjU0ZjM4ZDc3OWNhNWU4NTkyOTlmNjQ4MDM4NjAiLCJ0YWciOiIifQ%3D%3D; remember_web_3dc7a913ef5fd4b890ecabe3487085573e16cf82=eyJpdiI6IjRNaklYOE1rSW9sbEhSZ3ZFbTNmMFE9PSIsInZhbHVlIjoiODJZdWtFZ0lxY1FqZ3JVZjRiMzh5TFAxMW93RW1QajZ6YWJIRzBpemJIM1paUUd2NXVmcDc4S0p2TjBIRENycGNjOFJzd01XN2liUGhOcUI0dG1wZ0I5SU9CV0FMcmhLYnRYTFN2S0hFUzYyTUZ1MHdwZmw3RzRlZzh6SGtjNEMvMG92bWg5Z3FmZFBWK3FwbU1EKzhxczNGQ0tLdDN3NnRMUGFYUitxaXprdlY4eFVwcVo0anM0RnBwaDh2Wk9ER21aTnRFQ3FmamN4UHRrRHNQOFlGOXYwaTlNR2ZxcG91bERCNy9IOTFKYz0iLCJtYWMiOiIxOTJmMTRhMjU5YTY0YTlhMzIwNTcxYzEyZTE2ZmUyNDZjM2NmMDllMzlhN2Y4ZTRlODkwYWE2MDcwZjc2ZTIwIiwidGFnIjoiIn0%3D; orderfillers_session=eyJpdiI6Ino5aFErWmZISC9Ob05zOHQyZVlkZHc9PSIsInZhbHVlIjoieFNMeDdBMnd4aEZCUzBxcnBPNG5STzVwNVpMejd0UVZKMWlxdjVndm91ZmZ0VE1STTZCUEE5U0hEQ0ZKc0J4VGFveGYvaVVCUjRnaVJzM3RicXFPMVd3WXg1c0MrTWt5cU1MZFV0ZUZYT3k0eUlYcFZsU0JBV2o1VzU5cGg0VXciLCJtYWMiOiI0ODFkNmUyMDEwNzhhZTcxMGZhYmUxMmYyMmZjOGM4YzcwNGFkZTRiN2Q4ZDAxNmRlN2UwMjY5OTM5NGY2YmM2IiwidGFnIjoiIn0%3D; mp_52e5e0805583e8a410f1ed50d8e0c049_mixpanel=%7B%22distinct_id%22%3A%20%2217d7ce1e73c1ce-009b8c82ab5592-978183a-144000-17d7ce1e73d10c%22%2C%22%24device_id%22%3A%20%2217d7ce1e73c1ce-009b8c82ab5592-978183a-144000-17d7ce1e73d10c%22%2C%22%24initial_referrer%22%3A%20%22%24direct%22%2C%22%24initial_referring_domain%22%3A%20%22%24direct%22%7D; XSRF-TOKEN=eyJpdiI6Imp6bkp6SEJoYVFpMHRBbGx6RHpualE9PSIsInZhbHVlIjoiRXpYYnJ5WWQ1QVMxZmkwZmNBZnQwbGQ3Y3E5RG1XSXdnMHd4YTJ5akpTNGFpY2plbjd1Yy9tNE1XaExsc0VTOXhmazViU2o5aWR0YkpRMUd3c0ZSNk1udTFmcW9zQ2pyVUJPdTMxR0Jra1dJS0FMNTZiVzhNVHJmUXNKWnRzeE0iLCJtYWMiOiJmZjEwMjJkNDg4NTEyZjJlOGU4NzhmZTVkZThjNTFmMTU5NTgzM2JkNTM5ZTBlZWIwNDA0ODVjZDYzZTA0YzJjIiwidGFnIjoiIn0%3D; tracker_session=eyJpdiI6IlVlQ3owOTkybjIycVU2eERabldsT1E9PSIsInZhbHVlIjoicHlwMGRsYncrWGxTTUtTYVN4Z0NQQmE0aHVXbkVUSkY5eDRVMERJeDFkU29ubllaVm91NkMrdzlHNXJSdG5TcHlqYmRXQUlCQTg0SzQ0c09ZdU9qV3ZoeFBGRFRpc2MxNzh5YVVVY1ZSMHVvc1hpT1Fqb1FOMU8vdVBKSU0vZFQiLCJtYWMiOiI0OGQ1Zjg0YzM3NmZiYTk0NzMyZTJiZDZkZWVlNGI1YzFjMjA1OTRiOTk3ZWRlMWVjMTQyYjdjYjJkNjc4MTUxIiwidGFnIjoiIn0%3D\"},\"payload\":[],\"session\":{\"_token\":\"qvGmLGRSl6shjfCHnMz6ReocreoDyVa8MzzhLPjD\",\"login_web_3dc7a913ef5fd4b890ecabe3487085573e16cf82\":1,\"_previous\":{\"url\":\"http:\\/\\/127.0.0.1:8000\"},\"_flash\":{\"old\":[],\"new\":[]},\"PHPDEBUGBAR_STACK_DATA\":{\"Xb70b1ac9f2db8040cd2ba869c74bbac3\":null}},\"response_status\":302,\"response\":\"Redirected to \\/dashboard\",\"duration\":397,\"memory\":6,\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:49:48'),
(16, '9510c77d-2b0e-489b-b562-44b42ff17d56', '9510c77d-4690-4721-b651-08af716fe386', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select * from `users` where `id` = 1 limit 1\",\"time\":\"1.90\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\public\\\\index.php\",\"line\":52,\"hash\":\"082e27d9c4fc4a40315cae2c646c0509\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:49:48'),
(17, '9510c77d-2f57-40cb-9062-e89e0a4619fd', '9510c77d-4690-4721-b651-08af716fe386', NULL, 1, 'model', '{\"action\":\"retrieved\",\"model\":\"App\\\\Models\\\\User\",\"count\":1,\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:49:48'),
(18, '9510c77d-327a-48b8-9d4b-85f32856d8ff', '9510c77d-4690-4721-b651-08af716fe386', NULL, 1, 'view', '{\"name\":\"pages.dashboard.index\",\"path\":\"\\\\resources\\\\views\\/pages\\/dashboard\\/index.blade.php\",\"data\":[],\"composers\":[{\"name\":\"Closure at F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\vendor\\\\barryvdh\\\\laravel-debugbar\\\\src\\\\LaravelDebugbar.php[210:215]\",\"type\":\"composer\"}],\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":1,\"name\":\"Hamza Siddique\",\"email\":\"6793siddique@gmail.com\"}}', '2021-12-08 14:49:48'),
(19, '9510c77d-330c-4805-a6e8-5a67ab2daa3a', '9510c77d-4690-4721-b651-08af716fe386', NULL, 1, 'view', '{\"name\":\"pages.dashboard._inc.stats\",\"path\":\"\\\\resources\\\\views\\/pages\\/dashboard\\/_inc\\/stats.blade.php\",\"data\":[],\"composers\":[{\"name\":\"Closure at F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\vendor\\\\barryvdh\\\\laravel-debugbar\\\\src\\\\LaravelDebugbar.php[210:215]\",\"type\":\"composer\"}],\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":1,\"name\":\"Hamza Siddique\",\"email\":\"6793siddique@gmail.com\"}}', '2021-12-08 14:49:48'),
(20, '9510c77d-34a0-4ed0-a225-32047f529e21', '9510c77d-4690-4721-b651-08af716fe386', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select count(*) as aggregate from `users`\",\"time\":\"1.08\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\storage\\\\framework\\\\views\\\\bae298913fb23d7de12d50969a4f0f97d90b6d66.php\",\"line\":33,\"hash\":\"6c5274cfac96d79f6367317dfb756038\",\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":1,\"name\":\"Hamza Siddique\",\"email\":\"6793siddique@gmail.com\"}}', '2021-12-08 14:49:48'),
(21, '9510c77d-362c-4970-ae49-5a916e39eaf0', '9510c77d-4690-4721-b651-08af716fe386', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select count(*) as aggregate from `users` where `role` = \'user\'\",\"time\":\"0.72\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\storage\\\\framework\\\\views\\\\bae298913fb23d7de12d50969a4f0f97d90b6d66.php\",\"line\":38,\"hash\":\"c3160bda8c62f883a1e8ed6f23166c6b\",\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":1,\"name\":\"Hamza Siddique\",\"email\":\"6793siddique@gmail.com\"}}', '2021-12-08 14:49:48'),
(22, '9510c77d-3801-4719-b949-e4e194b96f95', '9510c77d-4690-4721-b651-08af716fe386', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select count(*) as aggregate from `users` where `role` = \'assistant\'\",\"time\":\"1.03\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\storage\\\\framework\\\\views\\\\bae298913fb23d7de12d50969a4f0f97d90b6d66.php\",\"line\":42,\"hash\":\"c3160bda8c62f883a1e8ed6f23166c6b\",\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":1,\"name\":\"Hamza Siddique\",\"email\":\"6793siddique@gmail.com\"}}', '2021-12-08 14:49:48'),
(23, '9510c77d-3961-414a-903a-7527082f4e6a', '9510c77d-4690-4721-b651-08af716fe386', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select count(*) as aggregate from `users` where `role` = \'admin\'\",\"time\":\"0.65\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\storage\\\\framework\\\\views\\\\bae298913fb23d7de12d50969a4f0f97d90b6d66.php\",\"line\":47,\"hash\":\"c3160bda8c62f883a1e8ed6f23166c6b\",\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":1,\"name\":\"Hamza Siddique\",\"email\":\"6793siddique@gmail.com\"}}', '2021-12-08 14:49:48'),
(24, '9510c77d-3a48-43d8-af0c-faf06f4e8dbc', '9510c77d-4690-4721-b651-08af716fe386', NULL, 1, 'view', '{\"name\":\"layouts.app\",\"path\":\"\\\\resources\\\\views\\/layouts\\/app.blade.php\",\"data\":[],\"composers\":[{\"name\":\"Closure at F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\vendor\\\\barryvdh\\\\laravel-debugbar\\\\src\\\\LaravelDebugbar.php[210:215]\",\"type\":\"composer\"}],\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":1,\"name\":\"Hamza Siddique\",\"email\":\"6793siddique@gmail.com\"}}', '2021-12-08 14:49:48'),
(25, '9510c77d-3ae6-41fe-aad9-9c10be7920b6', '9510c77d-4690-4721-b651-08af716fe386', NULL, 1, 'view', '{\"name\":\"includes.aside\",\"path\":\"\\\\resources\\\\views\\/includes\\/aside.blade.php\",\"data\":[],\"composers\":[{\"name\":\"Closure at F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\vendor\\\\barryvdh\\\\laravel-debugbar\\\\src\\\\LaravelDebugbar.php[210:215]\",\"type\":\"composer\"}],\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":1,\"name\":\"Hamza Siddique\",\"email\":\"6793siddique@gmail.com\"}}', '2021-12-08 14:49:48'),
(26, '9510c77d-3bd8-4041-a6f1-3ee4793af355', '9510c77d-4690-4721-b651-08af716fe386', NULL, 1, 'view', '{\"name\":\"includes.header\",\"path\":\"\\\\resources\\\\views\\/includes\\/header.blade.php\",\"data\":[],\"composers\":[{\"name\":\"Closure at F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\vendor\\\\barryvdh\\\\laravel-debugbar\\\\src\\\\LaravelDebugbar.php[210:215]\",\"type\":\"composer\"}],\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":1,\"name\":\"Hamza Siddique\",\"email\":\"6793siddique@gmail.com\"}}', '2021-12-08 14:49:48'),
(27, '9510c77d-3cd4-4ff9-8ca5-694b84393171', '9510c77d-4690-4721-b651-08af716fe386', NULL, 1, 'view', '{\"name\":\"includes.footer\",\"path\":\"\\\\resources\\\\views\\/includes\\/footer.blade.php\",\"data\":[],\"composers\":[{\"name\":\"Closure at F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\vendor\\\\barryvdh\\\\laravel-debugbar\\\\src\\\\LaravelDebugbar.php[210:215]\",\"type\":\"composer\"}],\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":1,\"name\":\"Hamza Siddique\",\"email\":\"6793siddique@gmail.com\"}}', '2021-12-08 14:49:48'),
(28, '9510c77d-4324-4e68-b31b-1e6e5c1eb7e1', '9510c77d-4690-4721-b651-08af716fe386', NULL, 1, 'debugbar', '{\"requestId\":\"X058e1e7b4debf7bdd2b24373b5a7bfbd\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:49:48'),
(29, '9510c77d-460d-4e60-b57c-9147447cbcd5', '9510c77d-4690-4721-b651-08af716fe386', NULL, 1, 'request', '{\"ip_address\":\"127.0.0.1\",\"uri\":\"\\/dashboard\",\"method\":\"GET\",\"controller_action\":\"App\\\\Http\\\\Controllers\\\\DashboardController@index\",\"middleware\":[\"web\",\"auth\"],\"headers\":{\"host\":\"127.0.0.1:8000\",\"connection\":\"keep-alive\",\"upgrade-insecure-requests\":\"1\",\"user-agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/96.0.4664.45 Safari\\/537.36\",\"accept\":\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.9\",\"sec-fetch-site\":\"none\",\"sec-fetch-mode\":\"navigate\",\"sec-fetch-user\":\"?1\",\"sec-fetch-dest\":\"document\",\"sec-ch-ua\":\"\\\" Not A;Brand\\\";v=\\\"99\\\", \\\"Chromium\\\";v=\\\"96\\\", \\\"Google Chrome\\\";v=\\\"96\\\"\",\"sec-ch-ua-mobile\":\"?0\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"accept-encoding\":\"gzip, deflate, br\",\"accept-language\":\"en-US,en;q=0.9,ur;q=0.8,la;q=0.7,de;q=0.6\",\"cookie\":\"remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d=eyJpdiI6ImlkYTZ2TG43N3lVUWVWUmlzMUlyOFE9PSIsInZhbHVlIjoiMXNiVXh4aXNmTlNRaVM4Z2hYL3E3QmJuTGFFTDFWUVRtd3VQdzNuVU9aNU8vVy9EZFd1bjB6VnFTWGxqTU1jNTZ3UjBBWTlOZEZMWTd4T3RrbGI1VUNINDYrUzVFNHZaMHBIMmRXUXNCa2dpV3UxQmNSeFJMMHRSY3Q2ZTQvVGtpcE80VkZrOGdXejA0blQwQzJrK1JKQ3BIZUlxN3VnaUtuWG5IRDA0ZldjPSIsIm1hYyI6IjAwMWQzNjI2OGM2Mjc1ZGM0ZjNkZjY3Yjk2ZTZmZjQ2M2Y1ZjU0ZjM4ZDc3OWNhNWU4NTkyOTlmNjQ4MDM4NjAiLCJ0YWciOiIifQ%3D%3D; remember_web_3dc7a913ef5fd4b890ecabe3487085573e16cf82=eyJpdiI6IjRNaklYOE1rSW9sbEhSZ3ZFbTNmMFE9PSIsInZhbHVlIjoiODJZdWtFZ0lxY1FqZ3JVZjRiMzh5TFAxMW93RW1QajZ6YWJIRzBpemJIM1paUUd2NXVmcDc4S0p2TjBIRENycGNjOFJzd01XN2liUGhOcUI0dG1wZ0I5SU9CV0FMcmhLYnRYTFN2S0hFUzYyTUZ1MHdwZmw3RzRlZzh6SGtjNEMvMG92bWg5Z3FmZFBWK3FwbU1EKzhxczNGQ0tLdDN3NnRMUGFYUitxaXprdlY4eFVwcVo0anM0RnBwaDh2Wk9ER21aTnRFQ3FmamN4UHRrRHNQOFlGOXYwaTlNR2ZxcG91bERCNy9IOTFKYz0iLCJtYWMiOiIxOTJmMTRhMjU5YTY0YTlhMzIwNTcxYzEyZTE2ZmUyNDZjM2NmMDllMzlhN2Y4ZTRlODkwYWE2MDcwZjc2ZTIwIiwidGFnIjoiIn0%3D; orderfillers_session=eyJpdiI6Ino5aFErWmZISC9Ob05zOHQyZVlkZHc9PSIsInZhbHVlIjoieFNMeDdBMnd4aEZCUzBxcnBPNG5STzVwNVpMejd0UVZKMWlxdjVndm91ZmZ0VE1STTZCUEE5U0hEQ0ZKc0J4VGFveGYvaVVCUjRnaVJzM3RicXFPMVd3WXg1c0MrTWt5cU1MZFV0ZUZYT3k0eUlYcFZsU0JBV2o1VzU5cGg0VXciLCJtYWMiOiI0ODFkNmUyMDEwNzhhZTcxMGZhYmUxMmYyMmZjOGM4YzcwNGFkZTRiN2Q4ZDAxNmRlN2UwMjY5OTM5NGY2YmM2IiwidGFnIjoiIn0%3D; mp_52e5e0805583e8a410f1ed50d8e0c049_mixpanel=%7B%22distinct_id%22%3A%20%2217d7ce1e73c1ce-009b8c82ab5592-978183a-144000-17d7ce1e73d10c%22%2C%22%24device_id%22%3A%20%2217d7ce1e73c1ce-009b8c82ab5592-978183a-144000-17d7ce1e73d10c%22%2C%22%24initial_referrer%22%3A%20%22%24direct%22%2C%22%24initial_referring_domain%22%3A%20%22%24direct%22%7D; XSRF-TOKEN=eyJpdiI6ImhNY0RVZHJ6Q0NJRnVDZTRoczk2R2c9PSIsInZhbHVlIjoib1J3Ky9DbGNnRnMyQjhGN1YzR1FmaFZ4U2NBSTlCQXFETnpRcW5OZXBQdWtFVHFIbytQZlVQbmx3TVoyZUR0WEJhK2k4WGJmbEcwdGZJVlZuM3daa3I3dVdmdGVwSEVXRHhRNnZYUHJTZzlPdllhR0RDNzJDeHptY1V0blRsMmEiLCJtYWMiOiJjZmE5MDVkZWZjODg2MzNmNTU1MDcyMzQ5YjM2ODVhYzE3Nzg2NTA4YmUzZmQ4ZGNhNGVjNjE4OGY3NGQ3ZDhjIiwidGFnIjoiIn0%3D; tracker_session=eyJpdiI6IjBjcWZyQnBYL3NKdWcyWnNGUHc5S3c9PSIsInZhbHVlIjoia2JpQ2NxamJaZENHMVlQMUZEN2ljS0RHWXZBKzRsRHJ2WDAyM1JhUnNXTTNHeTlseDVBdVRvYkkrSVFocGpVR0E4b0R6NFJIWDdDeHJWV3NDZytpVnQxQzZsNHRBSlRpQnNoYzNKVlZsOUxaT3NpN1VURXYwZmlkaldUa1NvaHMiLCJtYWMiOiJmOTMxNmE4NTk0YzlhZjViZmE2NjYzYjlmYmVhMDBmM2ZmM2JiZjRiNzVjOGYwNDBmZmQ0YTEzY2RmZWMyNTIzIiwidGFnIjoiIn0%3D\"},\"payload\":[],\"session\":{\"_token\":\"qvGmLGRSl6shjfCHnMz6ReocreoDyVa8MzzhLPjD\",\"login_web_3dc7a913ef5fd4b890ecabe3487085573e16cf82\":1,\"_previous\":{\"url\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\"},\"_flash\":{\"old\":[],\"new\":[]},\"PHPDEBUGBAR_STACK_DATA\":[]},\"response_status\":200,\"response\":{\"view\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\resources\\\\views\\/pages\\/dashboard\\/index.blade.php\",\"data\":[]},\"duration\":375,\"memory\":6,\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":1,\"name\":\"Hamza Siddique\",\"email\":\"6793siddique@gmail.com\"}}', '2021-12-08 14:49:48'),
(30, '9510c7ab-b820-4c4b-825c-343c92837db1', '9510c7ab-cfa3-4e5c-8707-481e17fbe8b3', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select * from `users` where `id` = 2 limit 1\",\"time\":\"2.31\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\public\\\\index.php\",\"line\":52,\"hash\":\"082e27d9c4fc4a40315cae2c646c0509\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:50:19'),
(31, '9510c7ab-bb6b-4c99-a193-5dd8022b0770', '9510c7ab-cfa3-4e5c-8707-481e17fbe8b3', NULL, 1, 'model', '{\"action\":\"retrieved\",\"model\":\"App\\\\Models\\\\User\",\"count\":1,\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:50:19'),
(32, '9510c7ab-beb2-4ded-8a89-18a4189ecfc2', '9510c7ab-cfa3-4e5c-8707-481e17fbe8b3', NULL, 1, 'view', '{\"name\":\"pages.dashboard.index\",\"path\":\"\\\\resources\\\\views\\/pages\\/dashboard\\/index.blade.php\",\"data\":[],\"composers\":[{\"name\":\"Closure at F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\vendor\\\\barryvdh\\\\laravel-debugbar\\\\src\\\\LaravelDebugbar.php[210:215]\",\"type\":\"composer\"}],\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:19'),
(33, '9510c7ab-bf46-4f1c-ac61-f707d538c2f1', '9510c7ab-cfa3-4e5c-8707-481e17fbe8b3', NULL, 1, 'view', '{\"name\":\"pages.dashboard._inc.stats\",\"path\":\"\\\\resources\\\\views\\/pages\\/dashboard\\/_inc\\/stats.blade.php\",\"data\":[],\"composers\":[{\"name\":\"Closure at F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\vendor\\\\barryvdh\\\\laravel-debugbar\\\\src\\\\LaravelDebugbar.php[210:215]\",\"type\":\"composer\"}],\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:19'),
(34, '9510c7ab-c05c-465c-81b8-495bc28edd8b', '9510c7ab-cfa3-4e5c-8707-481e17fbe8b3', NULL, 1, 'view', '{\"name\":\"layouts.app\",\"path\":\"\\\\resources\\\\views\\/layouts\\/app.blade.php\",\"data\":[],\"composers\":[{\"name\":\"Closure at F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\vendor\\\\barryvdh\\\\laravel-debugbar\\\\src\\\\LaravelDebugbar.php[210:215]\",\"type\":\"composer\"}],\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:19'),
(35, '9510c7ab-c0d4-43ef-9c48-96dc0c56571c', '9510c7ab-cfa3-4e5c-8707-481e17fbe8b3', NULL, 1, 'view', '{\"name\":\"includes.aside\",\"path\":\"\\\\resources\\\\views\\/includes\\/aside.blade.php\",\"data\":[],\"composers\":[{\"name\":\"Closure at F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\vendor\\\\barryvdh\\\\laravel-debugbar\\\\src\\\\LaravelDebugbar.php[210:215]\",\"type\":\"composer\"}],\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:19'),
(36, '9510c7ab-c18d-4a3d-8848-d33abeab2c2d', '9510c7ab-cfa3-4e5c-8707-481e17fbe8b3', NULL, 1, 'view', '{\"name\":\"includes.header\",\"path\":\"\\\\resources\\\\views\\/includes\\/header.blade.php\",\"data\":[],\"composers\":[{\"name\":\"Closure at F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\vendor\\\\barryvdh\\\\laravel-debugbar\\\\src\\\\LaravelDebugbar.php[210:215]\",\"type\":\"composer\"}],\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:19'),
(37, '9510c7ab-c256-4272-8e2d-106589a4f627', '9510c7ab-cfa3-4e5c-8707-481e17fbe8b3', NULL, 1, 'view', '{\"name\":\"includes.footer\",\"path\":\"\\\\resources\\\\views\\/includes\\/footer.blade.php\",\"data\":[],\"composers\":[{\"name\":\"Closure at F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\vendor\\\\barryvdh\\\\laravel-debugbar\\\\src\\\\LaravelDebugbar.php[210:215]\",\"type\":\"composer\"}],\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:19'),
(38, '9510c7ab-cc5a-46a5-977d-8fa10f7c1c5d', '9510c7ab-cfa3-4e5c-8707-481e17fbe8b3', NULL, 1, 'debugbar', '{\"requestId\":\"X97eaecb1152db49ed11f44c97779bda4\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:50:19'),
(39, '9510c7ab-cee9-4f6d-b2f3-7634e3bbbaeb', '9510c7ab-cfa3-4e5c-8707-481e17fbe8b3', NULL, 1, 'request', '{\"ip_address\":\"127.0.0.1\",\"uri\":\"\\/dashboard\",\"method\":\"GET\",\"controller_action\":\"App\\\\Http\\\\Controllers\\\\DashboardController@index\",\"middleware\":[\"web\",\"auth\"],\"headers\":{\"host\":\"127.0.0.1:8000\",\"connection\":\"keep-alive\",\"sec-ch-ua\":\"\\\" Not A;Brand\\\";v=\\\"99\\\", \\\"Chromium\\\";v=\\\"96\\\", \\\"Google Chrome\\\";v=\\\"96\\\"\",\"sec-ch-ua-mobile\":\"?0\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"upgrade-insecure-requests\":\"1\",\"user-agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/96.0.4664.45 Safari\\/537.36\",\"accept\":\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.9\",\"sec-fetch-site\":\"none\",\"sec-fetch-mode\":\"navigate\",\"sec-fetch-user\":\"?1\",\"sec-fetch-dest\":\"document\",\"accept-encoding\":\"gzip, deflate, br\",\"accept-language\":\"en-US,en;q=0.9\",\"cookie\":\"remember_web_3dc7a913ef5fd4b890ecabe3487085573e16cf82=eyJpdiI6IkhQbkluUnUxS1Z5WEdjMi83eEUyVlE9PSIsInZhbHVlIjoiVWVybWtFQjNPcDQvclNidzVDY1h0QndEbEpjbjdVTXd0Nm5lazJ1MnB3cnVhUmJqOXZaeXFYc3FUOXc0THNLck5KbVk1Q09JWU93U25vNkJzKzVOV0xMS2lLZmZUNXZieGRoSlRiRGUrLzMrREsrQkhJNEhIZzRXNnZmYnRnRWFZU0NaeUpFMkkvaDZVT3UyOE9YRSsxZWZEZjdrNE14VlUwZUxTaWJ3SVFZPSIsIm1hYyI6IjNmYzg5OTQ2M2ZiOGUwZjYwMGYyZmY2NTA0NDY1NTkyOTlmYzhlNjA1M2FiM2NjMzI5Mzc4MDM1NTZjNDhlZTQiLCJ0YWciOiIifQ%3D%3D; XSRF-TOKEN=eyJpdiI6IlUxMERlMHE0MU05L1NWYzVnMGJHK0E9PSIsInZhbHVlIjoiMmtlY1dMaDBycnBnQjVYckJMTE1ObnUzcnRFTk9MNmFGSEtIcjM3N1pJS0liVHA4bVYvV202cVdWS1VmUWxDYVBwN3JnbHJxOWtyeTBTR21ScDM1YkxjU1hzbzVZdldNK0FhZDhRQ2NZTWo2QmhOTkkzRWJmUzNaNDhTQUVrTVYiLCJtYWMiOiJmMTJlMjY1NDE0NmUwMzc2YzY1MmQ2OWEwODAxMTgwYmU4NDRlNGQyODU5OTY2YmM5MWQ4NWRjMjgwNWVjNDYzIiwidGFnIjoiIn0%3D; tracker_session=eyJpdiI6IjhJZDNNTWVGYWN6bXp6SmUvLzhYS1E9PSIsInZhbHVlIjoiUzZjZWZ6MzRmcGxna2I0NkluT3JpMjIzZHRvZklFakpVQ2YzWXIwYkZobm1lb0ppYy9TV0R0Y0lhTWppbHllVlpJcXRYVGMwYldvWTV4c3BWNEtYRnNkVXJ2MFJxcVB2a1VRZXpyV1htaXVlMHhvWDVML2VHUHp5ZU56NXFUT3giLCJtYWMiOiIyMmU1M2I0MTVmNGY5Nzg3N2RjYzA5ZTlhYWIxYmU1MDU2NDUyMzhjYTVmNzY5NDMyY2JhZjAzOTI0YzM5NjYwIiwidGFnIjoiIn0%3D\"},\"payload\":[],\"session\":{\"_token\":\"XAoDQJqSzn1BmiIEhPYv1Phv5Dj22fWGQ4BriCBp\",\"_previous\":{\"url\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\"},\"_flash\":{\"old\":[],\"new\":[]},\"url\":[],\"login_web_3dc7a913ef5fd4b890ecabe3487085573e16cf82\":2,\"PHPDEBUGBAR_STACK_DATA\":[]},\"response_status\":200,\"response\":{\"view\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\resources\\\\views\\/pages\\/dashboard\\/index.blade.php\",\"data\":[]},\"duration\":403,\"memory\":24,\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:19'),
(40, '9510c7b0-4458-470e-b19b-9f7ad3e33603', '9510c7b0-6247-4a3f-b59f-4211583bca26', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select * from `users` where `id` = 2 limit 1\",\"time\":\"3.86\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\public\\\\index.php\",\"line\":52,\"hash\":\"082e27d9c4fc4a40315cae2c646c0509\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:50:22'),
(41, '9510c7b0-47d6-41d7-94a8-142fe05d9554', '9510c7b0-6247-4a3f-b59f-4211583bca26', NULL, 1, 'model', '{\"action\":\"retrieved\",\"model\":\"App\\\\Models\\\\User\",\"count\":1,\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:50:22'),
(42, '9510c7b0-4bee-4854-9ebf-34788ad55713', '9510c7b0-6247-4a3f-b59f-4211583bca26', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select `make` from `vehicles` where `make` != \'\' group by `make`\",\"time\":\"3.59\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\app\\\\Http\\\\Controllers\\\\VehicleController.php\",\"line\":303,\"hash\":\"3ad4cc33233e824c588134eeee5a1645\",\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:22'),
(43, '9510c7b0-4d7e-4785-b5cc-bab09951a23e', '9510c7b0-6247-4a3f-b59f-4211583bca26', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select `model` from `vehicles` where `model` != \'\' group by `model`\",\"time\":\"0.91\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\app\\\\Http\\\\Controllers\\\\VehicleController.php\",\"line\":310,\"hash\":\"86e0e9d2d870fd0bdd471eb93416b259\",\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:22'),
(44, '9510c7b0-4fcc-4520-89f5-f22fcd66990d', '9510c7b0-6247-4a3f-b59f-4211583bca26', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select `meta_value` from `vehicle_metas` where `meta_key` = \'status\' group by `meta_value`\",\"time\":\"2.18\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\app\\\\Http\\\\Controllers\\\\VehicleController.php\",\"line\":26,\"hash\":\"3115c43a225999a8ac2713183d19e2ea\",\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:22'),
(45, '9510c7b0-5323-41b4-aa5b-5776dd770e43', '9510c7b0-6247-4a3f-b59f-4211583bca26', NULL, 1, 'view', '{\"name\":\"pages.vehicle.index\",\"path\":\"\\\\resources\\\\views\\/pages\\/vehicle\\/index.blade.php\",\"data\":[\"models\",\"makes\",\"statuses\"],\"composers\":[{\"name\":\"Closure at F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\vendor\\\\barryvdh\\\\laravel-debugbar\\\\src\\\\LaravelDebugbar.php[210:215]\",\"type\":\"composer\"}],\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:22'),
(46, '9510c7b0-55c8-4ba0-bdba-b81f0e7d0981', '9510c7b0-6247-4a3f-b59f-4211583bca26', NULL, 1, 'view', '{\"name\":\"layouts.app\",\"path\":\"\\\\resources\\\\views\\/layouts\\/app.blade.php\",\"data\":[\"models\",\"makes\",\"statuses\",\"role\",\"query\",\"__currentLoopData\",\"loop\"],\"composers\":[{\"name\":\"Closure at F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\vendor\\\\barryvdh\\\\laravel-debugbar\\\\src\\\\LaravelDebugbar.php[210:215]\",\"type\":\"composer\"}],\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:22'),
(47, '9510c7b0-563e-4e78-834c-586e92a47966', '9510c7b0-6247-4a3f-b59f-4211583bca26', NULL, 1, 'view', '{\"name\":\"includes.aside\",\"path\":\"\\\\resources\\\\views\\/includes\\/aside.blade.php\",\"data\":[\"models\",\"makes\",\"statuses\",\"role\",\"query\",\"__currentLoopData\",\"loop\"],\"composers\":[{\"name\":\"Closure at F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\vendor\\\\barryvdh\\\\laravel-debugbar\\\\src\\\\LaravelDebugbar.php[210:215]\",\"type\":\"composer\"}],\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:22'),
(48, '9510c7b0-56cf-4af8-9944-8b7330066787', '9510c7b0-6247-4a3f-b59f-4211583bca26', NULL, 1, 'view', '{\"name\":\"includes.header\",\"path\":\"\\\\resources\\\\views\\/includes\\/header.blade.php\",\"data\":[\"models\",\"makes\",\"statuses\",\"role\",\"query\",\"__currentLoopData\",\"loop\"],\"composers\":[{\"name\":\"Closure at F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\vendor\\\\barryvdh\\\\laravel-debugbar\\\\src\\\\LaravelDebugbar.php[210:215]\",\"type\":\"composer\"}],\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:22'),
(49, '9510c7b0-57b9-41ec-a2bb-89cbdee3026d', '9510c7b0-6247-4a3f-b59f-4211583bca26', NULL, 1, 'view', '{\"name\":\"includes.footer\",\"path\":\"\\\\resources\\\\views\\/includes\\/footer.blade.php\",\"data\":[\"models\",\"makes\",\"statuses\",\"role\",\"query\",\"__currentLoopData\",\"loop\"],\"composers\":[{\"name\":\"Closure at F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\vendor\\\\barryvdh\\\\laravel-debugbar\\\\src\\\\LaravelDebugbar.php[210:215]\",\"type\":\"composer\"}],\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:22'),
(50, '9510c7b0-5e1e-40b0-95b1-53f11c897c9c', '9510c7b0-6247-4a3f-b59f-4211583bca26', NULL, 1, 'debugbar', '{\"requestId\":\"X837c03e92697dd5ab8c963548e8c978d\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:50:22'),
(51, '9510c7b0-61c3-424f-baa6-553f0c32066c', '9510c7b0-6247-4a3f-b59f-4211583bca26', NULL, 1, 'request', '{\"ip_address\":\"127.0.0.1\",\"uri\":\"\\/vehicles\",\"method\":\"GET\",\"controller_action\":\"App\\\\Http\\\\Controllers\\\\VehicleController@index\",\"middleware\":[\"web\",\"auth\"],\"headers\":{\"host\":\"127.0.0.1:8000\",\"connection\":\"keep-alive\",\"sec-ch-ua\":\"\\\" Not A;Brand\\\";v=\\\"99\\\", \\\"Chromium\\\";v=\\\"96\\\", \\\"Google Chrome\\\";v=\\\"96\\\"\",\"sec-ch-ua-mobile\":\"?0\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"upgrade-insecure-requests\":\"1\",\"user-agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/96.0.4664.45 Safari\\/537.36\",\"accept\":\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.9\",\"sec-fetch-site\":\"same-origin\",\"sec-fetch-mode\":\"navigate\",\"sec-fetch-user\":\"?1\",\"sec-fetch-dest\":\"document\",\"referer\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\",\"accept-encoding\":\"gzip, deflate, br\",\"accept-language\":\"en-US,en;q=0.9\",\"cookie\":\"remember_web_3dc7a913ef5fd4b890ecabe3487085573e16cf82=eyJpdiI6IkhQbkluUnUxS1Z5WEdjMi83eEUyVlE9PSIsInZhbHVlIjoiVWVybWtFQjNPcDQvclNidzVDY1h0QndEbEpjbjdVTXd0Nm5lazJ1MnB3cnVhUmJqOXZaeXFYc3FUOXc0THNLck5KbVk1Q09JWU93U25vNkJzKzVOV0xMS2lLZmZUNXZieGRoSlRiRGUrLzMrREsrQkhJNEhIZzRXNnZmYnRnRWFZU0NaeUpFMkkvaDZVT3UyOE9YRSsxZWZEZjdrNE14VlUwZUxTaWJ3SVFZPSIsIm1hYyI6IjNmYzg5OTQ2M2ZiOGUwZjYwMGYyZmY2NTA0NDY1NTkyOTlmYzhlNjA1M2FiM2NjMzI5Mzc4MDM1NTZjNDhlZTQiLCJ0YWciOiIifQ%3D%3D; XSRF-TOKEN=eyJpdiI6IkZQWDZRaDNJYlJiTklZUEtBTUdsQ3c9PSIsInZhbHVlIjoiUWNZeDUxUVAyTW5OWGFRM2d3UWFnSGpjckhpckh1Z25ydVlMSVRmV1BDSHdRYXVpNE5VU2pKZ1BIc0ZCL0pPb2ZMZXpibFJLazdxc1ZjN2xJWWZPVXFWZnpxNVIxUHA4TU45emRsTU9JSGZ1c2tkbVVBb2doSmRWZzhPNSsvMXgiLCJtYWMiOiJjYmYzZDQ3NjBiZjgzY2EzMmE0OWRkMWZmNjA5ZDk4N2M2ZGVkZGI0NzVkY2M4NzU2ZWIxMDEwNWE1YWQ0YTUxIiwidGFnIjoiIn0%3D; tracker_session=eyJpdiI6IkFZa21MeDRxczlJWUs1ZE9mcmRreVE9PSIsInZhbHVlIjoiejBtT0xOVkVndkR1S2VkNG51NXdFcDdiUE9LNXorWW90S2dwc3ZJdi9SSVFhSFBPZGN3TjNCWjlnTjNJVGhlQ3V4b0ZrZ1lIaWl1UzBEWnZtcks1U2F5QjZ2cm93L0ZKeFhPVk9LZEEyWExyWHR4YTlGY09pa2wrTkNSQW93VmEiLCJtYWMiOiJhZDY1MThlNWNjNzg5MGRhMGQ5MDZkYzkwNzZlZjVlYTA0OWI1YjY3NjQ4ZGY1N2FlOTFmNjdlYWFjMDI1NDAyIiwidGFnIjoiIn0%3D\"},\"payload\":[],\"session\":{\"_token\":\"XAoDQJqSzn1BmiIEhPYv1Phv5Dj22fWGQ4BriCBp\",\"_previous\":{\"url\":\"http:\\/\\/127.0.0.1:8000\\/vehicles\"},\"_flash\":{\"old\":[],\"new\":[]},\"url\":[],\"login_web_3dc7a913ef5fd4b890ecabe3487085573e16cf82\":2,\"PHPDEBUGBAR_STACK_DATA\":[]},\"response_status\":200,\"response\":{\"view\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\resources\\\\views\\/pages\\/vehicle\\/index.blade.php\",\"data\":{\"models\":{\"class\":\"Illuminate\\\\Database\\\\Eloquent\\\\Collection\",\"properties\":[]},\"makes\":{\"class\":\"Illuminate\\\\Database\\\\Eloquent\\\\Collection\",\"properties\":[]},\"statuses\":[]}},\"duration\":436,\"memory\":24,\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:22'),
(52, '9510c7b1-61dd-4be0-ac15-43dfd80a461b', '9510c7b1-75d8-4118-b660-841461adb9b0', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select * from `users` where `id` = 2 limit 1\",\"time\":\"1.85\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\public\\\\index.php\",\"line\":52,\"hash\":\"082e27d9c4fc4a40315cae2c646c0509\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:50:23'),
(53, '9510c7b1-6523-4870-a01a-fb6b2c4c36a7', '9510c7b1-75d8-4118-b660-841461adb9b0', NULL, 1, 'model', '{\"action\":\"retrieved\",\"model\":\"App\\\\Models\\\\User\",\"count\":1,\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:50:23'),
(54, '9510c7b1-676c-49b5-ae30-1d0b771cc966', '9510c7b1-75d8-4118-b660-841461adb9b0', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select count(*) as aggregate from `vehicles`\",\"time\":\"1.60\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\app\\\\Http\\\\Controllers\\\\DatatableController.php\",\"line\":17,\"hash\":\"d59f0c06c0dd839df905598bc30efbb6\",\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:23'),
(55, '9510c7b1-68f4-4529-b82a-a85513d25399', '9510c7b1-75d8-4118-b660-841461adb9b0', NULL, 1, 'query', '{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select * from `vehicles` order by `created_at` desc limit 10 offset 0\",\"time\":\"0.60\",\"slow\":false,\"file\":\"F:\\\\Installed\\\\laragon\\\\www\\\\tracker\\\\app\\\\Http\\\\Controllers\\\\DatatableController.php\",\"line\":34,\"hash\":\"495b6d81dacae05cc550ec3512d6b8fa\",\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:23'),
(56, '9510c7b1-7302-4c37-9e2c-3c357a40c69a', '9510c7b1-75d8-4118-b660-841461adb9b0', NULL, 1, 'debugbar', '{\"requestId\":\"Xe48f15d6b522f3854e0d3fafb1ff19b7\",\"hostname\":\"DESKTOP-LFU8P4O\"}', '2021-12-08 14:50:23'),
(57, '9510c7b1-7551-44d2-bb64-53334ff2b6c8', '9510c7b1-75d8-4118-b660-841461adb9b0', NULL, 1, 'request', '{\"ip_address\":\"127.0.0.1\",\"uri\":\"\\/api\\/v1\\/orders_update?_=1638993022584&columns%5B0%5D%5Bdata%5D=null&columns%5B0%5D%5Bname%5D=&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=false&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=id&columns%5B1%5D%5Bname%5D=&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=false&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=invoice_date&columns%5B2%5D%5Bname%5D=&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=false&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=lot&columns%5B3%5D%5Bname%5D=&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=false&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=vin&columns%5B4%5D%5Bname%5D=&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=false&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=year&columns%5B5%5D%5Bname%5D=&columns%5B5%5D%5Bsearchable%5D=true&columns%5B5%5D%5Borderable%5D=false&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B6%5D%5Bdata%5D=make&columns%5B6%5D%5Bname%5D=&columns%5B6%5D%5Bsearchable%5D=true&columns%5B6%5D%5Borderable%5D=false&columns%5B6%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B6%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B7%5D%5Bdata%5D=model&columns%5B7%5D%5Bname%5D=&columns%5B7%5D%5Bsearchable%5D=true&columns%5B7%5D%5Borderable%5D=false&columns%5B7%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B7%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B8%5D%5Bdata%5D=created_at_new&columns%5B8%5D%5Bname%5D=&columns%5B8%5D%5Bsearchable%5D=true&columns%5B8%5D%5Borderable%5D=false&columns%5B8%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B8%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B9%5D%5Bdata%5D=actions&columns%5B9%5D%5Bname%5D=&columns%5B9%5D%5Bsearchable%5D=true&columns%5B9%5D%5Borderable%5D=false&columns%5B9%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B9%5D%5Bsearch%5D%5Bregex%5D=false&daterange=&draw=1&length=10&make=-100&model=-100&search%5Bvalue%5D=&search%5Bregex%5D=false&start=0&status=-100\",\"method\":\"GET\",\"controller_action\":\"App\\\\Http\\\\Controllers\\\\DatatableController@orders\",\"middleware\":[\"web\",\"auth\"],\"headers\":{\"host\":\"127.0.0.1:8000\",\"connection\":\"keep-alive\",\"sec-ch-ua\":\"\\\" Not A;Brand\\\";v=\\\"99\\\", \\\"Chromium\\\";v=\\\"96\\\", \\\"Google Chrome\\\";v=\\\"96\\\"\",\"accept\":\"application\\/json, text\\/javascript, *\\/*; q=0.01\",\"x-requested-with\":\"XMLHttpRequest\",\"sec-ch-ua-mobile\":\"?0\",\"user-agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/96.0.4664.45 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"sec-fetch-site\":\"same-origin\",\"sec-fetch-mode\":\"cors\",\"sec-fetch-dest\":\"empty\",\"referer\":\"http:\\/\\/127.0.0.1:8000\\/vehicles?search=&make=-100&model=-100&status=-100&daterange=&user=undefined&used_status=undefined&gateway=undefined&tag=undefined\",\"accept-encoding\":\"gzip, deflate, br\",\"accept-language\":\"en-US,en;q=0.9\",\"cookie\":\"remember_web_3dc7a913ef5fd4b890ecabe3487085573e16cf82=eyJpdiI6IkhQbkluUnUxS1Z5WEdjMi83eEUyVlE9PSIsInZhbHVlIjoiVWVybWtFQjNPcDQvclNidzVDY1h0QndEbEpjbjdVTXd0Nm5lazJ1MnB3cnVhUmJqOXZaeXFYc3FUOXc0THNLck5KbVk1Q09JWU93U25vNkJzKzVOV0xMS2lLZmZUNXZieGRoSlRiRGUrLzMrREsrQkhJNEhIZzRXNnZmYnRnRWFZU0NaeUpFMkkvaDZVT3UyOE9YRSsxZWZEZjdrNE14VlUwZUxTaWJ3SVFZPSIsIm1hYyI6IjNmYzg5OTQ2M2ZiOGUwZjYwMGYyZmY2NTA0NDY1NTkyOTlmYzhlNjA1M2FiM2NjMzI5Mzc4MDM1NTZjNDhlZTQiLCJ0YWciOiIifQ%3D%3D; XSRF-TOKEN=eyJpdiI6ImVFRjlwNUJtRTF1VkxZYlF0VzBUWXc9PSIsInZhbHVlIjoiVE5CY01jT0dsSVNBemZlR01GUzlsTHU4dFBRU1NkUDFMc0NkcUpvTXpEeS9yS09YK0FqT2JWT3lsNWhNQ2RON2NZdzRiR3dXVTl5RGg2amlDQUNXcTdMS1NtQ29wQ25HZ3R0NkhCaWVYaC9TL3pjNG9JMFB5WkphRUtyQ1o4Qk8iLCJtYWMiOiIzYzhlYTZhYzVjNDczYmEwODZlYjMwMGQxNDFiNjcyNGJmNWJhNzZjY2FiNTM1YThhMmIxMDQxMWVhNGFmN2E5IiwidGFnIjoiIn0%3D; tracker_session=eyJpdiI6IkIrd3pNWDU5TVltd0E2aG1PN0g5alE9PSIsInZhbHVlIjoiU0M2K0N4djZjSG85ZHRzY01EdU0yVXFYSERwSkQraUtUUDNsQldOR0RKakZta1JBS0w0bDlCZkpqQktXTXBVVHFUcWtyL3hFaS9iaW5FMldoNm9aYS9qSzBXZ25xYm9sUFprVUpydjVXRk50RE5DV1RLK0pEQkg3RGp4Q3FTRUsiLCJtYWMiOiI2Mzc2NjRjNzM3NzEwN2ZjMzA3YTJiZjllMWUyMmM2ODM0OWJiMzFjYTliYmEzMDhhZjRiMzI0MWM0Yzc4MzcxIiwidGFnIjoiIn0%3D\"},\"payload\":{\"draw\":\"1\",\"columns\":[{\"data\":\"null\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"false\",\"search\":{\"value\":null,\"regex\":\"false\"}},{\"data\":\"id\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"false\",\"search\":{\"value\":null,\"regex\":\"false\"}},{\"data\":\"invoice_date\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"false\",\"search\":{\"value\":null,\"regex\":\"false\"}},{\"data\":\"lot\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"false\",\"search\":{\"value\":null,\"regex\":\"false\"}},{\"data\":\"vin\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"false\",\"search\":{\"value\":null,\"regex\":\"false\"}},{\"data\":\"year\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"false\",\"search\":{\"value\":null,\"regex\":\"false\"}},{\"data\":\"make\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"false\",\"search\":{\"value\":null,\"regex\":\"false\"}},{\"data\":\"model\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"false\",\"search\":{\"value\":null,\"regex\":\"false\"}},{\"data\":\"created_at_new\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"false\",\"search\":{\"value\":null,\"regex\":\"false\"}},{\"data\":\"actions\",\"name\":null,\"searchable\":\"true\",\"orderable\":\"false\",\"search\":{\"value\":null,\"regex\":\"false\"}}],\"start\":\"0\",\"length\":\"10\",\"search\":{\"value\":null,\"regex\":\"false\"},\"make\":\"-100\",\"model\":\"-100\",\"status\":\"-100\",\"daterange\":null,\"_\":\"1638993022584\"},\"session\":{\"_token\":\"XAoDQJqSzn1BmiIEhPYv1Phv5Dj22fWGQ4BriCBp\",\"_previous\":{\"url\":\"http:\\/\\/127.0.0.1:8000\\/vehicles\"},\"_flash\":{\"old\":[],\"new\":[]},\"url\":[],\"login_web_3dc7a913ef5fd4b890ecabe3487085573e16cf82\":2},\"response_status\":200,\"response\":{\"draw\":1,\"recordsTotal\":0,\"recordsFiltered\":0,\"data\":[],\"extra_info\":{\"total_orders_count\":0,\"orders_status_accepted\":0,\"orders_status_pending\":0,\"orders_status_declined\":0,\"user_rate\":\"\"}},\"duration\":387,\"memory\":24,\"hostname\":\"DESKTOP-LFU8P4O\",\"user\":{\"id\":2,\"name\":\"Vida Aufderhar\",\"email\":\"rrussel@example.org\"}}', '2021-12-08 14:50:23');

-- --------------------------------------------------------

--
-- Table structure for table `telescope_entries_tags`
--

CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `telescope_entries_tags`
--

INSERT INTO `telescope_entries_tags` (`entry_uuid`, `tag`) VALUES
('9510c770-729d-4e28-9897-bd3bf81a9e35', 'App\\Models\\User:1'),
('9510c770-7d7e-4a29-a1e5-bb845673dbba', 'App\\Models\\User:2'),
('9510c770-7eeb-4ea8-a8c3-7ecd35ea20ea', 'App\\Models\\User:3'),
('9510c770-8059-4a79-acd5-0e2a6a7a98ba', 'App\\Models\\User:4'),
('9510c770-8178-499e-bd24-2463f0a43183', 'App\\Models\\User:5'),
('9510c770-82a6-4a5a-bca1-2ebdfa57c126', 'App\\Models\\User:6'),
('9510c77d-2f57-40cb-9062-e89e0a4619fd', 'App\\Models\\User'),
('9510c77d-327a-48b8-9d4b-85f32856d8ff', 'Auth:1'),
('9510c77d-330c-4805-a6e8-5a67ab2daa3a', 'Auth:1'),
('9510c77d-34a0-4ed0-a225-32047f529e21', 'Auth:1'),
('9510c77d-362c-4970-ae49-5a916e39eaf0', 'Auth:1'),
('9510c77d-3801-4719-b949-e4e194b96f95', 'Auth:1'),
('9510c77d-3961-414a-903a-7527082f4e6a', 'Auth:1'),
('9510c77d-3a48-43d8-af0c-faf06f4e8dbc', 'Auth:1'),
('9510c77d-3ae6-41fe-aad9-9c10be7920b6', 'Auth:1'),
('9510c77d-3bd8-4041-a6f1-3ee4793af355', 'Auth:1'),
('9510c77d-3cd4-4ff9-8ca5-694b84393171', 'Auth:1'),
('9510c77d-460d-4e60-b57c-9147447cbcd5', 'Auth:1'),
('9510c7ab-bb6b-4c99-a193-5dd8022b0770', 'App\\Models\\User'),
('9510c7ab-beb2-4ded-8a89-18a4189ecfc2', 'Auth:2'),
('9510c7ab-bf46-4f1c-ac61-f707d538c2f1', 'Auth:2'),
('9510c7ab-c05c-465c-81b8-495bc28edd8b', 'Auth:2'),
('9510c7ab-c0d4-43ef-9c48-96dc0c56571c', 'Auth:2'),
('9510c7ab-c18d-4a3d-8848-d33abeab2c2d', 'Auth:2'),
('9510c7ab-c256-4272-8e2d-106589a4f627', 'Auth:2'),
('9510c7ab-cee9-4f6d-b2f3-7634e3bbbaeb', 'Auth:2'),
('9510c7b0-47d6-41d7-94a8-142fe05d9554', 'App\\Models\\User'),
('9510c7b0-4bee-4854-9ebf-34788ad55713', 'Auth:2'),
('9510c7b0-4d7e-4785-b5cc-bab09951a23e', 'Auth:2'),
('9510c7b0-4fcc-4520-89f5-f22fcd66990d', 'Auth:2'),
('9510c7b0-5323-41b4-aa5b-5776dd770e43', 'Auth:2'),
('9510c7b0-55c8-4ba0-bdba-b81f0e7d0981', 'Auth:2'),
('9510c7b0-563e-4e78-834c-586e92a47966', 'Auth:2'),
('9510c7b0-56cf-4af8-9944-8b7330066787', 'Auth:2'),
('9510c7b0-57b9-41ec-a2bb-89cbdee3026d', 'Auth:2'),
('9510c7b0-61c3-424f-baa6-553f0c32066c', 'Auth:2'),
('9510c7b1-6523-4870-a01a-fb6b2c4c36a7', 'App\\Models\\User'),
('9510c7b1-676c-49b5-ae30-1d0b771cc966', 'Auth:2'),
('9510c7b1-68f4-4529-b82a-a85513d25399', 'Auth:2'),
('9510c7b1-7551-44d2-bb64-53334ff2b6c8', 'Auth:2');

-- --------------------------------------------------------

--
-- Table structure for table `telescope_monitoring`
--

CREATE TABLE `telescope_monitoring` (
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `role`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Hamza Siddique', 'admin@gmail.com', '2021-12-08 09:49:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'admin', NULL, '2021-12-08 09:49:40', '2021-12-08 09:49:40', NULL),
(2, 'Vida Aufderhar', 'rrussel@example.org', '2021-12-08 09:49:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'user', 'Vb4DJ0MvgR', '2021-12-08 09:49:40', '2021-12-08 09:49:40', NULL),
(3, 'Ms. Alverta Wisoky', 'dauer@example.net', '2021-12-08 09:49:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'user', 'fUo7RoJv2i', '2021-12-08 09:49:40', '2021-12-08 09:49:40', NULL),
(4, 'Melissa Willms', 'bins.marquis@example.com', '2021-12-08 09:49:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'user', 'PG6KfBBGFR', '2021-12-08 09:49:40', '2021-12-08 09:49:40', NULL),
(5, 'Rudy Ondricka', 'bailey.abbie@example.com', '2021-12-08 09:49:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'user', 'pRObO6FYEN', '2021-12-08 09:49:40', '2021-12-08 09:49:40', NULL),
(6, 'Joshuah Corkery', 'ksipes@example.org', '2021-12-08 09:49:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'user', '58tNxZdDjt', '2021-12-08 09:49:40', '2021-12-08 09:49:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_date` date DEFAULT NULL,
  `lot` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `make` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_metas`
--

CREATE TABLE `vehicle_metas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `telescope_entries`
--
ALTER TABLE `telescope_entries`
  ADD PRIMARY KEY (`sequence`),
  ADD UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  ADD KEY `telescope_entries_batch_id_index` (`batch_id`),
  ADD KEY `telescope_entries_family_hash_index` (`family_hash`),
  ADD KEY `telescope_entries_created_at_index` (`created_at`),
  ADD KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`);

--
-- Indexes for table `telescope_entries_tags`
--
ALTER TABLE `telescope_entries_tags`
  ADD KEY `telescope_entries_tags_entry_uuid_tag_index` (`entry_uuid`,`tag`),
  ADD KEY `telescope_entries_tags_tag_index` (`tag`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicle_metas`
--
ALTER TABLE `vehicle_metas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `telescope_entries`
--
ALTER TABLE `telescope_entries`
  MODIFY `sequence` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_metas`
--
ALTER TABLE `vehicle_metas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `telescope_entries_tags`
--
ALTER TABLE `telescope_entries_tags`
  ADD CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
