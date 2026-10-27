-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2026 at 01:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `guard_attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `guard_id` bigint(20) UNSIGNED NOT NULL,
  `guard_site_assignment_id` bigint(20) UNSIGNED NOT NULL,
  `site_id` bigint(20) UNSIGNED NOT NULL,
  `attendance_date` date NOT NULL,
  `check_in_at` timestamp NULL DEFAULT NULL,
  `check_out_at` timestamp NULL DEFAULT NULL,
  `check_in_latitude` decimal(10,7) DEFAULT NULL,
  `check_in_longitude` decimal(10,7) DEFAULT NULL,
  `check_in_accuracy_m` smallint(5) UNSIGNED DEFAULT NULL,
  `check_out_latitude` decimal(10,7) DEFAULT NULL,
  `check_out_longitude` decimal(10,7) DEFAULT NULL,
  `check_out_accuracy_m` smallint(5) UNSIGNED DEFAULT NULL,
  `worked_minutes` int(10) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'present',
  `check_in_method` varchar(255) NOT NULL DEFAULT 'qr_gps',
  `check_out_method` varchar(255) NOT NULL DEFAULT 'qr_gps',
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_audit_logs`
--

CREATE TABLE `attendance_audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attendance_id` bigint(20) UNSIGNED DEFAULT NULL,
  `guard_id` bigint(20) UNSIGNED DEFAULT NULL,
  `actor_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('guard-attendance-cache-28226c821876d464e565546721885e8216548bc3', 'i:1;', 1793105312),
('guard-attendance-cache-28226c821876d464e565546721885e8216548bc3:timer', 'i:1793105312;', 1793105312),
('guard-attendance-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1793105337),
('guard-attendance-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1793105337;', 1793105337),
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1792428965),
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1792428965;', 1792428965),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1792428991),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1792428991;', 1792428991);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `code`, `notes`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Demo Client Company', 'DEMO001', NULL, 1, '2026-10-27 07:45:20', '2026-10-27 07:45:20');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guards`
--

CREATE TABLE `guards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL DEFAULT 'Demo Guard',
  `joining_date` date DEFAULT NULL,
  `designation` varchar(255) NOT NULL DEFAULT 'Security Guard',
  `employment_status` varchar(255) NOT NULL DEFAULT 'active',
  `emergency_contact` varchar(30) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guards`
--

INSERT INTO `guards` (`id`, `user_id`, `employee_id`, `full_name`, `joining_date`, `designation`, `employment_status`, `emergency_contact`, `notes`, `created_at`, `updated_at`) VALUES
(1, 4, 'GRD0001', 'Demo Guard', '2026-09-19', 'Security Guard', 'active', NULL, NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31'),
(2, 5, 'GRD0002', 'Demo Guard', '2026-08-19', 'Security Guard', 'active', NULL, NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31'),
(3, 6, 'GRD0003', 'Demo Guard', '2026-07-19', 'Security Guard', 'active', NULL, NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31'),
(4, 7, 'GRD0004', 'Demo Guard', '2026-06-19', 'Security Guard', 'active', NULL, NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31'),
(5, 8, 'GRD0005', 'Demo Guard', '2026-05-19', 'Security Guard', 'active', NULL, NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31'),
(6, 9, 'GRD0006', 'Demo Guard', '2026-04-19', 'Security Guard', 'active', NULL, NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31'),
(7, 10, 'GRD0007', 'Demo Guard', '2026-03-19', 'Security Guard', 'active', NULL, NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31'),
(8, 11, 'GRD0008', 'Demo Guard', '2026-02-19', 'Security Guard', 'active', NULL, NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31'),
(9, 12, 'GRD0009', 'Demo Guard', '2026-01-19', 'Security Guard', 'active', NULL, NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31'),
(10, 13, 'GRD0010', 'Demo Guard', '2025-12-19', 'Security Guard', 'active', NULL, NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31');

-- --------------------------------------------------------

--
-- Table structure for table `guard_site_assignments`
--

CREATE TABLE `guard_site_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `guard_id` bigint(20) UNSIGNED NOT NULL,
  `site_id` bigint(20) UNSIGNED NOT NULL,
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `assignment_date` date DEFAULT NULL,
  `starts_on` date NOT NULL,
  `ends_on` date DEFAULT NULL,
  `scheduled_start_time` time DEFAULT NULL,
  `scheduled_end_time` time DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `assigned_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `holiday_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `guard_id` bigint(20) UNSIGNED NOT NULL,
  `starts_on` date NOT NULL,
  `ends_on` date NOT NULL,
  `leave_type` varchar(255) NOT NULL DEFAULT 'casual',
  `reason` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_10_16_000001_create_guard_attendance_tables', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qr_codes`
--

CREATE TABLE `qr_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` bigint(20) UNSIGNED NOT NULL,
  `public_id` char(36) NOT NULL,
  `token_hash` varchar(64) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `revoked_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator', 'Full system access.', '2026-10-16 11:18:18', '2026-10-16 11:18:18'),
(2, 'hr', 'HR', 'Employee and guard management, assignments, and attendance management.', '2026-10-16 11:18:18', '2026-10-16 11:18:18'),
(3, 'accounts', 'Accounts', 'Attendance management and attendance reports.', '2026-10-16 11:18:18', '2026-10-16 11:18:18'),
(4, 'guard', 'Security Guard', 'Can access own profile, assignments, and attendance actions.', '2026-10-16 11:18:18', '2026-10-16 11:18:18');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0r8vRXPehTdRoGWLJ5XR8omGsBoffbG7ylSZdDtP', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI1MUg1ZTJLNXRXZ21ZODA5ZnBPWmNvUzFrSXVkb3RhWkFBc0FBbDFKIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvY29tcGFuaWVzIiwicm91dGUiOiJhZG1pbi5jb21wYW5pZXMifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwicGFzc3dvcmRfaGFzaF93ZWIiOiI4OTNmNDlhNjU4YzU3ODc4Y2QwMThjY2RjMjY3ODg0MDg2ZTJiNzU4MjVjZDUyZTEwZmFhMzQ2ODE2YzYwOTg2In0=', 1793107297),
('8S78e6WzjHBM8CVmxysnL3wT3m7bCFgQ7892oCMT', 4, '192.168.1.26', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiJqcFU1Ynp2WGtQODNEREVGS1gyT29HZlV0YmJBdWJqT056UjFpQXBTIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzE5Mi4xNjguMS4yODo4MDAwXC9ndWFyZFwvZGFzaGJvYXJkIiwicm91dGUiOiJndWFyZC5kYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6NCwicGFzc3dvcmRfaGFzaF93ZWIiOiI4OTNmNDlhNjU4YzU3ODc4Y2QwMThjY2RjMjY3ODg0MDg2ZTJiNzU4MjVjZDUyZTEwZmFhMzQ2ODE2YzYwOTg2In0=', 1793106023);

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `duration_minutes` smallint(5) UNSIGNED NOT NULL DEFAULT 720,
  `grace_period_minutes` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `crosses_midnight` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE `sites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`id`, `company_id`, `name`, `address`, `latitude`, `longitude`, `is_active`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Head Office', 'Connaught Place, New Delhi, Delhi', 28.6315000, 77.2167000, 1, 0, '2026-10-27 07:45:20', '2026-10-27 07:45:20'),
(2, 1, 'Warehouse Site', 'Okhla Industrial Area, New Delhi, Delhi', 28.5355000, 77.2732000, 1, 0, '2026-10-27 07:45:20', '2026-10-27 07:45:20'),
(3, 1, 'Branch Office', 'Netaji Subhash Place, Pitampura, Delhi', 28.6942000, 77.1526000, 1, 0, '2026-10-27 07:45:20', '2026-10-27 07:45:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` bigint(20) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `mobile`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Demo Admin', 'admin@example.com', 9990000001, NULL, '$2y$12$ovsbqluQXbw6ghzpvAq41uP5F6uggeE/3ERlvHb6ADSM8CC23xaS2', NULL, '2026-10-19 11:11:33', '2026-10-19 11:13:31'),
(2, 2, 'Demo HR', 'hr@example.com', 9990000002, NULL, '$2y$12$ovsbqluQXbw6ghzpvAq41uP5F6uggeE/3ERlvHb6ADSM8CC23xaS2', NULL, '2026-10-19 11:11:33', '2026-10-19 11:13:31'),
(3, 3, 'Demo Accounts', 'accounts@example.com', 9990000003, NULL, '$2y$12$ovsbqluQXbw6ghzpvAq41uP5F6uggeE/3ERlvHb6ADSM8CC23xaS2', NULL, '2026-10-19 11:11:33', '2026-10-19 11:13:31'),
(4, 4, 'Demo Guard 1', 'guard1@example.com', 9991000001, NULL, '$2y$12$ovsbqluQXbw6ghzpvAq41uP5F6uggeE/3ERlvHb6ADSM8CC23xaS2', NULL, '2026-10-19 11:11:33', '2026-10-19 11:13:31'),
(5, 4, 'Demo Guard 2', 'guard2@example.com', 9991000002, NULL, '$2y$12$ovsbqluQXbw6ghzpvAq41uP5F6uggeE/3ERlvHb6ADSM8CC23xaS2', NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31'),
(6, 4, 'Demo Guard 3', 'guard3@example.com', 9991000003, NULL, '$2y$12$ovsbqluQXbw6ghzpvAq41uP5F6uggeE/3ERlvHb6ADSM8CC23xaS2', NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31'),
(7, 4, 'Demo Guard 4', 'guard4@example.com', 9991000004, NULL, '$2y$12$ovsbqluQXbw6ghzpvAq41uP5F6uggeE/3ERlvHb6ADSM8CC23xaS2', NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31'),
(8, 4, 'Demo Guard 5', 'guard5@example.com', 9991000005, NULL, '$2y$12$ovsbqluQXbw6ghzpvAq41uP5F6uggeE/3ERlvHb6ADSM8CC23xaS2', NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31'),
(9, 4, 'Demo Guard 6', 'guard6@example.com', 9991000006, NULL, '$2y$12$ovsbqluQXbw6ghzpvAq41uP5F6uggeE/3ERlvHb6ADSM8CC23xaS2', NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31'),
(10, 4, 'Demo Guard 7', 'guard7@example.com', 9991000007, NULL, '$2y$12$ovsbqluQXbw6ghzpvAq41uP5F6uggeE/3ERlvHb6ADSM8CC23xaS2', NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31'),
(11, 4, 'Demo Guard 8', 'guard8@example.com', 9991000008, NULL, '$2y$12$ovsbqluQXbw6ghzpvAq41uP5F6uggeE/3ERlvHb6ADSM8CC23xaS2', NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31'),
(12, 4, 'Demo Guard 9', 'guard9@example.com', 9991000009, NULL, '$2y$12$ovsbqluQXbw6ghzpvAq41uP5F6uggeE/3ERlvHb6ADSM8CC23xaS2', NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31'),
(13, 4, 'Demo Guard 10', 'guard10@example.com', 9991000010, NULL, '$2y$12$ovsbqluQXbw6ghzpvAq41uP5F6uggeE/3ERlvHb6ADSM8CC23xaS2', NULL, '2026-10-19 11:13:31', '2026-10-19 11:13:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attendance_guard_date_assignment_unique` (`guard_id`,`attendance_date`,`guard_site_assignment_id`),
  ADD KEY `attendances_guard_site_assignment_id_foreign` (`guard_site_assignment_id`),
  ADD KEY `attendances_created_by_foreign` (`created_by`),
  ADD KEY `attendances_updated_by_foreign` (`updated_by`),
  ADD KEY `attendances_site_id_attendance_date_status_index` (`site_id`,`attendance_date`,`status`),
  ADD KEY `attendances_attendance_date_status_index` (`attendance_date`,`status`);

--
-- Indexes for table `attendance_audit_logs`
--
ALTER TABLE `attendance_audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendance_audit_logs_guard_id_foreign` (`guard_id`),
  ADD KEY `attendance_audit_logs_attendance_id_created_at_index` (`attendance_id`,`created_at`),
  ADD KEY `attendance_audit_logs_actor_user_id_created_at_index` (`actor_user_id`,`created_at`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `companies_code_unique` (`code`),
  ADD KEY `companies_name_is_active_index` (`name`,`is_active`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `guards`
--
ALTER TABLE `guards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `guards_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `guards_employee_id_unique` (`employee_id`),
  ADD KEY `guards_employment_status_full_name_index` (`employment_status`,`full_name`);

--
-- Indexes for table `guard_site_assignments`
--
ALTER TABLE `guard_site_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guard_site_assignments_shift_id_foreign` (`shift_id`),
  ADD KEY `guard_site_assignments_assigned_by_foreign` (`assigned_by`),
  ADD KEY `guard_assignment_date_status_idx` (`guard_id`,`assignment_date`,`status`),
  ADD KEY `site_assignment_date_status_idx` (`site_id`,`assignment_date`,`status`),
  ADD KEY `guard_site_assignments_starts_on_ends_on_index` (`starts_on`,`ends_on`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `holidays_created_by_foreign` (`created_by`),
  ADD KEY `holidays_company_id_holiday_date_index` (`company_id`,`holiday_date`),
  ADD KEY `holidays_site_id_holiday_date_index` (`site_id`,`holiday_date`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leaves_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `leaves_guard_id_starts_on_ends_on_index` (`guard_id`,`starts_on`,`ends_on`),
  ADD KEY `leaves_status_starts_on_index` (`status`,`starts_on`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `qr_codes_public_id_unique` (`public_id`),
  ADD UNIQUE KEY `qr_codes_token_hash_unique` (`token_hash`),
  ADD KEY `qr_codes_created_by_foreign` (`created_by`),
  ADD KEY `qr_codes_site_id_is_active_index` (`site_id`,`is_active`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shifts_name_start_time_end_time_unique` (`name`,`start_time`,`end_time`);

--
-- Indexes for table `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sites_company_id_is_active_index` (`company_id`,`is_active`),
  ADD KEY `sites_name_index` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobile` (`mobile`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_audit_logs`
--
ALTER TABLE `attendance_audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guards`
--
ALTER TABLE `guards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `guard_site_assignments`
--
ALTER TABLE `guard_site_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `qr_codes`
--
ALTER TABLE `qr_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sites`
--
ALTER TABLE `sites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `attendances_guard_id_foreign` FOREIGN KEY (`guard_id`) REFERENCES `guards` (`id`),
  ADD CONSTRAINT `attendances_guard_site_assignment_id_foreign` FOREIGN KEY (`guard_site_assignment_id`) REFERENCES `guard_site_assignments` (`id`),
  ADD CONSTRAINT `attendances_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`),
  ADD CONSTRAINT `attendances_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `attendance_audit_logs`
--
ALTER TABLE `attendance_audit_logs`
  ADD CONSTRAINT `attendance_audit_logs_actor_user_id_foreign` FOREIGN KEY (`actor_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `attendance_audit_logs_attendance_id_foreign` FOREIGN KEY (`attendance_id`) REFERENCES `attendances` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `attendance_audit_logs_guard_id_foreign` FOREIGN KEY (`guard_id`) REFERENCES `guards` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `guards`
--
ALTER TABLE `guards`
  ADD CONSTRAINT `guards_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `guard_site_assignments`
--
ALTER TABLE `guard_site_assignments`
  ADD CONSTRAINT `guard_site_assignments_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `guard_site_assignments_guard_id_foreign` FOREIGN KEY (`guard_id`) REFERENCES `guards` (`id`),
  ADD CONSTRAINT `guard_site_assignments_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`),
  ADD CONSTRAINT `guard_site_assignments_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`);

--
-- Constraints for table `holidays`
--
ALTER TABLE `holidays`
  ADD CONSTRAINT `holidays_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `holidays_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `holidays_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leaves`
--
ALTER TABLE `leaves`
  ADD CONSTRAINT `leaves_guard_id_foreign` FOREIGN KEY (`guard_id`) REFERENCES `guards` (`id`),
  ADD CONSTRAINT `leaves_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD CONSTRAINT `qr_codes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `qr_codes_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sites`
--
ALTER TABLE `sites`
  ADD CONSTRAINT `sites_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
