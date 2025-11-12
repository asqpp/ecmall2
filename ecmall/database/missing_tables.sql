-- =====================================================
-- Missing Core Tables & Seed Data
-- Run this script if your cybor432_erpnew database
-- does not already contain the authentication tables.
-- =====================================================

USE `cybor432_erpnew`;

START TRANSACTION;

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `permissions` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(191) NOT NULL,
  `username` varchar(191) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `first_name` varchar(191) DEFAULT NULL,
  `last_name` varchar(191) DEFAULT NULL,
  `role_id` int(10) UNSIGNED DEFAULT NULL,
  `branch_id` int(10) UNSIGNED DEFAULT NULL,
  `usertype` varchar(191) DEFAULT 'USER',
  `phone` varchar(191) DEFAULT NULL,
  `avatar_url` text DEFAULT NULL,
  `status` varchar(191) DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`, `description`, `permissions`)
VALUES
  (1, 'Admin', 'System Administrator', '{"all": true}'),
  (2, 'Manager', 'Branch Manager', '{"manage_users": true, "view_reports": true}'),
  (3, 'Accountant', 'Accountant', '{"manage_accounts": true, "view_reports": true}'),
  (4, 'User', 'Regular User', '{"view_own_data": true}')
ON DUPLICATE KEY UPDATE
  `description` = VALUES(`description`),
  `permissions` = VALUES(`permissions`);

INSERT INTO `users` (`code`, `username`, `email`, `password`, `first_name`, `last_name`, `role_id`, `usertype`, `status`)
VALUES
  ('USR-2025-0001', 'admin', 'admin@example.com', '$2y$10$hIB4Etgi98K5TemqY5i/AuQ2QruojMpbqMyPsUDr9ePs0Fu9Dzq7K', 'Admin', 'User', 1, 'ADMIN', 'active')
ON DUPLICATE KEY UPDATE
  `email` = VALUES(`email`),
  `password` = VALUES(`password`),
  `role_id` = VALUES(`role_id`),
  `status` = VALUES(`status`);

-- Ensure invoice monetary columns exist
ALTER TABLE `invoice`
  ADD COLUMN IF NOT EXISTS `total_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00 AFTER `grand_total`;

ALTER TABLE `invoice`
  ADD COLUMN IF NOT EXISTS `paid_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00 AFTER `total_amount`;

ALTER TABLE `invoice`
  ADD COLUMN IF NOT EXISTS `due_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00 AFTER `paid_amount`;

ALTER TABLE `invoice`
  ADD COLUMN IF NOT EXISTS `sales_date` DATETIME NULL AFTER `sales_by`;

ALTER TABLE `invoice`
  ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

UPDATE `invoice`
SET `total_amount` = `grand_total`
WHERE `total_amount` = 0;

UPDATE `invoice`
SET `due_amount` = GREATEST(`grand_total` - `paid_amount`, 0);

COMMIT;
