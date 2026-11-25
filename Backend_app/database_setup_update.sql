-- VMAX Software - Database Update Schema for Giai đoạn 4

CREATE TABLE IF NOT EXISTS `pages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) UNIQUE NOT NULL,
  `content_html` LONGTEXT,
  `content_json` LONGTEXT, -- Stores the JSON structure of GrapeJS blocks
  `is_published` TINYINT DEFAULT 0,
  `created_at` INT NOT NULL,
  `updated_at` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
