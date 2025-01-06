CREATE TABLE IF NOT EXISTS `consult` (
    `id` bigint(20) unsigned  COLLATE utf8mb4_unicode_ci AUTO_INCREMENT,
    -- 基本資料
    `name` VARCHAR(200) COLLATE utf8mb4_unicode_ci default '',
    `gender` VARCHAR(10) COLLATE utf8mb4_unicode_ci default '',
    `birthday` VARCHAR(10) COLLATE utf8mb4_unicode_ci default '',
    `phone` VARCHAR(60) COLLATE utf8mb4_unicode_ci default '',
    `email` VARCHAR(100) COLLATE utf8mb4_unicode_ci default '',
    `address` TEXT,
    `emergency_contact` VARCHAR(200)  COLLATE utf8mb4_unicode_ci default '',
    `emergency_contact_phone` VARCHAR(60)  COLLATE utf8mb4_unicode_ci default '',
    `emergency_contact_relation` VARCHAR(200)  COLLATE utf8mb4_unicode_ci default '',
    -- 來源資訊
    `referral_source` VARCHAR(200)  COLLATE utf8mb4_unicode_ci default '',
    `referral_source_other` TEXT,
    -- 健康資訊
    `health_condition` VARCHAR(200)  COLLATE utf8mb4_unicode_ci default '',
    `health_condition_other` TEXT,
    -- 帳號資訊
    `account_status` VARCHAR(10) COLLATE utf8mb4_unicode_ci default '',
    `profile_photo_url` VARCHAR(255) COLLATE utf8mb4_unicode_ci default '',
    `active_branch` VARCHAR(100) COLLATE utf8mb4_unicode_ci default '',
    `id_number` VARCHAR(60) COLLATE utf8mb4_unicode_ci default '',
    `password_hash` VARCHAR(255)  COLLATE utf8mb4_unicode_ci default '',
    -- 偏好設定
    `contact_time` VARCHAR(40)  COLLATE utf8mb4_unicode_ci default '',
    -- 運動目標（使用 JSON 儲存多個選項）
    `fitness_goals` JSON,
    -- 推薦資訊
    `referrer_name` VARCHAR(200) COLLATE utf8mb4_unicode_ci default '',
    -- 身體資訊
    `height` VARCHAR(60) COLLATE utf8mb4_unicode_ci default '', -- 公分
    `weight` VARCHAR(60) COLLATE utf8mb4_unicode_ci default '', -- 公斤
    -- 發票資訊
    `default_invoice_type` VARCHAR(20) COLLATE utf8mb4_unicode_ci default '',
    `mobile_barcode` VARCHAR(60) COLLATE utf8mb4_unicode_ci default '',
    `company_tax_id` VARCHAR(60) COLLATE utf8mb4_unicode_ci default '',
    `company_name` VARCHAR(200) COLLATE utf8mb4_unicode_ci default '',
    -- 時間戳記和操作者記錄
    `created_at` TIMESTAMP  DEFAULT CURRENT_TIMESTAMP,
    `created_by` bigint(20) unsigned,
    `updated_at` TIMESTAMP  DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated_by` bigint(20) unsigned,
    `deleted_at` TIMESTAMP NULL,
    `deleted_by` bigint(20) unsigned,
    -- 索引
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE='utf8mb4_unicode_ci';
