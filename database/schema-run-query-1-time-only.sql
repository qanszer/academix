CREATE DATABASE IF NOT EXISTS academix_db;
USE academix_db;

-- 1. Roles Table
CREATE TABLE `ROLES` (
  `role_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `role_name` VARCHAR(50) UNIQUE NOT NULL,
  `description` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Users Table
CREATE TABLE `USERS` (
  `user_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `role_id` INT UNSIGNED NOT NULL,
  `student_id` VARCHAR(32) UNIQUE,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) UNIQUE NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `account_status` ENUM('Pending', 'Active', 'Suspended', 'Deactivated') NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `ROLES` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Document Types Table
CREATE TABLE `DOCUMENT_TYPES` (
  `document_type_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `document_name` VARCHAR(150) NOT NULL,
  `description` TEXT,
  `fee` DECIMAL(10,2) DEFAULT 0.00 CHECK (`fee` >= 0.00),
  `is_active` BOOLEAN NOT NULL,
  `created_at` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Document Requests Table
CREATE TABLE `DOCUMENT_REQUESTS` (
  `request_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `student_user_id` INT UNSIGNED NOT NULL,
  `document_type_id` INT UNSIGNED NOT NULL,
  `assigned_staff_user_id` INT UNSIGNED DEFAULT NULL,
  `request_status` ENUM('Pending', 'Processing', 'Ready', 'Completed', 'Rejected') NOT NULL,
  `purpose` TEXT,
  `quantity` INT UNSIGNED DEFAULT 1 CHECK (`quantity` > 0),
  `submitted_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `completed_at` DATETIME DEFAULT NULL,
  CONSTRAINT `fk_request_student` FOREIGN KEY (`student_user_id`) REFERENCES `USERS` (`user_id`),
  CONSTRAINT `fk_request_doc_type` FOREIGN KEY (`document_type_id`) REFERENCES `DOCUMENT_TYPES` (`document_type_id`),
  CONSTRAINT `fk_request_staff` FOREIGN KEY (`assigned_staff_user_id`) REFERENCES `USERS` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Request Status History Table
CREATE TABLE `REQUEST_STATUS_HISTORY` (
  `history_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `request_id` INT UNSIGNED NOT NULL,
  `changed_by_user_id` INT UNSIGNED DEFAULT NULL,
  `previous_status` ENUM('Pending', 'Processing', 'Ready', 'Completed', 'Rejected') DEFAULT NULL,
  `new_status` ENUM('Pending', 'Processing', 'Ready', 'Completed', 'Rejected') NOT NULL,
  `change_note` TEXT,
  `changed_at` DATETIME NOT NULL,
  CONSTRAINT `fk_history_request` FOREIGN KEY (`request_id`) REFERENCES `DOCUMENT_REQUESTS` (`request_id`),
  CONSTRAINT `fk_history_user` FOREIGN KEY (`changed_by_user_id`) REFERENCES `USERS` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Messages Table
CREATE TABLE `MESSAGES` (
  `message_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `request_id` INT UNSIGNED NOT NULL,
  `sender_user_id` INT UNSIGNED NOT NULL,
  `message_body` TEXT NOT NULL,
  `sent_at` DATETIME NOT NULL,
  CONSTRAINT `fk_message_request` FOREIGN KEY (`request_id`) REFERENCES `DOCUMENT_REQUESTS` (`request_id`),
  CONSTRAINT `fk_message_sender` FOREIGN KEY (`sender_user_id`) REFERENCES `USERS` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Notifications Table
CREATE TABLE `NOTIFICATIONS` (
  `notification_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `recipient_user_id` INT UNSIGNED NOT NULL,
  `request_id` INT UNSIGNED DEFAULT NULL,
  `notification_type` VARCHAR(80) NOT NULL,
  `notification_message` TEXT NOT NULL,
  `is_read` BOOLEAN DEFAULT FALSE NOT NULL,
  `created_at` DATETIME NOT NULL,
  CONSTRAINT `fk_notification_recipient` FOREIGN KEY (`recipient_user_id`) REFERENCES `USERS` (`user_id`),
  CONSTRAINT `fk_notification_request` FOREIGN KEY (`request_id`) REFERENCES `DOCUMENT_REQUESTS` (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;