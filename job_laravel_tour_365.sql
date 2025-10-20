-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th10 18, 2025 lúc 02:50 PM
-- Phiên bản máy phục vụ: 8.4.3
-- Phiên bản PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `job_laravel_tour_365`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banners`
--

CREATE TABLE `banners` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('hero','promotion','category','featured') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hero',
  `position` enum('top','middle','bottom','sidebar') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'top',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `target_audience` json DEFAULT NULL,
  `click_count` int NOT NULL DEFAULT '0',
  `view_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `banners`
--

INSERT INTO `banners` (`id`, `title`, `description`, `image_url`, `link_url`, `type`, `position`, `sort_order`, `is_active`, `start_date`, `end_date`, `target_audience`, `click_count`, `view_count`, `created_at`, `updated_at`) VALUES
(1, 'Khám phá thế giới cùng Tour365', 'Trải nghiệm những chuyến du lịch tuyệt vời với dịch vụ chuyên nghiệp, an toàn và giá cả hợp lý.', 'https://via.placeholder.com/1200x600/0EA5E9/ffffff?text=Hero+Banner+1', '/tours', 'hero', 'top', 1, 1, '2025-10-18 07:16:54', '2026-01-18 07:16:54', '[\"all\"]', 1, 2, '2025-10-18 07:16:54', '2025-10-18 07:32:47'),
(2, 'Ưu đãi mùa hè 2025', 'Giảm đến 30% cho các tour biển và miền núi. Đặt ngay để không bỏ lỡ cơ hội!', 'https://via.placeholder.com/1200x600/06B6D4/ffffff?text=Summer+Promotion', '/promotions', 'hero', 'top', 2, 1, '2025-10-18 07:16:54', '2025-12-18 07:16:54', '[\"all\"]', 0, 0, '2025-10-18 07:16:54', '2025-10-18 07:16:54'),
(3, 'Tour Phú Quốc - Giảm 20%', 'Khám phá đảo ngọc xinh đẹp với giá ưu đãi', 'https://via.placeholder.com/400x300/FF6B6B/ffffff?text=Phu+Quoc+Tour', '/tours?search=phú+quốc', 'promotion', 'middle', 1, 1, '2025-10-18 07:16:54', '2025-11-18 07:16:54', '[\"all\"]', 1, 2, '2025-10-18 07:16:54', '2025-10-18 07:21:04'),
(4, 'Tour Sapa - Mùa lúa chín', 'Ngắm nhìn ruộng bậc thang vàng óng tuyệt đẹp', 'https://via.placeholder.com/400x300/4ECDC4/ffffff?text=Sapa+Tour', '/tours?search=sapa', 'promotion', 'middle', 2, 1, '2025-10-18 07:16:54', '2025-12-18 07:16:54', '[\"all\"]', 0, 0, '2025-10-18 07:16:54', '2025-10-18 07:16:54'),
(5, 'Du lịch biển', 'Khám phá những bãi biển đẹp nhất Việt Nam', 'https://via.placeholder.com/300x200/38BDF8/ffffff?text=Beach+Tours', '/tours?category_id=1', 'category', 'sidebar', 1, 1, '2025-10-18 07:16:54', NULL, '[\"all\"]', 0, 0, '2025-10-18 07:16:54', '2025-10-18 07:16:54'),
(6, 'Du lịch miền núi', 'Trải nghiệm thiên nhiên hoang sơ và khí hậu mát mẻ', 'https://via.placeholder.com/300x200/10B981/ffffff?text=Mountain+Tours', '/tours?category_id=2', 'category', 'sidebar', 2, 1, '2025-10-18 07:16:54', NULL, '[\"all\"]', 0, 0, '2025-10-18 07:16:54', '2025-10-18 07:16:54'),
(7, 'Tour nổi bật tuần này', 'Đà Nẵng - Hội An 3 ngày 2 đêm', 'https://via.placeholder.com/500x300/F59E0B/ffffff?text=Featured+Tour', '/tours?featured=1', 'featured', 'middle', 1, 1, '2025-10-18 07:16:54', '2025-10-25 07:16:54', '[\"all\"]', 0, 0, '2025-10-18 07:16:54', '2025-10-18 07:16:54'),
(8, 'Banner không hoạt động', 'Banner này đã hết hạn hoặc bị tắt', 'https://via.placeholder.com/400x200/6B7280/ffffff?text=Inactive+Banner', NULL, 'promotion', 'bottom', 99, 0, '2025-09-18 07:16:54', '2025-10-11 07:16:54', '[\"all\"]', 0, 0, '2025-10-18 07:16:54', '2025-10-18 07:16:54'),
(9, 'Chào mừng thành viên mới', 'Nhận ngay voucher 500k cho đơn hàng đầu tiên', 'https://via.placeholder.com/400x200/8B5CF6/ffffff?text=New+User+Welcome', '/register', 'promotion', 'top', 3, 1, '2025-10-18 07:16:54', '2026-04-18 07:16:54', '[\"new_users\"]', 0, 0, '2025-10-18 07:16:54', '2025-10-18 07:16:54'),
(10, 'Cảm ơn bạn đã quay lại', 'Ưu đãi đặc biệt cho khách hàng thân thiết', 'https://via.placeholder.com/400x200/EC4899/ffffff?text=Returning+User', '/promotions?type=loyalty', 'promotion', 'middle', 3, 1, '2025-10-18 07:16:54', '2026-01-18 07:16:54', '[\"returning_users\"]', 0, 0, '2025-10-18 07:16:54', '2025-10-18 07:16:54'),
(11, 'API Configuration', 'API Configuration', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSGqqHiT6kGxQhoulqdKlKa_jp8bYOD7nLKpQ&s', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSGqqHiT6kGxQhoulqdKlKa_jp8bYOD7nLKpQ&s', 'hero', 'top', 0, 1, '2025-10-01 14:33:00', '2025-10-25 14:33:00', '[\"all\"]', 0, 1, '2025-10-18 07:33:45', '2025-10-18 07:33:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `tour_id` bigint NOT NULL,
  `departure_id` bigint DEFAULT NULL,
  `promotion_id` bigint DEFAULT NULL,
  `staff_id` bigint DEFAULT NULL,
  `booking_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `adults` int DEFAULT NULL,
  `children` int DEFAULT NULL,
  `infants` int DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `note` text,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` bigint NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Tour Singapore', 'Các tour du lịch tại singapore', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Du lịch trong nước', 'Các tour du lịch trong nước', '2025-10-08 00:06:16', '2025-10-08 00:06:16'),
(4, 'Du lịch nước ngoài', 'Các tour du lịch nước ngoài', '2025-10-08 00:06:16', '2025-10-08 00:06:16'),
(5, 'Du lịch biển', 'Các tour du lịch biển', '2025-10-08 00:06:16', '2025-10-08 00:06:16'),
(6, 'Du lịch núi', 'Các tour du lịch núi', '2025-10-08 00:06:16', '2025-10-08 00:06:16'),
(7, 'Du lịch văn hóa', 'Các tour du lịch văn hóa', '2025-10-08 00:06:16', '2025-10-08 00:06:16');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chats`
--

CREATE TABLE `chats` (
  `id` bigint NOT NULL,
  `booking_id` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` bigint NOT NULL,
  `chat_id` bigint NOT NULL,
  `sender_id` bigint NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `checkins_checkouts`
--

CREATE TABLE `checkins_checkouts` (
  `id` bigint NOT NULL,
  `booking_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `staff_id` bigint DEFAULT NULL,
  `checkin_time` timestamp NULL DEFAULT NULL,
  `checkout_time` timestamp NULL DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `note` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `checkin_checkout_details`
--

CREATE TABLE `checkin_checkout_details` (
  `id` bigint NOT NULL,
  `checkin_checkout_id` bigint NOT NULL,
  `passenger_name` varchar(100) NOT NULL,
  `passenger_type` varchar(20) DEFAULT NULL,
  `checkin_time` timestamp NULL DEFAULT NULL,
  `checkout_time` timestamp NULL DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `note` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `documents`
--

CREATE TABLE `documents` (
  `id` bigint NOT NULL,
  `booking_id` bigint NOT NULL,
  `file_url` varchar(1024) NOT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `employees`
--

CREATE TABLE `employees` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `employee_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hire_date` date NOT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `status` enum('active','inactive','terminated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `employee_code`, `name`, `email`, `phone`, `position`, `department`, `hire_date`, `salary`, `status`, `address`, `avatar`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 33, 'EMP001', 'Nguyễn Văn An', 'an.nguyen@tour365.vn', '0901234567', 'Giám đốc', 'IT', '2020-10-12', 50000000.00, 'active', '123 Đường ABC, Quận 1, TP.HCM', NULL, 1, '2025-10-11 21:09:31', '2025-10-11 21:19:11'),
(2, 34, 'EMP002', 'Trần Thị Bình', 'binh.tran@tour365.vn', '0901234568', 'Trưởng phòng Marketing', 'Marketing', '2022-10-12', 25000000.00, 'active', '456 Đường DEF, Quận 2, TP.HCM', NULL, 2, '2025-10-11 21:09:31', '2025-10-11 21:19:12'),
(3, 35, 'EMP003', 'Lê Văn Cường', 'cuong.le@tour365.vn', '0901234569', 'Nhân viên kinh doanh', 'Sales', '2023-10-12', 15000000.00, 'active', '789 Đường GHI, Quận 3, TP.HCM', NULL, 3, '2025-10-11 21:09:31', '2025-10-11 21:19:12'),
(4, 36, 'EMP004', 'Phạm Thị Dung', 'dung.pham@tour365.vn', '0901234570', 'Chuyên viên IT', 'IT', '2024-10-12', 18000000.00, 'active', '321 Đường JKL, Quận 4, TP.HCM', NULL, 3, '2025-10-11 21:09:31', '2025-10-11 21:19:12'),
(5, 37, 'EMP005', 'Hoàng Văn Em', 'em.hoang@tour365.vn', '0901234571', 'Nhân viên tài chính', 'Finance', '2025-04-12', 12000000.00, 'inactive', '654 Đường MNO, Quận 5, TP.HCM', NULL, 3, '2025-10-11 21:09:31', '2025-10-11 21:19:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint NOT NULL,
  `booking_id` bigint NOT NULL,
  `invoice_number` varchar(100) NOT NULL,
  `issue_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` decimal(12,2) NOT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_10_08_055045_check_and_add_user_columns', 1),
(2, '2025_10_08_073616_check_and_add_missing_user_fields', 2),
(3, '0001_01_01_000001_create_cache_table', 3),
(4, '2025_10_08_114329_fix_user_roles_table_structure', 4),
(5, '2025_10_08_133204_add_updated_at_to_tour_images_table', 5),
(6, '2025_10_10_155525_update_tours_status_enum', 6),
(7, '0001_01_01_000000_create_users_table', 1),
(8, '2025_01_27_000001_create_roles_table', 1),
(9, '2025_01_27_000002_create_user_roles_table', 1),
(10, '2025_01_27_000004_create_categories_table', 1),
(11, '2025_01_27_000005_create_tours_table', 1),
(12, '2025_01_27_000006_create_tour_images_table', 1),
(13, '2025_01_27_000007_create_tour_schedules_table', 1),
(14, '2025_01_27_000008_create_tour_departures_table', 1),
(15, '2025_01_27_000009_create_promotions_table', 1),
(16, '2025_01_27_000010_create_bookings_table', 1),
(17, '2025_01_27_000011_create_payments_table', 1),
(18, '2025_01_27_000012_create_invoices_table', 1),
(19, '2025_01_27_000013_create_reviews_table', 1),
(20, '2025_01_27_000014_create_chats_table', 1),
(21, '2025_01_27_000015_create_chat_messages_table', 1),
(22, '2025_01_27_000016_create_support_tickets_table', 1),
(23, '2025_01_27_000017_create_documents_table', 1),
(24, '2025_01_27_000018_create_user_history_table', 1),
(25, '2025_01_27_000019_create_notifications_table', 1),
(26, '2025_01_27_000020_create_wishlists_table', 1),
(27, '0001_01_01_000002_create_jobs_table', 7),
(28, '2025_01_27_000003_update_users_table', 1),
(29, '2025_09_29_052323_create_sessions_table_simple', 8),
(30, '2025_09_29_053335_add_phone_and_role_to_users_table', 8),
(31, '2025_09_29_053712_create_personal_access_tokens_table', 8),
(32, '2025_09_29_054013_add_phone_role_to_users', 8),
(33, '2025_09_29_163055_add_departure_date_and_image_to_tours_table', 8),
(34, '2025_09_29_174215_add_remember_token_to_users_table', 8),
(35, '2025_10_02_143529_add_image_column_to_tours_table', 8),
(36, '2025_10_06_063408_add_departure_date_and_image_to_tours_table', 1),
(37, '2025_10_08_054356_add_role_to_users_table', 1),
(38, '2025_10_10_153653_add_timestamps_to_bookings_table', 1),
(39, '2025_10_18_141220_create_banners_table', 9);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text,
  `type` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

CREATE TABLE `payments` (
  `id` bigint NOT NULL,
  `booking_id` bigint NOT NULL,
  `payment_method` varchar(20) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `transaction_code` varchar(200) DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `raw_response` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 38, 'auth_token', '4e502e5c3c3f85fc2af83515ce8b2e3d7b1c2ae9936f98b48358628da67adce0', '[\"*\"]', NULL, NULL, '2025-10-18 07:25:26', '2025-10-18 07:25:26'),
(2, 'App\\Models\\User', 38, 'auth_token', '54173d1274a57bcb8988edd7e211c7a6de8bdc85eb080ab74661410b8c8b9e7f', '[\"*\"]', NULL, NULL, '2025-10-18 07:25:43', '2025-10-18 07:25:43'),
(3, 'App\\Models\\User', 38, 'auth_token', 'cd518f998e109cd16be11a99424f0f95cd8c918dc03b9b423920f24657cf5ac0', '[\"*\"]', NULL, NULL, '2025-10-18 07:26:38', '2025-10-18 07:26:38'),
(4, 'App\\Models\\User', 38, 'auth_token', 'bcd23ba6d652f0a90ea7db9481e33f9a999cb01d8468b6a1434de51c6b8ed446', '[\"*\"]', NULL, NULL, '2025-10-18 07:31:15', '2025-10-18 07:31:15'),
(5, 'App\\Models\\User', 38, 'auth_token', 'fd4b3f35fed505a79cd36fdc1c7413441a294c940d5145ddde4a38a527624cce', '[\"*\"]', NULL, NULL, '2025-10-18 07:31:24', '2025-10-18 07:31:24'),
(6, 'App\\Models\\User', 38, 'auth_token', 'ae4b9e96b61b976d1ed87ce77941099fa4f28daa252671d1c1bba71cae12d101', '[\"*\"]', '2025-10-18 07:31:37', NULL, '2025-10-18 07:31:26', '2025-10-18 07:31:37'),
(7, 'App\\Models\\User', 38, 'auth_token', 'aad89a8f365dd4123040576dc9ea0ae96054302c063f3f5502f4858cfa0c869c', '[\"*\"]', '2025-10-18 07:33:45', NULL, '2025-10-18 07:32:31', '2025-10-18 07:33:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotions`
--

CREATE TABLE `promotions` (
  `id` bigint NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `description` text,
  `discount_percent` decimal(5,2) DEFAULT NULL,
  `discount_amount` decimal(12,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint NOT NULL,
  `tour_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `rating` int NOT NULL,
  `comment` text,
  `images` text,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` bigint NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator role', NULL, NULL),
(2, 'customer', 'Khách hàng', NULL, NULL),
(3, 'staff', 'Nhân viên có quyền quản lý tours, bookings và khách hàng', '2025-10-11 18:41:31', '2025-10-11 18:41:31');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0cqF3pSlrivNEB4l91BMBh2Jvr1TgHN3VydhX7BF', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.6584', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMW9QRzVheVRzY1RwRmhQUEZDVzlHanZ2cHlMQ21uWm9ycFN1dThocSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC90ZXN0LWVtcGxveWVlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1760243140),
('aH1Efqx4y9GUMYlKeTxTSsFDuhtqFZUfkYyJVt0W', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.6584', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUVZmZWFHZjZhTVU3a0xIdzBPTXh1Mk1yOHh0a1I2V0hFNGhhY0tLayI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2VtcGxveWVlL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1760244217),
('cb62dc3XnMCCgAqV8lYIf9GYGzbtM3LcttAKkngD', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUUFQcFIxdGRPRHdsbW9FbkRuZXRZSVBoNWFQQUlJR2d2cVVGSHl1ZSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2VycyI7fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1760244590),
('GkOviIjuA65WECYUr5hixNo7oTimnrzDt9fgi8zu', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.6584', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiZHBLb1l1eVZXd0E5VmJCUFFjc2tOaU5rZGZYbTg1T21xcFp2THBCZCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1760243166),
('JPxSezf8X1uy6fE2fyNB9uR6043fetluqxcCx1FM', 38, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiY1MwOGpRZjFRZlNBNzRZY2J1VThFN2tpek5SNG8zcTl0MTlSY2lYTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM4O30=', 1760797572),
('Kq4Xg0KHThZHktwD2irHXmh4mUmpJxAV1MMIPpid', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.6584', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTjlpZ2FLdnZScGRJMnNqaVI4cDFHU1Q2RlVLUEFscTlEVG5CY0xXTiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9lbXBsb3llZS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1760244038),
('W2JMNmMVds5o0WCYTcAksUhOQNGmvbkWbAC9rH2U', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQUpFRzYzbU1nUlJQc0twMDlkZ2lEc2QzSlpCN1JvNTI4OWRta1l5eSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1760239611),
('WNjJpG8EgJdDuLudrylRWTxnqOnV1RcLLtZcJxCO', 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.6584', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoid0pWczBHS1dxRUtkNzJoUUZSblo0eWk2YXNPZWRYY0FvRXdZelFzQSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MzM7czoxMToiZW1wbG95ZWVfaWQiO2k6MTtzOjEzOiJlbXBsb3llZV9uYW1lIjtzOjE2OiJOZ3V54buFbiBWxINuIEFuIjtzOjE1OiJlbXBsb3llZV9hdmF0YXIiO3M6NDc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9pbWFnZXMvZGVmYXVsdC1hdmF0YXIucG5nIjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MDoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2VtcGxveWVlL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1760244259),
('zhFYni9jiX1yD1HgRrJANC0VgffaMWNFSVmcLNDY', 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.6584', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiUDVENmdVUHNlNTJUR1BhWHl4U004VjhMWXRjTlV5bTB1aGNZck5ESCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MzM7czoxMToiZW1wbG95ZWVfaWQiO2k6MTtzOjEzOiJlbXBsb3llZV9uYW1lIjtzOjE2OiJOZ3V54buFbiBWxINuIEFuIjtzOjE1OiJlbXBsb3llZV9hdmF0YXIiO3M6NDc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9pbWFnZXMvZGVmYXVsdC1hdmF0YXIucG5nIjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MDoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2VtcGxveWVlL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1760243194),
('ziBQmhv22cEaWP5Ik39rjkU8mOCLNHdIuLU5Ow3U', 32, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUTZoMzNxWjJNVmgyZmRiSWg3ZmNhYWduOVZjRHNVS0hPQjN4ekNEbSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC90b3VycyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjMyO30=', 1760238309);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tours`
--

CREATE TABLE `tours` (
  `id` bigint NOT NULL,
  `category_id` bigint DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `description` text,
  `price` decimal(12,2) NOT NULL,
  `location` varchar(200) DEFAULT NULL,
  `duration_days` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `available_seats` int DEFAULT NULL,
  `departure_date` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','draft') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tours`
--

INSERT INTO `tours` (`id`, `category_id`, `title`, `short_description`, `description`, `price`, `location`, `duration_days`, `available_seats`, `departure_date`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Tour Singapore 2N1Đ', NULL, 'Tour du lịch 2 ngày 1 đêm tại Singapore', 1500000.00, NULL, '2', NULL, NULL, NULL, 'active', '2025-10-06 07:40:20', '2025-10-06 07:40:20'),
(2, 1, 'Tour Singapore 2N1Đ', NULL, 'Tour du lịch 2 ngày 1 đêm tại Singapore', 1500000.00, NULL, '2', NULL, NULL, NULL, 'active', '2025-10-06 07:40:22', '2025-10-06 07:40:22'),
(3, 1, 'Tour Singapore 2N1Đ', NULL, 'Tour du lịch 2 ngày 1 đêm tại Singapore', 1500000.00, NULL, '2', NULL, NULL, NULL, 'active', '2025-10-06 07:42:14', '2025-10-06 07:42:14'),
(4, 1, 'Tour  2N1Đ', NULL, 'Tour du lịch 2 ngày 1 đêm tại Singapore', 1500000.00, NULL, '2', NULL, NULL, NULL, 'active', '2025-10-06 07:43:13', '2025-10-06 07:43:48'),
(5, 1, 'Tour Hà Nội - Hạ Long - Sapa 5N4Đ', NULL, 'Khám phá vẻ đẹp của miền Bắc với Hà Nội cổ kính, vịnh Hạ Long huyền bí và Sapa mờ sương.', 3500000.00, NULL, '5', NULL, NULL, NULL, 'active', '2025-10-08 00:06:16', '2025-10-08 00:06:16'),
(6, 1, 'Tour Hà Nội - Hạ Long - Sapa 5N4Đ', NULL, 'Khám phá vẻ đẹp của miền Bắc với Hà Nội cổ kính, vịnh Hạ Long huyền bí và Sapa mờ sương.', 3500000.00, NULL, '5N4Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:11:06', '2025-10-08 00:11:06'),
(7, 1, 'Tour Hà Nội - Hạ Long - Sapa 5N4Đ', NULL, 'Khám phá vẻ đẹp của miền Bắc với Hà Nội cổ kính, vịnh Hạ Long huyền bí và Sapa mờ sương.', 3500000.00, NULL, '5N4Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:11:57', '2025-10-08 00:11:57'),
(8, 1, 'Tour Hà Nội - Hạ Long - Sapa 5N4Đ', NULL, 'Khám phá vẻ đẹp của miền Bắc với Hà Nội cổ kính, vịnh Hạ Long huyền bí và Sapa mờ sương.', 3500000.00, NULL, '5N4Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:12:23', '2025-10-08 00:12:23'),
(9, 1, 'Tour Đà Nẵng - Hội An - Huế 4N3Đ', NULL, 'Trải nghiệm miền Trung với Đà Nẵng hiện đại, Hội An cổ kính và Huế cố đô.', 2800000.00, NULL, '4N3Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:12:23', '2025-10-08 00:12:23'),
(10, 1, 'Tour TP.HCM - Cần Thơ - Cà Mau 3N2Đ', NULL, 'Khám phá miền Tây Nam Bộ với sông nước Cửu Long và văn hóa đặc trưng.', 1800000.00, NULL, '3N2Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:12:23', '2025-10-08 00:12:23'),
(11, 3, 'Tour Phú Quốc 3N2Đ', NULL, 'Thư giãn tại đảo ngọc Phú Quốc với bãi biển đẹp và hải sản tươi ngon.', 2200000.00, NULL, '3N2Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:12:23', '2025-10-08 00:12:23'),
(12, 3, 'Tour Nha Trang - Đà Lạt 4N3Đ', NULL, 'Kết hợp biển xanh Nha Trang và khí hậu mát mẻ Đà Lạt.', 2500000.00, NULL, '4N3Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:12:23', '2025-10-08 00:12:23'),
(14, 7, 'Tour Hà Nội - Hạ Long - Sapa 5N4Đ', NULL, 'Khám phá vẻ đẹp của miền Bắc với Hà Nội cổ kính, vịnh Hạ Long huyền bí và Sapa mờ sương.', 3500000.00, NULL, '5N4Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:14:05', '2025-10-08 00:14:05'),
(15, 7, 'Tour Đà Nẵng - Hội An - Huế 4N3Đ', NULL, 'Trải nghiệm miền Trung với Đà Nẵng hiện đại, Hội An cổ kính và Huế cố đô.', 2800000.00, NULL, '4N3Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:14:05', '2025-10-08 00:14:05'),
(16, 7, 'Tour TP.HCM - Cần Thơ - Cà Mau 3N2Đ', NULL, 'Khám phá miền Tây Nam Bộ với sông nước Cửu Long và văn hóa đặc trưng.', 1800000.00, NULL, '3N2Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:14:05', '2025-10-08 00:14:05'),
(17, 7, 'Tour Phú Quốc 3N2Đ', NULL, 'Thư giãn tại đảo ngọc Phú Quốc với bãi biển đẹp và hải sản tươi ngon.', 2200000.00, NULL, '3N2Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:14:05', '2025-10-08 00:14:05'),
(18, 7, 'Tour Nha Trang - Đà Lạt 4N3Đ', NULL, 'Kết hợp biển xanh Nha Trang và khí hậu mát mẻ Đà Lạt.', 2500000.00, NULL, '4N3Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:14:05', '2025-10-08 00:14:05'),
(19, 6, 'Tour Thái Lan - Bangkok - Pattaya 5N4Đ', NULL, 'Khám phá Thái Lan với Bangkok sôi động và Pattaya biển xanh.', 4500000.00, NULL, '5N4Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:14:05', '2025-10-08 00:14:05'),
(20, 6, 'Tour Singapore - Malaysia 4N3Đ', NULL, 'Trải nghiệm Singapore hiện đại và Malaysia đa văn hóa.', 5200000.00, NULL, '4N3Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:14:05', '2025-10-08 00:14:05'),
(21, 6, 'Tour Sapa - Fansipan 3N2Đ', NULL, 'Chinh phục đỉnh Fansipan và khám phá văn hóa dân tộc Sapa.', 1900000.00, NULL, '3N2Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:14:05', '2025-10-08 00:14:05'),
(22, 6, 'Tour Đà Lạt - Langbiang 2N1Đ', NULL, 'Tham quan Đà Lạt và leo núi Langbiang với view đẹp.', 1200000.00, NULL, '2N1Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:14:05', '2025-10-08 00:14:05'),
(23, 7, 'Tour Huế - Cố đô 2N1Đ', NULL, 'Khám phá cố đô Huế với lăng tẩm, đền đài và văn hóa cung đình.', 1500000.00, NULL, '2N1Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:14:05', '2025-10-08 00:14:05'),
(24, 7, 'Tour Hội An - Phố cổ 2N1Đ', NULL, 'Trải nghiệm phố cổ Hội An với đèn lồng và ẩm thực đặc sắc.', 1300000.00, NULL, '2N1Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:14:05', '2025-10-08 00:14:05'),
(25, 7, 'Tour Mù Cang Chải - Ruộng bậc thang 3N2Đ', NULL, 'Ngắm ruộng bậc thang Mù Cang Chải vào mùa lúa chín vàng.', 2100000.00, NULL, '3N2Đ', NULL, NULL, NULL, 'active', '2025-10-08 00:14:05', '2025-10-08 00:14:05'),
(31, 3, '1233', NULL, '231131dshbsfhsajfhksfjakjfhhh jshkjksjk sjahjha húhfiahf àuhyqw7hfdds úadfgsfbh', 6000000.00, NULL, '3', NULL, NULL, NULL, 'draft', '2025-10-10 08:58:21', '2025-10-10 08:58:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_departures`
--

CREATE TABLE `tour_departures` (
  `id` bigint NOT NULL,
  `tour_id` bigint NOT NULL,
  `departure_date` date NOT NULL,
  `seats_total` int DEFAULT NULL,
  `seats_available` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_departures`
--

INSERT INTO `tour_departures` (`id`, `tour_id`, `departure_date`, `seats_total`, `seats_available`, `created_at`, `updated_at`) VALUES
(5, 21, '2025-10-17', 20, 20, '2025-10-10 09:01:28', '2025-10-10 09:01:28'),
(8, 31, '2025-10-17', 20, 20, '2025-10-10 09:04:20', '2025-10-10 09:04:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_images`
--

CREATE TABLE `tour_images` (
  `id` bigint NOT NULL,
  `tour_id` bigint NOT NULL,
  `image_url` varchar(1024) NOT NULL,
  `is_cover` tinyint(1) DEFAULT NULL,
  `sort_order` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_images`
--

INSERT INTO `tour_images` (`id`, `tour_id`, `image_url`, `is_cover`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 8, 'https://picsum.photos/800/600?random=81', 1, 1, '2025-10-08 00:12:23', NULL),
(2, 8, 'https://picsum.photos/800/600?random=82', 0, 2, '2025-10-08 00:12:23', NULL),
(3, 9, 'https://picsum.photos/800/600?random=91', 1, 1, '2025-10-08 00:12:23', NULL),
(4, 9, 'https://picsum.photos/800/600?random=92', 0, 2, '2025-10-08 00:12:23', NULL),
(5, 10, 'https://picsum.photos/800/600?random=101', 1, 1, '2025-10-08 00:12:23', NULL),
(6, 10, 'https://picsum.photos/800/600?random=102', 0, 2, '2025-10-08 00:12:23', NULL),
(7, 11, 'https://picsum.photos/800/600?random=111', 1, 1, '2025-10-08 00:12:23', NULL),
(8, 11, 'https://picsum.photos/800/600?random=112', 0, 2, '2025-10-08 00:12:23', NULL),
(9, 12, 'https://picsum.photos/800/600?random=121', 1, 1, '2025-10-08 00:12:23', NULL),
(10, 12, 'https://picsum.photos/800/600?random=122', 0, 2, '2025-10-08 00:12:23', NULL),
(11, 14, 'https://picsum.photos/800/600?random=141', 1, 1, '2025-10-08 00:14:05', NULL),
(12, 14, 'https://picsum.photos/800/600?random=142', 0, 2, '2025-10-08 00:14:05', NULL),
(13, 15, 'https://picsum.photos/800/600?random=151', 1, 1, '2025-10-08 00:14:05', NULL),
(14, 15, 'https://picsum.photos/800/600?random=152', 0, 2, '2025-10-08 00:14:05', NULL),
(15, 16, 'https://picsum.photos/800/600?random=161', 1, 1, '2025-10-08 00:14:05', NULL),
(16, 16, 'https://picsum.photos/800/600?random=162', 0, 2, '2025-10-08 00:14:05', NULL),
(17, 17, 'https://picsum.photos/800/600?random=171', 1, 1, '2025-10-08 00:14:05', NULL),
(18, 17, 'https://picsum.photos/800/600?random=172', 0, 2, '2025-10-08 00:14:05', NULL),
(19, 18, 'https://picsum.photos/800/600?random=181', 1, 1, '2025-10-08 00:14:05', NULL),
(20, 18, 'https://picsum.photos/800/600?random=182', 0, 2, '2025-10-08 00:14:05', NULL),
(21, 19, 'https://picsum.photos/800/600?random=191', 1, 1, '2025-10-08 00:14:05', NULL),
(22, 19, 'https://picsum.photos/800/600?random=192', 0, 2, '2025-10-08 00:14:05', NULL),
(23, 20, 'https://picsum.photos/800/600?random=201', 1, 1, '2025-10-08 00:14:05', NULL),
(24, 20, 'https://picsum.photos/800/600?random=202', 0, 2, '2025-10-08 00:14:05', NULL),
(25, 21, 'https://picsum.photos/800/600?random=211', 1, 1, '2025-10-08 00:14:05', NULL),
(26, 21, 'https://picsum.photos/800/600?random=212', 0, 2, '2025-10-08 00:14:05', NULL),
(27, 22, 'https://picsum.photos/800/600?random=221', 1, 1, '2025-10-08 00:14:05', NULL),
(28, 22, 'https://picsum.photos/800/600?random=222', 0, 2, '2025-10-08 00:14:05', NULL),
(29, 23, 'https://picsum.photos/800/600?random=231', 1, 1, '2025-10-08 00:14:05', NULL),
(30, 23, 'https://picsum.photos/800/600?random=232', 0, 2, '2025-10-08 00:14:05', NULL),
(31, 24, 'https://picsum.photos/800/600?random=241', 1, 1, '2025-10-08 00:14:05', NULL),
(32, 24, 'https://picsum.photos/800/600?random=242', 0, 2, '2025-10-08 00:14:05', NULL),
(33, 25, 'https://picsum.photos/800/600?random=251', 1, 1, '2025-10-08 00:14:05', NULL),
(34, 25, 'https://picsum.photos/800/600?random=252', 0, 2, '2025-10-08 00:14:05', NULL),
(36, 31, '/storage/tours/JzhjHt0m84BLBoP8fduHOkaLOiLk6QDHHViFp6Ws.png', 1, 1, '2025-10-10 08:58:21', '2025-10-10 08:58:21'),
(37, 31, '/storage/tours/RncJaUlHOJqIoK8yiXjwn7kuOJGuaUQlU9eWTWZq.png', 0, 2, '2025-10-10 09:03:43', '2025-10-10 09:03:43'),
(38, 31, '/storage/tours/BSqUYBL6E9hgDK4gaWr5QFZHjR6clwUCoLPEjqhU.png', 0, 3, '2025-10-10 09:04:20', '2025-10-10 09:04:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_schedules`
--

CREATE TABLE `tour_schedules` (
  `id` bigint NOT NULL,
  `tour_id` bigint NOT NULL,
  `day_number` int DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_schedules`
--

INSERT INTO `tour_schedules` (`id`, `tour_id`, `day_number`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, 'HCM-Singapore-Merlio(ăn trưa- ăn tối)-kuala', '', '2025-10-06 07:42:14', '2025-10-06 07:42:14'),
(2, 3, NULL, 'kuala-HCM', 'Quay về', '2025-10-06 07:42:14', '2025-10-06 07:42:14'),
(5, 4, NULL, 'HCM-Singapore-Merlio(ăn trưa- ăn tối)-kuala', '', '2025-10-06 07:43:48', '2025-10-06 07:43:48'),
(6, 4, NULL, 'kuala-HCM', 'Quay về', '2025-10-06 07:43:48', '2025-10-06 07:43:48'),
(22, 31, NULL, 'từ Hn đến Đn', 'sjadnjad', '2025-10-10 09:04:20', '2025-10-10 09:04:20'),
(23, 31, NULL, 'VNNNNN', 'hbhsaba', '2025-10-10 09:04:20', '2025-10-10 09:04:20'),
(24, 31, NULL, 'ạnah', 'jasjja', '2025-10-10 09:04:20', '2025-10-10 09:04:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','staff','customer') NOT NULL DEFAULT 'customer',
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `password`, `remember_token`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@example.com', 'customer', '$2y$12$iAXmmn2k5Cy6bgmX.k.Vbe3Lmd7BtnGryp4pMrcLcm1zQRaE0jCRS', '8tElIHve6q1QTWXBVNGT8g9FvHZg5w2Vsy5lfrYfSxABu5BbjQl4l8xNZeo0', '0338996868', 'Hà Nội', '2025-10-03 01:14:34', '2025-10-12 04:48:24'),
(4, 'huyen', 'huyen123@gmail.com', 'customer', '$2y$12$bynl6/fArlV9l6bUeWh66.hqs7mtc0SCH8Yar.0sZXcjBCQsfPvGW', NULL, NULL, NULL, '2025-10-03 07:28:36', '2025-10-03 07:28:36'),
(5, 'Hoàng Quốc Doanh', 'hoangquocdoanh6686@gmail.com', 'customer', '$2y$12$GctPCv9/2Lf63IwOTYZ0OOCii3riMiUb4nO.4XswLhT3w4HMJEQHq', NULL, '0879273804', NULL, '2025-10-07 17:05:54', '2025-10-08 00:13:41'),
(10, 'Hoàng Quốc Doanh', 'hoangquocdoanh66861@gmail.com', 'admin', '$2y$12$jMzw58fX9VxqU6cCh.1sEO0a8o2f2fAklRrqTc3.psTt82if.sRDW', NULL, '0879273804', 'hà nội', '2025-10-07 23:08:38', '2025-10-08 03:47:06'),
(21, 'Hoàng Quốc Doanh', 'admin12345611@gmail.com', 'customer', '$2y$12$fRV6MrtnM5GpTpBNfX3uAubds2AWO4fEaPx0J4GSKVTiq.sBYia1G', NULL, NULL, NULL, '2025-10-08 04:48:39', '2025-10-08 04:48:39'),
(29, 'Hoàng Quốc Doanh', 'hiep66778800@gmail.com', 'customer', '$2y$12$0iPrnWMZ3HC4KSffNklmAe940XM.YrzJzN6RKh5g94/avJHzrYK6W', NULL, '0879273804', 'phú tàng', '2025-10-08 22:18:19', '2025-10-08 22:18:19'),
(30, 'iPhone 15 Pro', 'truong@gmail.com', 'customer', '$2y$12$nK2.TYm.xUV3/P1i8BIYc.yhTIQFwWYlSa9upXcrsTbjM0jjIttB.', 'hRZwpP6j8Hl85h2aXuLsENT5Raxq3b77zWLIKLDjeOxOjYugRTwbsxD7G8iS', '0357168655', 'HN', '2025-10-11 18:29:22', '2025-10-12 04:34:20'),
(32, 'iPhone 15 Pro Max', 'truong123@gmail.com', 'customer', '$2y$12$9gqpbgs/guo2e3zoL3j2a.FevOv3jvsfaV40ukCxmQyRYt2fUWMeq', 'ey2DisqarTwAKNFJI1r4ecQpP68U1GiKuJit42f7TGLpSyEqbd3HQL37Q097', '0444555666', 'HN', '2025-10-11 18:50:58', '2025-10-12 04:12:21'),
(33, 'Nguyễn Văn An', 'an.nguyen@tour365.vn', 'customer', '$2y$12$QbVTcGgKIT3wNnC7iTR.zeB7iAhfNkG399YHDFsx3JD5D89R78382', NULL, NULL, NULL, '2025-10-11 21:19:11', '2025-10-11 21:19:11'),
(34, 'Trần Thị Bình', 'binh.tran@tour365.vn', 'customer', '$2y$12$Y3vERVSbCJeuBhRDr93Md.dr9bXqIiSl/CP61vHjlONzFsCW6MMB2', NULL, NULL, NULL, '2025-10-11 21:19:12', '2025-10-11 21:19:12'),
(35, 'Lê Văn Cường', 'cuong.le@tour365.vn', 'customer', '$2y$12$DbGIl9YJlxrhp0Uwde3b.uAbsPafKXC0EQWsdIZ3QojwOaA.d7IO.', NULL, NULL, NULL, '2025-10-11 21:19:12', '2025-10-11 21:19:12'),
(36, 'Phạm Thị Dung', 'dung.pham@tour365.vn', 'customer', '$2y$12$oF5geAbqikp2h2MJnfyKVO6F9u7fNLZlGLuH/yXehLonXK4KimV3O', NULL, NULL, NULL, '2025-10-11 21:19:12', '2025-10-11 21:19:12'),
(37, 'Hoàng Văn Em', 'em.hoang@tour365.vn', 'customer', '$2y$12$ECIST7J5agTyrMNFo7jXjeOvmGY3IdyhF27x45ZqJf8ZIt8IkaJNu', NULL, NULL, NULL, '2025-10-11 21:19:12', '2025-10-11 21:19:12'),
(38, 'Đỗ Văn Vũ', 'vudevweb@gmail.com', 'customer', '$2y$12$O7vyPRudtrnpIgZjtcH14.1l9BxKFViNgcA9LA/DeNwhYRp5yiGdm', NULL, '0779440918', '3', '2025-10-18 07:18:06', '2025-10-18 07:18:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_history`
--

CREATE TABLE `user_history` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `role_id` bigint NOT NULL,
  `assigned_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `assigned_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-10-03 01:14:34', NULL, NULL),
(2, 21, 2, '2025-10-08 11:48:39', '2025-10-08 04:48:39', '2025-10-08 04:48:39'),
(3, 5, 1, '2025-10-08 05:14:33', '2025-10-08 05:14:33', '2025-10-08 05:14:33'),
(9, 10, 1, '2025-10-08 07:49:03', NULL, NULL),
(10, 29, 2, '2025-10-09 05:18:19', NULL, NULL),
(11, 30, 2, '2025-10-11 18:29:22', NULL, NULL),
(14, 32, 3, '2025-10-11 21:12:59', NULL, NULL),
(15, 34, 2, '2025-10-11 21:48:20', NULL, NULL),
(16, 38, 1, '2025-10-18 14:18:06', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `tour_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `banners_is_active_type_index` (`is_active`,`type`),
  ADD KEY `banners_start_date_end_date_index` (`start_date`,`end_date`),
  ADD KEY `banners_sort_order_index` (`sort_order`);

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `departure_id` (`departure_id`),
  ADD KEY `promotion_id` (`promotion_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Chỉ mục cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_id` (`chat_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Chỉ mục cho bảng `checkins_checkouts`
--
ALTER TABLE `checkins_checkouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Chỉ mục cho bảng `checkin_checkout_details`
--
ALTER TABLE `checkin_checkout_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `checkin_checkout_id` (`checkin_checkout_id`);

--
-- Chỉ mục cho bảng `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Chỉ mục cho bảng `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Chỉ mục cho bảng `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `tour_departures`
--
ALTER TABLE `tour_departures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `tour_images`
--
ALTER TABLE `tour_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `tour_schedules`
--
ALTER TABLE `tour_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `user_history`
--
ALTER TABLE `user_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Chỉ mục cho bảng `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_tour` (`user_id`,`tour_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `chats`
--
ALTER TABLE `chats`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `checkins_checkouts`
--
ALTER TABLE `checkins_checkouts`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `checkin_checkout_details`
--
ALTER TABLE `checkin_checkout_details`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tours`
--
ALTER TABLE `tours`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT cho bảng `tour_departures`
--
ALTER TABLE `tour_departures`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `tour_images`
--
ALTER TABLE `tour_images`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT cho bảng `tour_schedules`
--
ALTER TABLE `tour_schedules`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT cho bảng `user_history`
--
ALTER TABLE `user_history`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`departure_id`) REFERENCES `tour_departures` (`id`),
  ADD CONSTRAINT `bookings_ibfk_4` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`),
  ADD CONSTRAINT `bookings_ibfk_5` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`);

--
-- Ràng buộc cho bảng `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Ràng buộc cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`),
  ADD CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

--
-- Ràng buộc cho bảng `checkins_checkouts`
--
ALTER TABLE `checkins_checkouts`
  ADD CONSTRAINT `checkins_checkouts_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `checkins_checkouts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `checkins_checkouts_ibfk_3` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`);

--
-- Ràng buộc cho bảng `checkin_checkout_details`
--
ALTER TABLE `checkin_checkout_details`
  ADD CONSTRAINT `checkin_checkout_details_ibfk_1` FOREIGN KEY (`checkin_checkout_id`) REFERENCES `checkins_checkouts` (`id`);

--
-- Ràng buộc cho bảng `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Ràng buộc cho bảng `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Ràng buộc cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ràng buộc cho bảng `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ràng buộc cho bảng `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ràng buộc cho bảng `tours`
--
ALTER TABLE `tours`
  ADD CONSTRAINT `tours_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Ràng buộc cho bảng `tour_departures`
--
ALTER TABLE `tour_departures`
  ADD CONSTRAINT `tour_departures_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);

--
-- Ràng buộc cho bảng `tour_images`
--
ALTER TABLE `tour_images`
  ADD CONSTRAINT `tour_images_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);

--
-- Ràng buộc cho bảng `tour_schedules`
--
ALTER TABLE `tour_schedules`
  ADD CONSTRAINT `tour_schedules_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);

--
-- Ràng buộc cho bảng `user_history`
--
ALTER TABLE `user_history`
  ADD CONSTRAINT `user_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ràng buộc cho bảng `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Ràng buộc cho bảng `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
