-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2026 at 01:45 PM
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
-- Database: `academix_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `document_requests`
--

CREATE TABLE `document_requests` (
  `request_id` int(10) UNSIGNED NOT NULL,
  `student_user_id` int(10) UNSIGNED NOT NULL,
  `document_type_id` int(10) UNSIGNED NOT NULL,
  `assigned_staff_user_id` int(10) UNSIGNED DEFAULT NULL,
  `request_status` enum('Pending','Processing','Ready','Completed','Rejected') NOT NULL,
  `purpose` text DEFAULT NULL,
  `quantity` int(10) UNSIGNED DEFAULT 1 CHECK (`quantity` > 0),
  `submitted_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_requests`
--

INSERT INTO `document_requests` (`request_id`, `student_user_id`, `document_type_id`, `assigned_staff_user_id`, `request_status`, `purpose`, `quantity`, `submitted_at`, `updated_at`, `completed_at`) VALUES
(1, 6, 5, 2, 'Processing', 'Employment application requirement', 2, '2026-11-01 20:20:04', '2026-11-03 20:20:04', NULL),
(2, 3, 20, 2, 'Ready', 'Scholarship grant submission', 1, '2026-10-30 20:20:04', '2026-11-02 20:20:04', NULL),
(3, 4, 1, NULL, 'Pending', 'Personal evaluation and record check', 1, '2026-11-04 20:20:04', '2026-11-04 20:20:04', NULL),
(4, 5, 3, 2, 'Completed', 'Board exam application requirement', 1, '2026-10-28 20:21:36', '2026-10-31 20:21:36', '2026-10-31 20:21:36');

-- --------------------------------------------------------

--
-- Table structure for table `document_types`
--

CREATE TABLE `document_types` (
  `document_type_id` int(10) UNSIGNED NOT NULL,
  `document_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `fee` decimal(10,2) DEFAULT 0.00 CHECK (`fee` >= 0.00),
  `is_active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_types`
--

INSERT INTO `document_types` (`document_type_id`, `document_name`, `description`, `fee`, `is_active`, `created_at`) VALUES
(1, 'True Copy of Grades', 'A copy of your grades, certified as a true copy by the Registrar.', 100.00, 1, '2026-11-04 20:04:39'),
(2, 'AESAR (Automated Evaluation of Students Academic Records)', 'An automated summary of your academic standing and the subjects you still need to complete.', 150.00, 1, '2026-11-04 20:04:39'),
(3, 'Transfer Credential Package (Honorable Dismissal) Undergraduate', 'Everything a receiving school asks for when you transfer, issued together as one set.', 400.00, 1, '2026-11-04 20:04:39'),
(4, 'Transcript of Records - For Abroad OTR (Undergraduate)', 'Official transcript for study or work outside the Philippines.', 350.00, 1, '2026-11-04 20:04:39'),
(5, 'Transcript of Records - For Employment OTR (Undergraduate)', 'Official transcript to submit to an employer.', 300.00, 1, '2026-11-04 20:04:39'),
(6, 'Transcript of Records - For Evaluation', 'Transcript for evaluation by another school or office.', 300.00, 1, '2026-11-04 20:04:39'),
(7, 'Transcript of Records - For Evaluation (Application for Bachelor of Laws Entrance Exam)', 'Transcript for the Bachelor of Laws entrance exam application.', 320.00, 1, '2026-11-04 20:04:39'),
(8, 'Transcript of Records - For Evaluation (Application for Graduate Studies)', 'Transcript for a graduate school application.', 320.00, 1, '2026-11-04 20:04:39'),
(9, 'Transcript of Records - For International Evaluation', 'Transcript sent to an international credential evaluation agency.', 450.00, 1, '2026-11-04 20:04:39'),
(10, 'Transcript of Records - For OJT', 'Transcript for on-the-job training or internship placement.', 200.00, 1, '2026-11-04 20:04:39'),
(11, 'Transcript of Records - For Professional Regulations Commission (PRC - Undergraduate)', 'Transcript for a PRC licensure exam application.', 300.00, 1, '2026-11-04 20:04:39'),
(12, 'Transcript of Records - For Scholarship Application', 'Transcript to submit with a scholarship application.', 150.00, 1, '2026-11-04 20:04:39'),
(13, 'Certificate of Class Ranking (Undergraduate)', 'States your rank within your graduating class or program.', 120.00, 1, '2026-11-04 20:04:39'),
(14, 'Certificate of Clock Hours (Undergraduate)', 'Lists the contact hours of the subjects you took.', 100.00, 1, '2026-11-04 20:04:39'),
(15, 'Certificate of Correction of Name/Birth Date/Place of Birth', 'Confirms a corrected entry in your school records.', 250.00, 1, '2026-11-04 20:04:39'),
(16, 'Certificate of Eligibility (C1) for Law Students', 'Confirms you meet the requirements to enter law school.', 150.00, 1, '2026-11-04 20:04:39'),
(17, 'Certificate of English Course Taken', 'Lists the English courses you completed.', 100.00, 1, '2026-11-04 20:04:39'),
(18, 'Certificate of Equivalency (UAE)', 'States the equivalency of your program for use in the UAE.', 300.00, 1, '2026-11-04 20:04:39'),
(19, 'Certificate of General Weighted Average (Undergraduate)', 'States your general weighted average.', 100.00, 1, '2026-11-04 20:04:39'),
(20, 'Certificate of Good Moral Character', 'States that you have no record of a disciplinary offense.', 150.00, 1, '2026-11-04 20:04:39'),
(21, 'Certificate of Grading System', 'Explains the grading scale the university uses.', 80.00, 1, '2026-11-04 20:04:39'),
(22, 'Certificate of Medium of Instruction', 'States the language your courses were taught in.', 100.00, 1, '2026-11-04 20:04:39'),
(23, 'Certificate of Medium of Instruction (with Percentage)', 'Like the standard certificate, with the share of courses taught in each language.', 130.00, 1, '2026-11-04 20:04:39'),
(24, 'Certificate of NSTP Serial Number', 'States the serial number of your NSTP completion.', 80.00, 1, '2026-11-04 20:04:39'),
(25, 'Certificate of Student Status', 'States whether you are currently enrolled and in what program.', 100.00, 1, '2026-11-04 20:04:39'),
(26, 'Certificate of Translation to English for Filipino Students', 'English translation of a record issued in Filipino.', 150.00, 1, '2026-11-04 20:04:39'),
(27, 'Certificate of Units Earned (Undergraduate)', 'States how many units you have completed so far.', 100.00, 1, '2026-11-04 20:04:39'),
(28, 'Course Descriptions (Undergraduate)', 'Official descriptions of the courses in your program.', 250.00, 1, '2026-11-04 20:04:39'),
(29, 'Related Learning Experiences (RLE - Undergraduate)', 'Records the hours and areas of your related learning experience.', 150.00, 1, '2026-11-04 20:04:39'),
(30, 'Authentication - For Abroad - Undergraduate', 'Authentication or apostille of your records for use abroad.', 500.00, 1, '2026-11-04 20:04:39'),
(31, 'Authentication - Certificate of Enrollment', 'Authenticated certificate of enrollment.', 200.00, 1, '2026-11-04 20:04:39'),
(32, 'Authentication - Certified Copy of Transcript of Records', 'Certified copy of your transcript, ready for authentication.', 350.00, 1, '2026-11-04 20:04:39'),
(33, 'Authentication - CHED Endorsement', 'School endorsement to submit for CHED authentication.', 250.00, 1, '2026-11-04 20:04:39'),
(34, 'For CHED - Certificate of Enrollment', 'Certificate of enrollment issued for CHED use only.', 100.00, 1, '2026-11-04 20:04:39'),
(35, 'For CHED - CHED Endorsement', 'Endorsement of your records to CHED. No fee when issued to CHED.', 0.00, 1, '2026-11-04 20:04:39'),
(36, 'For CHED - Transcript of Records', 'Transcript of records issued for CHED use only.', 300.00, 1, '2026-11-04 20:04:39'),
(37, 'Academic Calendar (Certified True Copy)', 'Certified copy of the academic calendar for your school year.', 50.00, 1, '2026-11-04 20:04:39'),
(38, 'Certificate of Registration (Certified True Copy)', 'Certified copy of your registration for a given term.', 80.00, 1, '2026-11-04 20:04:39'),
(39, 'Computerized Grades (Certified True Copy)', 'Certified copy of your grades as printed from the system.', 100.00, 1, '2026-11-04 20:04:39'),
(40, 'Others', 'Not in the list? Describe what you need and the Registrar will confirm the fee.', 0.00, 1, '2026-11-04 20:04:39');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(10) UNSIGNED NOT NULL,
  `request_id` int(10) UNSIGNED NOT NULL,
  `sender_user_id` int(10) UNSIGNED NOT NULL,
  `message_body` text NOT NULL,
  `sent_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `request_id`, `sender_user_id`, `message_body`, `sent_at`) VALUES
(1, 1, 6, 'Good day po, should I submit the hard copy of my clearance form now?', '2026-11-02 20:20:04'),
(2, 1, 2, 'Hello Brandon, yes please drop it off at window 3 during working hours.', '2026-11-03 20:20:04'),
(3, 2, 3, 'Is my good moral certificate ready for release?', '2026-11-01 20:20:04'),
(4, 2, 2, 'Yes Eldric, it is already marked ready for pickup at the Registrar counter.', '2026-11-02 20:20:04'),
(5, 4, 5, 'Hello po, I already settled my payment at the cashier.', '2026-10-29 20:21:36'),
(6, 4, 2, 'Got it John Steven, processing your true copy of grades now.', '2026-10-30 20:21:36');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(10) UNSIGNED NOT NULL,
  `recipient_user_id` int(10) UNSIGNED NOT NULL,
  `request_id` int(10) UNSIGNED DEFAULT NULL,
  `notification_type` varchar(80) NOT NULL,
  `notification_message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `recipient_user_id`, `request_id`, `notification_type`, `notification_message`, `is_read`, `created_at`) VALUES
(1, 6, 1, 'STATUS_UPDATE', 'Your request for Transcript of Records is now Processing.', 1, '2026-11-03 20:20:04'),
(2, 3, 2, 'STATUS_UPDATE', 'Your Certificate of Good Moral Character is Ready for Pickup.', 0, '2026-11-02 20:20:04'),
(3, 2, 3, 'NEW_REQUEST', 'New document request received from Gabrielle Angelo Paet.', 0, '2026-11-04 20:20:04'),
(4, 5, 4, 'STATUS_UPDATE', 'Your request for True Copy of Grades has been Completed.', 1, '2026-10-31 20:21:36');

-- --------------------------------------------------------

--
-- Table structure for table `request_status_history`
--

CREATE TABLE `request_status_history` (
  `history_id` int(10) UNSIGNED NOT NULL,
  `request_id` int(10) UNSIGNED NOT NULL,
  `changed_by_user_id` int(10) UNSIGNED DEFAULT NULL,
  `previous_status` enum('Pending','Processing','Ready','Completed','Rejected') DEFAULT NULL,
  `new_status` enum('Pending','Processing','Ready','Completed','Rejected') NOT NULL,
  `change_note` text DEFAULT NULL,
  `changed_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_status_history`
--

INSERT INTO `request_status_history` (`history_id`, `request_id`, `changed_by_user_id`, `previous_status`, `new_status`, `change_note`, `changed_at`) VALUES
(1, 1, 3, NULL, 'Pending', 'Request initially submitted by student.', '2026-11-01 20:20:04'),
(2, 1, 2, 'Pending', 'Processing', 'Verified requirements and forwarded to records office.', '2026-11-03 20:20:04'),
(3, 2, 3, NULL, 'Pending', 'Request initially submitted by student.', '2026-10-30 20:20:04'),
(4, 2, 2, 'Pending', 'Processing', 'Checking clearance records.', '2026-11-01 20:20:04'),
(5, 2, 2, 'Processing', 'Ready', 'Document printed and ready for on-site pickup.', '2026-11-02 20:20:04'),
(6, 3, 4, NULL, 'Pending', 'Request submitted.', '2026-11-04 20:20:04'),
(7, 4, 5, NULL, 'Pending', 'Request submitted by student.', '2026-10-28 20:21:36'),
(8, 4, 2, 'Pending', 'Processing', 'Checked records and payment status.', '2026-10-29 20:21:36'),
(9, 4, 2, 'Processing', 'Completed', 'Document picked up and completed.', '2026-10-31 20:21:36');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `description`) VALUES
(1, 'student', 'Enrolled student account'),
(2, 'staff', 'Registrar office personnel'),
(3, 'admin', 'System administrator');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `student_id` varchar(32) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `account_status` enum('Pending','Active','Suspended','Deactivated') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role_id`, `student_id`, `first_name`, `last_name`, `email`, `password_hash`, `account_status`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, 'System', 'Admin', 'admin@univ.edu.ph', 'admin', 'Active', '2026-11-04 20:05:52', '2026-11-04 20:42:11'),
(2, 2, 'EMP202612345', 'Juan', 'Dela Cruz', 'juan.delacruz@univ.edu.ph', '123', 'Active', '2026-11-04 20:05:52', '2026-11-04 20:42:11'),
(3, 1, '202510875', 'Eldric Vincent', 'Ponce', 'eldric.vincent.ponce@univ.edu.ph', '202510875', 'Active', '2026-11-04 20:05:52', '2026-11-04 20:42:11'),
(4, 1, '202413709', 'Gabrielle Angelo', 'Paet', 'gabrielle.angelo.paet@univ.edu.ph', '202413709', 'Active', '2026-11-04 20:05:52', '2026-11-04 20:42:11'),
(5, 1, '202413898', 'John Steven', 'Maluenda', 'john.steven.maluenda@univ.edu.ph', '202413898', 'Active', '2026-11-04 20:05:52', '2026-11-04 20:42:11'),
(6, 1, '202215245', 'Brandon', 'Romingquit', 'brandon.romingquit@univ.edu.ph', '202215245', 'Active', '2026-11-04 20:05:52', '2026-11-04 20:42:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `document_requests`
--
ALTER TABLE `document_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `fk_request_student` (`student_user_id`),
  ADD KEY `fk_request_doc_type` (`document_type_id`),
  ADD KEY `fk_request_staff` (`assigned_staff_user_id`);

--
-- Indexes for table `document_types`
--
ALTER TABLE `document_types`
  ADD PRIMARY KEY (`document_type_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `fk_message_request` (`request_id`),
  ADD KEY `fk_message_sender` (`sender_user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `fk_notification_recipient` (`recipient_user_id`),
  ADD KEY `fk_notification_request` (`request_id`);

--
-- Indexes for table `request_status_history`
--
ALTER TABLE `request_status_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `fk_history_request` (`request_id`),
  ADD KEY `fk_history_user` (`changed_by_user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD KEY `fk_user_role` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `document_requests`
--
ALTER TABLE `document_requests`
  MODIFY `request_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `document_types`
--
ALTER TABLE `document_types`
  MODIFY `document_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `request_status_history`
--
ALTER TABLE `request_status_history`
  MODIFY `history_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `document_requests`
--
ALTER TABLE `document_requests`
  ADD CONSTRAINT `fk_request_doc_type` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`document_type_id`),
  ADD CONSTRAINT `fk_request_staff` FOREIGN KEY (`assigned_staff_user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_request_student` FOREIGN KEY (`student_user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_message_request` FOREIGN KEY (`request_id`) REFERENCES `document_requests` (`request_id`),
  ADD CONSTRAINT `fk_message_sender` FOREIGN KEY (`sender_user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notification_recipient` FOREIGN KEY (`recipient_user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_notification_request` FOREIGN KEY (`request_id`) REFERENCES `document_requests` (`request_id`);

--
-- Constraints for table `request_status_history`
--
ALTER TABLE `request_status_history`
  ADD CONSTRAINT `fk_history_request` FOREIGN KEY (`request_id`) REFERENCES `document_requests` (`request_id`),
  ADD CONSTRAINT `fk_history_user` FOREIGN KEY (`changed_by_user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
