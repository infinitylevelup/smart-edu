-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2025 at 10:10 AM
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
-- Database: `smart_edu_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_assessments`
--

CREATE TABLE `academic_assessments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `grade_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `field_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subfield_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `topic_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assessment_type` enum('diagnostic','weekly','exam_result','practice') NOT NULL DEFAULT 'exam_result',
  `score_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `mastery_level` decimal(5,2) NOT NULL DEFAULT 0.00,
  `weaknesses` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`weaknesses`)),
  `strengths` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`strengths`)),
  `taken_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ai_agents`
--

CREATE TABLE `ai_agents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(100) NOT NULL,
  `name_fa` varchar(150) NOT NULL,
  `role_type` enum('tutor','counselor','both') NOT NULL DEFAULT 'both',
  `model_provider` varchar(50) NOT NULL DEFAULT 'openai',
  `model_name` varchar(100) NOT NULL,
  `system_prompt` longtext DEFAULT NULL,
  `config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`config`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ai_artifacts`
--

CREATE TABLE `ai_artifacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_id` bigint(20) UNSIGNED NOT NULL,
  `artifact_type` enum('content','question','plan','report','rubric','other') NOT NULL DEFAULT 'other',
  `title` varchar(250) DEFAULT NULL,
  `body` longtext DEFAULT NULL,
  `linked_table` varchar(100) DEFAULT NULL,
  `linked_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('draft','approved','published','rejected') NOT NULL DEFAULT 'draft',
  `reviewer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ai_feedback`
--

CREATE TABLE `ai_feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_id` bigint(20) UNSIGNED NOT NULL,
  `message_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `feedback_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ai_messages`
--

CREATE TABLE `ai_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_id` bigint(20) UNSIGNED NOT NULL,
  `sender_type` enum('user','ai','system') NOT NULL DEFAULT 'user',
  `content` longtext NOT NULL,
  `tokens_in` int(10) UNSIGNED DEFAULT NULL,
  `tokens_out` int(10) UNSIGNED DEFAULT NULL,
  `safety_flags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`safety_flags`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ai_sessions`
--

CREATE TABLE `ai_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ai_agent_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `context_type` enum('teaching','counseling','qna','exam_help','mixed') NOT NULL DEFAULT 'mixed',
  `section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `grade_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `field_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subfield_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `topic_id` bigint(20) UNSIGNED DEFAULT NULL,
  `classroom_id` bigint(20) UNSIGNED DEFAULT NULL,
  `exam_id` bigint(20) UNSIGNED DEFAULT NULL,
  `started_at` datetime NOT NULL,
  `ended_at` datetime DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attempt_answers`
--

CREATE TABLE `attempt_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attempt_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `answer` longtext DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `score` decimal(6,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(100) NOT NULL,
  `name_fa` varchar(150) NOT NULL,
  `sort_order` int(10) UNSIGNED DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classrooms`
--

CREATE TABLE `classrooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `grade_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `field_id` bigint(20) UNSIGNED NOT NULL,
  `subfield_id` bigint(20) UNSIGNED NOT NULL,
  `subject_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `classroom_type` enum('single','comprehensive') NOT NULL DEFAULT 'single',
  `join_code` varchar(20) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classroom_subject`
--

CREATE TABLE `classroom_subject` (
  `classroom_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classroom_user`
--

CREATE TABLE `classroom_user` (
  `classroom_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('video','pdf','article','link','quiz','other') NOT NULL DEFAULT 'other',
  `title` varchar(250) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `grade_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `field_id` bigint(20) UNSIGNED NOT NULL,
  `subfield_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `topic_id` bigint(20) UNSIGNED DEFAULT NULL,
  `access_level` enum('public','classroom','private') NOT NULL DEFAULT 'public',
  `classroom_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ai_generated_by_session_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `counseling_tasks`
--

CREATE TABLE `counseling_tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text DEFAULT NULL,
  `task_type` varchar(100) NOT NULL,
  `recommended_minutes` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `counselor_profiles`
--

CREATE TABLE `counselor_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `focus_area` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`focus_area`)),
  `main_section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `main_branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `classroom_id` bigint(20) UNSIGNED DEFAULT NULL,
  `exam_type` enum('public','class_single','class_comprehensive') NOT NULL DEFAULT 'public',
  `title` varchar(250) NOT NULL,
  `description` text DEFAULT NULL,
  `duration_minutes` int(10) UNSIGNED NOT NULL DEFAULT 90,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `grade_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `field_id` bigint(20) UNSIGNED NOT NULL,
  `subfield_id` bigint(20) UNSIGNED NOT NULL,
  `subject_type_id` bigint(20) UNSIGNED NOT NULL,
  `total_questions` int(10) UNSIGNED DEFAULT NULL,
  `coefficients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`coefficients`)),
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `shuffle_questions` tinyint(1) NOT NULL DEFAULT 1,
  `shuffle_options` tinyint(1) NOT NULL DEFAULT 1,
  `ai_assisted` tinyint(1) NOT NULL DEFAULT 0,
  `ai_session_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_attempts`
--

CREATE TABLE `exam_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `exam_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `started_at` datetime NOT NULL,
  `finished_at` datetime DEFAULT NULL,
  `status` enum('in_progress','submitted','graded') NOT NULL DEFAULT 'in_progress',
  `total_score` decimal(6,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_questions`
--

CREATE TABLE `exam_questions` (
  `exam_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `sort_order` int(10) UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_subject`
--

CREATE TABLE `exam_subject` (
  `exam_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `question_count` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

CREATE TABLE `fields` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(100) NOT NULL,
  `name_fa` varchar(150) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `sort_order` int(10) UNSIGNED DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `value` tinyint(3) UNSIGNED NOT NULL,
  `name_fa` varchar(50) NOT NULL,
  `sort_order` int(10) UNSIGNED DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `learning_paths`
--

CREATE TABLE `learning_paths` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `path_type` enum('academic','counseling','mixed') NOT NULL DEFAULT 'mixed',
  `title` varchar(250) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','completed','archived') NOT NULL DEFAULT 'active',
  `generated_by` enum('ai','counselor','teacher','system') NOT NULL DEFAULT 'system',
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `learning_path_steps`
--

CREATE TABLE `learning_path_steps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `learning_path_id` bigint(20) UNSIGNED NOT NULL,
  `step_type` enum('topic','subject','content','exercise','exam','counseling_task') NOT NULL,
  `order_index` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `grade_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `field_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subfield_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `topic_id` bigint(20) UNSIGNED DEFAULT NULL,
  `content_id` bigint(20) UNSIGNED DEFAULT NULL,
  `exam_id` bigint(20) UNSIGNED DEFAULT NULL,
  `counseling_task_id` bigint(20) UNSIGNED DEFAULT NULL,
  `estimated_minutes` int(10) UNSIGNED DEFAULT NULL,
  `required_mastery` decimal(5,2) NOT NULL DEFAULT 0.00,
  `status` enum('locked','current','done','skipped') NOT NULL DEFAULT 'locked',
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, '2025_12_05_202831_create_otps_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `otps`
--

CREATE TABLE `otps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(15) NOT NULL,
  `code` varchar(6) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `type` enum('login','register','password_reset') NOT NULL DEFAULT 'login',
  `attempts` tinyint(4) NOT NULL DEFAULT 0,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otps`
--

INSERT INTO `otps` (`id`, `phone`, `code`, `token`, `type`, `attempts`, `verified`, `expires_at`, `verified_at`, `created_at`, `updated_at`) VALUES
(25, '9391414434', '169686', NULL, 'login', 0, 0, '2025-12-06 02:53:23', NULL, '2025-12-06 02:51:23', '2025-12-06 02:51:23'),
(26, '9391414434', '759396', '8UBUTrPua1QPzCmYLphQeXl3kYiqb0Cp', 'login', 0, 0, '2025-12-06 02:56:25', NULL, '2025-12-06 02:51:25', '2025-12-06 02:51:25');

-- --------------------------------------------------------

--
-- Table structure for table `psycho_assessments`
--

CREATE TABLE `psycho_assessments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `assessment_type` enum('stress','motivation','focus','anxiety','mood','sleep') NOT NULL,
  `value` decimal(5,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `measured_at` datetime NOT NULL,
  `source` enum('self_report','counselor','ai_inferred') NOT NULL DEFAULT 'self_report'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `grade_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `field_id` bigint(20) UNSIGNED NOT NULL,
  `subfield_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `topic_id` bigint(20) UNSIGNED DEFAULT NULL,
  `difficulty` tinyint(3) UNSIGNED NOT NULL DEFAULT 3,
  `question_type` enum('mcq','descriptive','true_false','matching','short_answer','other') NOT NULL DEFAULT 'mcq',
  `content` longtext NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `correct_answer` longtext NOT NULL,
  `explanation` longtext DEFAULT NULL,
  `ai_generated_by_session_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ai_confidence` decimal(5,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(50) NOT NULL,
  `name_fa` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `slug`, `name_fa`) VALUES
(1, 'student', 'دانش‌آموز'),
(2, 'teacher', 'معلم');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`user_id`, `role_id`) VALUES
(2, 1),
(3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(100) NOT NULL,
  `name_fa` varchar(150) NOT NULL,
  `sort_order` int(10) UNSIGNED DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_counseling_task_logs`
--

CREATE TABLE `student_counseling_task_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `counseling_task_id` bigint(20) UNSIGNED NOT NULL,
  `done_at` datetime NOT NULL,
  `self_rating` tinyint(3) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_daily_logs`
--

CREATE TABLE `student_daily_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `log_date` date NOT NULL,
  `study_minutes` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `mood` tinyint(3) UNSIGNED DEFAULT NULL,
  `stress_level` tinyint(3) UNSIGNED DEFAULT NULL,
  `sleep_hours` decimal(4,1) DEFAULT NULL,
  `free_text` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_goals`
--

CREATE TABLE `student_goals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `goal_type` enum('academic','psychological','career') NOT NULL DEFAULT 'academic',
  `title` varchar(250) NOT NULL,
  `description` text DEFAULT NULL,
  `target_date` date DEFAULT NULL,
  `status` enum('active','done','paused') NOT NULL DEFAULT 'active',
  `priority` tinyint(3) UNSIGNED NOT NULL DEFAULT 3,
  `related_subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `related_topic_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_learning_profiles`
--

CREATE TABLE `student_learning_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `preferred_style` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preferred_style`)),
  `pace_level` tinyint(3) UNSIGNED NOT NULL DEFAULT 3,
  `study_time_per_day` int(10) UNSIGNED DEFAULT NULL,
  `goals_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`goals_json`)),
  `counselor_notes` text DEFAULT NULL,
  `ai_summary` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_profiles`
--

CREATE TABLE `student_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `grade_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `field_id` bigint(20) UNSIGNED NOT NULL,
  `subfield_id` bigint(20) UNSIGNED NOT NULL,
  `national_code` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subfields`
--

CREATE TABLE `subfields` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `field_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(100) NOT NULL,
  `name_fa` varchar(150) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `sort_order` int(10) UNSIGNED DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title_fa` varchar(200) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `hours` tinyint(3) UNSIGNED DEFAULT NULL,
  `grade_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `field_id` bigint(20) UNSIGNED NOT NULL,
  `subfield_id` bigint(20) UNSIGNED NOT NULL,
  `subject_type_id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_types`
--

CREATE TABLE `subject_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(100) NOT NULL,
  `name_fa` varchar(150) NOT NULL,
  `coefficient` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `weight_percent` decimal(6,2) NOT NULL DEFAULT 0.00,
  `default_question_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `color` varchar(30) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `sort_order` int(10) UNSIGNED DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_profiles`
--

CREATE TABLE `teacher_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `bio` text DEFAULT NULL,
  `main_section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `main_branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `main_field_id` bigint(20) UNSIGNED DEFAULT NULL,
  `main_subfield_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_subject`
--

CREATE TABLE `teacher_subject` (
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `title_fa` varchar(200) NOT NULL,
  `sort_order` int(10) UNSIGNED DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(190) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `status`, `created_at`, `updated_at`) VALUES
(2, 'کاربر 9391414434', NULL, '9391414434', '$2y$12$URBr64vqLt9O.MLwrQ8Lh.s7ZrB68ux3w.WB3mSIO6GBk./eeRVhu', 'active', '2025-12-05 17:53:59', '2025-12-05 17:53:59'),
(3, 'کاربر 9111434434', NULL, '9111434434', '$2y$12$QNg4aONakGgZz5z94.hnAuI4dvua6rpg8R1wuPD3Qz8lt7JgahJYO', 'active', '2025-12-05 18:16:57', '2025-12-05 18:16:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_assessments`
--
ALTER TABLE `academic_assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_acad_assess_student` (`student_id`),
  ADD KEY `idx_acad_assess_subject` (`subject_id`),
  ADD KEY `fk_acad_assess_section` (`section_id`),
  ADD KEY `fk_acad_assess_grade` (`grade_id`),
  ADD KEY `fk_acad_assess_branch` (`branch_id`),
  ADD KEY `fk_acad_assess_field` (`field_id`),
  ADD KEY `fk_acad_assess_subfield` (`subfield_id`),
  ADD KEY `fk_acad_assess_topic` (`topic_id`);

--
-- Indexes for table `ai_agents`
--
ALTER TABLE `ai_agents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `ai_artifacts`
--
ALTER TABLE `ai_artifacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ai_artifacts_session` (`session_id`),
  ADD KEY `idx_ai_artifacts_status` (`status`),
  ADD KEY `fk_ai_artifacts_reviewer` (`reviewer_id`);

--
-- Indexes for table `ai_feedback`
--
ALTER TABLE `ai_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ai_feedback_session` (`session_id`),
  ADD KEY `idx_ai_feedback_user` (`user_id`),
  ADD KEY `fk_ai_feedback_message` (`message_id`);

--
-- Indexes for table `ai_messages`
--
ALTER TABLE `ai_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ai_messages_session` (`session_id`);

--
-- Indexes for table `ai_sessions`
--
ALTER TABLE `ai_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ai_sessions_user` (`user_id`),
  ADD KEY `idx_ai_sessions_agent` (`ai_agent_id`),
  ADD KEY `fk_ai_sessions_section` (`section_id`),
  ADD KEY `fk_ai_sessions_grade` (`grade_id`),
  ADD KEY `fk_ai_sessions_branch` (`branch_id`),
  ADD KEY `fk_ai_sessions_field` (`field_id`),
  ADD KEY `fk_ai_sessions_subfield` (`subfield_id`),
  ADD KEY `fk_ai_sessions_subject` (`subject_id`),
  ADD KEY `fk_ai_sessions_topic` (`topic_id`),
  ADD KEY `fk_ai_sessions_classroom` (`classroom_id`),
  ADD KEY `fk_ai_sessions_exam` (`exam_id`);

--
-- Indexes for table `attempt_answers`
--
ALTER TABLE `attempt_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_attempt_answers_attempt` (`attempt_id`),
  ADD KEY `idx_attempt_answers_question` (`question_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_branches_section` (`section_id`);

--
-- Indexes for table `classrooms`
--
ALTER TABLE `classrooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `join_code` (`join_code`),
  ADD KEY `idx_classrooms_teacher` (`teacher_id`),
  ADD KEY `idx_classrooms_grade` (`grade_id`),
  ADD KEY `idx_classrooms_branch` (`branch_id`),
  ADD KEY `idx_classrooms_field` (`field_id`),
  ADD KEY `idx_classrooms_subfield` (`subfield_id`),
  ADD KEY `fk_classrooms_section` (`section_id`),
  ADD KEY `fk_classrooms_subject_type` (`subject_type_id`);

--
-- Indexes for table `classroom_subject`
--
ALTER TABLE `classroom_subject`
  ADD PRIMARY KEY (`classroom_id`,`subject_id`),
  ADD KEY `fk_classroom_subject_subject` (`subject_id`);

--
-- Indexes for table `classroom_user`
--
ALTER TABLE `classroom_user`
  ADD PRIMARY KEY (`classroom_id`,`user_id`),
  ADD KEY `fk_classroom_user_user` (`user_id`);

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contents_creator` (`creator_id`),
  ADD KEY `idx_contents_subject` (`subject_id`),
  ADD KEY `idx_contents_topic` (`topic_id`),
  ADD KEY `fk_contents_section` (`section_id`),
  ADD KEY `fk_contents_grade` (`grade_id`),
  ADD KEY `fk_contents_branch` (`branch_id`),
  ADD KEY `fk_contents_field` (`field_id`),
  ADD KEY `fk_contents_subfield` (`subfield_id`),
  ADD KEY `fk_contents_classroom` (`classroom_id`),
  ADD KEY `fk_contents_ai_session` (`ai_generated_by_session_id`);

--
-- Indexes for table `counseling_tasks`
--
ALTER TABLE `counseling_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `counselor_profiles`
--
ALTER TABLE `counselor_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `fk_counselor_profiles_section` (`main_section_id`),
  ADD KEY `fk_counselor_profiles_branch` (`main_branch_id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_exams_teacher` (`teacher_id`),
  ADD KEY `idx_exams_classroom` (`classroom_id`),
  ADD KEY `idx_exams_subject_type` (`subject_type_id`),
  ADD KEY `fk_exams_section` (`section_id`),
  ADD KEY `fk_exams_grade` (`grade_id`),
  ADD KEY `fk_exams_branch` (`branch_id`),
  ADD KEY `fk_exams_field` (`field_id`),
  ADD KEY `fk_exams_subfield` (`subfield_id`),
  ADD KEY `fk_exams_ai_session` (`ai_session_id`);

--
-- Indexes for table `exam_attempts`
--
ALTER TABLE `exam_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_attempts_exam` (`exam_id`),
  ADD KEY `idx_attempts_student` (`student_id`);

--
-- Indexes for table `exam_questions`
--
ALTER TABLE `exam_questions`
  ADD PRIMARY KEY (`exam_id`,`question_id`),
  ADD KEY `fk_exam_questions_question` (`question_id`);

--
-- Indexes for table `exam_subject`
--
ALTER TABLE `exam_subject`
  ADD PRIMARY KEY (`exam_id`,`subject_id`),
  ADD KEY `fk_exam_subject_subject` (`subject_id`);

--
-- Indexes for table `fields`
--
ALTER TABLE `fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_fields_branch_slug` (`branch_id`,`slug`),
  ADD KEY `idx_fields_branch` (`branch_id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_grades_section` (`section_id`);

--
-- Indexes for table `learning_paths`
--
ALTER TABLE `learning_paths`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_paths_student` (`student_id`);

--
-- Indexes for table `learning_path_steps`
--
ALTER TABLE `learning_path_steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_steps_path` (`learning_path_id`),
  ADD KEY `idx_steps_subject` (`subject_id`),
  ADD KEY `fk_steps_section` (`section_id`),
  ADD KEY `fk_steps_grade` (`grade_id`),
  ADD KEY `fk_steps_branch` (`branch_id`),
  ADD KEY `fk_steps_field` (`field_id`),
  ADD KEY `fk_steps_subfield` (`subfield_id`),
  ADD KEY `fk_steps_topic` (`topic_id`),
  ADD KEY `fk_steps_content` (`content_id`),
  ADD KEY `fk_steps_exam` (`exam_id`),
  ADD KEY `fk_steps_counsel_task` (`counseling_task_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otps`
--
ALTER TABLE `otps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `otps_token_unique` (`token`),
  ADD KEY `otps_phone_verified_index` (`phone`,`verified`),
  ADD KEY `otps_phone_expires_at_index` (`phone`,`expires_at`),
  ADD KEY `otps_phone_index` (`phone`);

--
-- Indexes for table `psycho_assessments`
--
ALTER TABLE `psycho_assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_psy_assess_student` (`student_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_questions_creator` (`creator_id`),
  ADD KEY `idx_questions_subject` (`subject_id`),
  ADD KEY `idx_questions_topic` (`topic_id`),
  ADD KEY `fk_questions_section` (`section_id`),
  ADD KEY `fk_questions_grade` (`grade_id`),
  ADD KEY `fk_questions_branch` (`branch_id`),
  ADD KEY `fk_questions_field` (`field_id`),
  ADD KEY `fk_questions_subfield` (`subfield_id`),
  ADD KEY `fk_questions_ai_session` (`ai_generated_by_session_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `idx_role_user_role` (`role_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `student_counseling_task_logs`
--
ALTER TABLE `student_counseling_task_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sctl_student` (`student_id`),
  ADD KEY `fk_sctl_task` (`counseling_task_id`);

--
-- Indexes for table `student_daily_logs`
--
ALTER TABLE `student_daily_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_daily` (`student_id`,`log_date`),
  ADD KEY `idx_daily_student` (`student_id`);

--
-- Indexes for table `student_goals`
--
ALTER TABLE `student_goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_goals_student` (`student_id`),
  ADD KEY `fk_goals_subject` (`related_subject_id`),
  ADD KEY `fk_goals_topic` (`related_topic_id`);

--
-- Indexes for table `student_learning_profiles`
--
ALTER TABLE `student_learning_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- Indexes for table `student_profiles`
--
ALTER TABLE `student_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `fk_student_profiles_section` (`section_id`),
  ADD KEY `fk_student_profiles_grade` (`grade_id`),
  ADD KEY `fk_student_profiles_branch` (`branch_id`),
  ADD KEY `fk_student_profiles_field` (`field_id`),
  ADD KEY `fk_student_profiles_subfield` (`subfield_id`);

--
-- Indexes for table `subfields`
--
ALTER TABLE `subfields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_subfields_field_slug` (`field_id`,`slug`),
  ADD KEY `idx_subfields_field` (`field_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_subjects_grade` (`grade_id`),
  ADD KEY `idx_subjects_branch` (`branch_id`),
  ADD KEY `idx_subjects_field` (`field_id`),
  ADD KEY `idx_subjects_subfield` (`subfield_id`),
  ADD KEY `idx_subjects_type` (`subject_type_id`);

--
-- Indexes for table `subject_types`
--
ALTER TABLE `subject_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `teacher_profiles`
--
ALTER TABLE `teacher_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `fk_teacher_profiles_section` (`main_section_id`),
  ADD KEY `fk_teacher_profiles_branch` (`main_branch_id`),
  ADD KEY `fk_teacher_profiles_field` (`main_field_id`),
  ADD KEY `fk_teacher_profiles_subfield` (`main_subfield_id`);

--
-- Indexes for table `teacher_subject`
--
ALTER TABLE `teacher_subject`
  ADD PRIMARY KEY (`teacher_id`,`subject_id`),
  ADD KEY `fk_teacher_subject_subject` (`subject_id`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_topics_subject` (`subject_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_assessments`
--
ALTER TABLE `academic_assessments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ai_agents`
--
ALTER TABLE `ai_agents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ai_artifacts`
--
ALTER TABLE `ai_artifacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ai_feedback`
--
ALTER TABLE `ai_feedback`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ai_messages`
--
ALTER TABLE `ai_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ai_sessions`
--
ALTER TABLE `ai_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attempt_answers`
--
ALTER TABLE `attempt_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classrooms`
--
ALTER TABLE `classrooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `counseling_tasks`
--
ALTER TABLE `counseling_tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `counselor_profiles`
--
ALTER TABLE `counselor_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_attempts`
--
ALTER TABLE `exam_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fields`
--
ALTER TABLE `fields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_paths`
--
ALTER TABLE `learning_paths`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_path_steps`
--
ALTER TABLE `learning_path_steps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `psycho_assessments`
--
ALTER TABLE `psycho_assessments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_counseling_task_logs`
--
ALTER TABLE `student_counseling_task_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_daily_logs`
--
ALTER TABLE `student_daily_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_goals`
--
ALTER TABLE `student_goals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_learning_profiles`
--
ALTER TABLE `student_learning_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_profiles`
--
ALTER TABLE `student_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subfields`
--
ALTER TABLE `subfields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_types`
--
ALTER TABLE `subject_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher_profiles`
--
ALTER TABLE `teacher_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academic_assessments`
--
ALTER TABLE `academic_assessments`
  ADD CONSTRAINT `fk_acad_assess_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_acad_assess_field` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_acad_assess_grade` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_acad_assess_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_acad_assess_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_acad_assess_subfield` FOREIGN KEY (`subfield_id`) REFERENCES `subfields` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_acad_assess_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_acad_assess_topic` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `ai_artifacts`
--
ALTER TABLE `ai_artifacts`
  ADD CONSTRAINT `fk_ai_artifacts_reviewer` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ai_artifacts_session` FOREIGN KEY (`session_id`) REFERENCES `ai_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ai_feedback`
--
ALTER TABLE `ai_feedback`
  ADD CONSTRAINT `fk_ai_feedback_message` FOREIGN KEY (`message_id`) REFERENCES `ai_messages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ai_feedback_session` FOREIGN KEY (`session_id`) REFERENCES `ai_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ai_feedback_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ai_messages`
--
ALTER TABLE `ai_messages`
  ADD CONSTRAINT `fk_ai_messages_session` FOREIGN KEY (`session_id`) REFERENCES `ai_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ai_sessions`
--
ALTER TABLE `ai_sessions`
  ADD CONSTRAINT `fk_ai_sessions_agent` FOREIGN KEY (`ai_agent_id`) REFERENCES `ai_agents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ai_sessions_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ai_sessions_classroom` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ai_sessions_exam` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ai_sessions_field` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ai_sessions_grade` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ai_sessions_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ai_sessions_subfield` FOREIGN KEY (`subfield_id`) REFERENCES `subfields` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ai_sessions_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ai_sessions_topic` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ai_sessions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `attempt_answers`
--
ALTER TABLE `attempt_answers`
  ADD CONSTRAINT `fk_attempt_answers_attempt` FOREIGN KEY (`attempt_id`) REFERENCES `exam_attempts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_attempt_answers_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `fk_branches_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classrooms`
--
ALTER TABLE `classrooms`
  ADD CONSTRAINT `fk_classrooms_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_classrooms_field` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_classrooms_grade` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_classrooms_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_classrooms_subfield` FOREIGN KEY (`subfield_id`) REFERENCES `subfields` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_classrooms_subject_type` FOREIGN KEY (`subject_type_id`) REFERENCES `subject_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_classrooms_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classroom_subject`
--
ALTER TABLE `classroom_subject`
  ADD CONSTRAINT `fk_classroom_subject_classroom` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_classroom_subject_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classroom_user`
--
ALTER TABLE `classroom_user`
  ADD CONSTRAINT `fk_classroom_user_classroom` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_classroom_user_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contents`
--
ALTER TABLE `contents`
  ADD CONSTRAINT `fk_contents_ai_session` FOREIGN KEY (`ai_generated_by_session_id`) REFERENCES `ai_sessions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_contents_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_contents_classroom` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_contents_creator` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_contents_field` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_contents_grade` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_contents_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_contents_subfield` FOREIGN KEY (`subfield_id`) REFERENCES `subfields` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_contents_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_contents_topic` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `counselor_profiles`
--
ALTER TABLE `counselor_profiles`
  ADD CONSTRAINT `fk_counselor_profiles_branch` FOREIGN KEY (`main_branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_counselor_profiles_section` FOREIGN KEY (`main_section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_counselor_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `fk_exams_ai_session` FOREIGN KEY (`ai_session_id`) REFERENCES `ai_sessions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_exams_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_exams_classroom` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_exams_field` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_exams_grade` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_exams_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_exams_subfield` FOREIGN KEY (`subfield_id`) REFERENCES `subfields` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_exams_subject_type` FOREIGN KEY (`subject_type_id`) REFERENCES `subject_types` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_exams_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exam_attempts`
--
ALTER TABLE `exam_attempts`
  ADD CONSTRAINT `fk_attempts_exam` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_attempts_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exam_questions`
--
ALTER TABLE `exam_questions`
  ADD CONSTRAINT `fk_exam_questions_exam` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_exam_questions_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exam_subject`
--
ALTER TABLE `exam_subject`
  ADD CONSTRAINT `fk_exam_subject_exam` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_exam_subject_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fields`
--
ALTER TABLE `fields`
  ADD CONSTRAINT `fk_fields_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `fk_grades_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `learning_paths`
--
ALTER TABLE `learning_paths`
  ADD CONSTRAINT `fk_paths_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `learning_path_steps`
--
ALTER TABLE `learning_path_steps`
  ADD CONSTRAINT `fk_steps_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_steps_content` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_steps_counsel_task` FOREIGN KEY (`counseling_task_id`) REFERENCES `counseling_tasks` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_steps_exam` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_steps_field` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_steps_grade` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_steps_path` FOREIGN KEY (`learning_path_id`) REFERENCES `learning_paths` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_steps_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_steps_subfield` FOREIGN KEY (`subfield_id`) REFERENCES `subfields` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_steps_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_steps_topic` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `psycho_assessments`
--
ALTER TABLE `psycho_assessments`
  ADD CONSTRAINT `fk_psy_assess_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_questions_ai_session` FOREIGN KEY (`ai_generated_by_session_id`) REFERENCES `ai_sessions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_questions_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_questions_creator` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_questions_field` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_questions_grade` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_questions_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_questions_subfield` FOREIGN KEY (`subfield_id`) REFERENCES `subfields` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_questions_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_questions_topic` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `fk_role_user_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_role_user_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_counseling_task_logs`
--
ALTER TABLE `student_counseling_task_logs`
  ADD CONSTRAINT `fk_sctl_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sctl_task` FOREIGN KEY (`counseling_task_id`) REFERENCES `counseling_tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_daily_logs`
--
ALTER TABLE `student_daily_logs`
  ADD CONSTRAINT `fk_daily_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_goals`
--
ALTER TABLE `student_goals`
  ADD CONSTRAINT `fk_goals_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_goals_subject` FOREIGN KEY (`related_subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_goals_topic` FOREIGN KEY (`related_topic_id`) REFERENCES `topics` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `student_learning_profiles`
--
ALTER TABLE `student_learning_profiles`
  ADD CONSTRAINT `fk_slp_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_profiles`
--
ALTER TABLE `student_profiles`
  ADD CONSTRAINT `fk_student_profiles_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_student_profiles_field` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_student_profiles_grade` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_student_profiles_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_student_profiles_subfield` FOREIGN KEY (`subfield_id`) REFERENCES `subfields` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_student_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subfields`
--
ALTER TABLE `subfields`
  ADD CONSTRAINT `fk_subfields_field` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `fk_subjects_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_subjects_field` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_subjects_grade` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_subjects_subfield` FOREIGN KEY (`subfield_id`) REFERENCES `subfields` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_subjects_type` FOREIGN KEY (`subject_type_id`) REFERENCES `subject_types` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `teacher_profiles`
--
ALTER TABLE `teacher_profiles`
  ADD CONSTRAINT `fk_teacher_profiles_branch` FOREIGN KEY (`main_branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_teacher_profiles_field` FOREIGN KEY (`main_field_id`) REFERENCES `fields` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_teacher_profiles_section` FOREIGN KEY (`main_section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_teacher_profiles_subfield` FOREIGN KEY (`main_subfield_id`) REFERENCES `subfields` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_teacher_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teacher_subject`
--
ALTER TABLE `teacher_subject`
  ADD CONSTRAINT `fk_teacher_subject_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_teacher_subject_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `fk_topics_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
