/* =========================================================
   SMART-EDU FINAL DATABASE (NO SEED)
   Auto-create & select database
   ========================================================= */

CREATE DATABASE IF NOT EXISTS smart_edu_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE smart_edu_db;

SET NAMES utf8mb4;
SET time_zone = "+00:00";
SET foreign_key_checks = 0;

/* =========================================================
   A) TAXONOMY (Education Core)
   ========================================================= */

DROP TABLE IF EXISTS topics;
DROP TABLE IF EXISTS subjects;
DROP TABLE IF EXISTS subject_types;
DROP TABLE IF EXISTS subfields;
DROP TABLE IF EXISTS fields;
DROP TABLE IF EXISTS branches;
DROP TABLE IF EXISTS grades;
DROP TABLE IF EXISTS sections;

CREATE TABLE sections (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    name_fa VARCHAR(150) NOT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE grades (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    section_id BIGINT UNSIGNED NOT NULL,
    value TINYINT UNSIGNED NOT NULL,                 -- 10 / 11 / 12 / ...
    name_fa VARCHAR(50) NOT NULL,                    -- دهم/یازدهم/...
    sort_order INT UNSIGNED DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_grades_section(section_id),
    CONSTRAINT fk_grades_section
        FOREIGN KEY (section_id) REFERENCES sections(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE branches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    section_id BIGINT UNSIGNED NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,               -- technical / vocational / ...
    name_fa VARCHAR(150) NOT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_branches_section(section_id),
    CONSTRAINT fk_branches_section
        FOREIGN KEY (section_id) REFERENCES sections(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE fields (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    branch_id BIGINT UNSIGNED NOT NULL,
    slug VARCHAR(100) NOT NULL,
    name_fa VARCHAR(150) NOT NULL,
    icon VARCHAR(50) NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY uq_fields_branch_slug(branch_id, slug),
    INDEX idx_fields_branch(branch_id),
    CONSTRAINT fk_fields_branch
        FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE subfields (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    field_id BIGINT UNSIGNED NOT NULL,
    slug VARCHAR(100) NOT NULL,
    name_fa VARCHAR(150) NOT NULL,
    icon VARCHAR(50) NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY uq_subfields_field_slug(field_id, slug),
    INDEX idx_subfields_field(field_id),
    CONSTRAINT fk_subfields_field
        FOREIGN KEY (field_id) REFERENCES fields(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE subject_types (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    name_fa VARCHAR(150) NOT NULL,
    coefficient INT UNSIGNED NOT NULL DEFAULT 1,
    weight_percent DECIMAL(6,2) NOT NULL DEFAULT 0.00,
    default_question_count INT UNSIGNED NOT NULL DEFAULT 0,
    color VARCHAR(30) NULL,
    icon VARCHAR(50) NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE subjects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title_fa VARCHAR(200) NOT NULL,
    code VARCHAR(50) NULL,
    hours TINYINT UNSIGNED NULL,

    grade_id BIGINT UNSIGNED NOT NULL,
    branch_id BIGINT UNSIGNED NOT NULL,
    field_id BIGINT UNSIGNED NOT NULL,
    subfield_id BIGINT UNSIGNED NOT NULL,
    subject_type_id BIGINT UNSIGNED NOT NULL,

    description TEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,

    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX idx_subjects_grade(grade_id),
    INDEX idx_subjects_branch(branch_id),
    INDEX idx_subjects_field(field_id),
    INDEX idx_subjects_subfield(subfield_id),
    INDEX idx_subjects_type(subject_type_id),

    CONSTRAINT fk_subjects_grade
        FOREIGN KEY (grade_id) REFERENCES grades(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_subjects_branch
        FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_subjects_field
        FOREIGN KEY (field_id) REFERENCES fields(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_subjects_subfield
        FOREIGN KEY (subfield_id) REFERENCES subfields(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_subjects_type
        FOREIGN KEY (subject_type_id) REFERENCES subject_types(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE topics (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    subject_id BIGINT UNSIGNED NOT NULL,
    title_fa VARCHAR(200) NOT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX idx_topics_subject(subject_id),
    CONSTRAINT fk_topics_subject
        FOREIGN KEY (subject_id) REFERENCES subjects(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* =========================================================
   B) USERS & ROLES
   ========================================================= */

DROP TABLE IF EXISTS role_user;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NULL UNIQUE,
    phone VARCHAR(30) NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(50) NOT NULL UNIQUE,     -- student/teacher/counselor/admin/...
    name_fa VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE role_user (
    user_id BIGINT UNSIGNED NOT NULL,
    role_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY(user_id, role_id),
    INDEX idx_role_user_role(role_id),
    CONSTRAINT fk_role_user_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_role_user_role
        FOREIGN KEY (role_id) REFERENCES roles(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* =========================================================
   C) PROFILES
   ========================================================= */

DROP TABLE IF EXISTS counselor_profiles;
DROP TABLE IF EXISTS teacher_subject;
DROP TABLE IF EXISTS teacher_profiles;
DROP TABLE IF EXISTS student_profiles;

CREATE TABLE student_profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,

    section_id BIGINT UNSIGNED NOT NULL,
    grade_id BIGINT UNSIGNED NOT NULL,
    branch_id BIGINT UNSIGNED NOT NULL,
    field_id BIGINT UNSIGNED NOT NULL,
    subfield_id BIGINT UNSIGNED NOT NULL,

    national_code VARCHAR(20) NULL,

    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_student_profiles_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_student_profiles_section
        FOREIGN KEY (section_id) REFERENCES sections(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_student_profiles_grade
        FOREIGN KEY (grade_id) REFERENCES grades(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_student_profiles_branch
        FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_student_profiles_field
        FOREIGN KEY (field_id) REFERENCES fields(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_student_profiles_subfield
        FOREIGN KEY (subfield_id) REFERENCES subfields(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE teacher_profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,

    bio TEXT NULL,
    main_section_id BIGINT UNSIGNED NULL,
    main_branch_id BIGINT UNSIGNED NULL,
    main_field_id BIGINT UNSIGNED NULL,
    main_subfield_id BIGINT UNSIGNED NULL,

    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_teacher_profiles_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_teacher_profiles_section
        FOREIGN KEY (main_section_id) REFERENCES sections(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_teacher_profiles_branch
        FOREIGN KEY (main_branch_id) REFERENCES branches(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_teacher_profiles_field
        FOREIGN KEY (main_field_id) REFERENCES fields(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_teacher_profiles_subfield
        FOREIGN KEY (main_subfield_id) REFERENCES subfields(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE teacher_subject (
    teacher_id BIGINT UNSIGNED NOT NULL,
    subject_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY(teacher_id, subject_id),
    CONSTRAINT fk_teacher_subject_teacher
        FOREIGN KEY (teacher_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_teacher_subject_subject
        FOREIGN KEY (subject_id) REFERENCES subjects(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE counselor_profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,

    focus_area JSON NULL,
    main_section_id BIGINT UNSIGNED NULL,
    main_branch_id BIGINT UNSIGNED NULL,

    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_counselor_profiles_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_counselor_profiles_section
        FOREIGN KEY (main_section_id) REFERENCES sections(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_counselor_profiles_branch
        FOREIGN KEY (main_branch_id) REFERENCES branches(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* =========================================================
   D) CLASSROOMS
   ========================================================= */

DROP TABLE IF EXISTS classroom_subject;
DROP TABLE IF EXISTS classrooms;

CREATE TABLE classrooms (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    teacher_id BIGINT UNSIGNED NOT NULL,

    title VARCHAR(200) NOT NULL,
    description TEXT NULL,

    section_id BIGINT UNSIGNED NOT NULL,
    grade_id BIGINT UNSIGNED NOT NULL,
    branch_id BIGINT UNSIGNED NOT NULL,
    field_id BIGINT UNSIGNED NOT NULL,
    subfield_id BIGINT UNSIGNED NOT NULL,
    subject_type_id BIGINT UNSIGNED NULL,

    classroom_type ENUM('single','comprehensive') NOT NULL DEFAULT 'single',
    join_code VARCHAR(20) NOT NULL UNIQUE,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    metadata JSON NULL,

    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX idx_classrooms_teacher(teacher_id),
    INDEX idx_classrooms_grade(grade_id),
    INDEX idx_classrooms_branch(branch_id),
    INDEX idx_classrooms_field(field_id),
    INDEX idx_classrooms_subfield(subfield_id),

    CONSTRAINT fk_classrooms_teacher
        FOREIGN KEY (teacher_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_classrooms_section
        FOREIGN KEY (section_id) REFERENCES sections(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_classrooms_grade
        FOREIGN KEY (grade_id) REFERENCES grades(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_classrooms_branch
        FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_classrooms_field
        FOREIGN KEY (field_id) REFERENCES fields(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_classrooms_subfield
        FOREIGN KEY (subfield_id) REFERENCES subfields(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_classrooms_subject_type
        FOREIGN KEY (subject_type_id) REFERENCES subject_types(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE classroom_subject (
    classroom_id BIGINT UNSIGNED NOT NULL,
    subject_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY(classroom_id, subject_id),

    CONSTRAINT fk_classroom_subject_classroom
        FOREIGN KEY (classroom_id) REFERENCES classrooms(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_classroom_subject_subject
        FOREIGN KEY (subject_id) REFERENCES subjects(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* =========================================================
   E) CONTENTS (Learning Resources)
   ========================================================= */

DROP TABLE IF EXISTS contents;

CREATE TABLE contents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    creator_id BIGINT UNSIGNED NOT NULL,

    type ENUM('video','pdf','article','link','quiz','other') NOT NULL DEFAULT 'other',
    title VARCHAR(250) NOT NULL,
    description TEXT NULL,
    file_path TEXT NULL,
    url TEXT NULL,

    section_id BIGINT UNSIGNED NOT NULL,
    grade_id BIGINT UNSIGNED NOT NULL,
    branch_id BIGINT UNSIGNED NOT NULL,
    field_id BIGINT UNSIGNED NOT NULL,
    subfield_id BIGINT UNSIGNED NOT NULL,
    subject_id BIGINT UNSIGNED NOT NULL,
    topic_id BIGINT UNSIGNED NULL,

    access_level ENUM('public','classroom','private') NOT NULL DEFAULT 'public',
    classroom_id BIGINT UNSIGNED NULL,

    ai_generated_by_session_id BIGINT UNSIGNED NULL,  -- FK later

    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX idx_contents_creator(creator_id),
    INDEX idx_contents_subject(subject_id),
    INDEX idx_contents_topic(topic_id),

    CONSTRAINT fk_contents_creator
        FOREIGN KEY (creator_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_contents_section
        FOREIGN KEY (section_id) REFERENCES sections(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_contents_grade
        FOREIGN KEY (grade_id) REFERENCES grades(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_contents_branch
        FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_contents_field
        FOREIGN KEY (field_id) REFERENCES fields(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_contents_subfield
        FOREIGN KEY (subfield_id) REFERENCES subfields(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_contents_subject
        FOREIGN KEY (subject_id) REFERENCES subjects(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_contents_topic
        FOREIGN KEY (topic_id) REFERENCES topics(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_contents_classroom
        FOREIGN KEY (classroom_id) REFERENCES classrooms(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* =========================================================
   F) QUESTION BANK
   ========================================================= */

DROP TABLE IF EXISTS questions;

CREATE TABLE questions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    creator_id BIGINT UNSIGNED NOT NULL,

    section_id BIGINT UNSIGNED NOT NULL,
    grade_id BIGINT UNSIGNED NOT NULL,
    branch_id BIGINT UNSIGNED NOT NULL,
    field_id BIGINT UNSIGNED NOT NULL,
    subfield_id BIGINT UNSIGNED NOT NULL,
    subject_id BIGINT UNSIGNED NOT NULL,
    topic_id BIGINT UNSIGNED NULL,

    difficulty TINYINT UNSIGNED NOT NULL DEFAULT 3,
    question_type ENUM('mcq','descriptive','true_false','matching','short_answer','other')
        NOT NULL DEFAULT 'mcq',

    content LONGTEXT NOT NULL,
    options JSON NULL,
    correct_answer LONGTEXT NOT NULL,
    explanation LONGTEXT NULL,

    ai_generated_by_session_id BIGINT UNSIGNED NULL, -- FK later
    ai_confidence DECIMAL(5,2) NULL,

    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX idx_questions_creator(creator_id),
    INDEX idx_questions_subject(subject_id),
    INDEX idx_questions_topic(topic_id),

    CONSTRAINT fk_questions_creator
        FOREIGN KEY (creator_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_questions_section
        FOREIGN KEY (section_id) REFERENCES sections(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_questions_grade
        FOREIGN KEY (grade_id) REFERENCES grades(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_questions_branch
        FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_questions_field
        FOREIGN KEY (field_id) REFERENCES fields(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_questions_subfield
        FOREIGN KEY (subfield_id) REFERENCES subfields(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_questions_subject
        FOREIGN KEY (subject_id) REFERENCES subjects(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_questions_topic
        FOREIGN KEY (topic_id) REFERENCES topics(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* =========================================================
   G) EXAMS
   ========================================================= */

DROP TABLE IF EXISTS attempt_answers;
DROP TABLE IF EXISTS exam_attempts;
DROP TABLE IF EXISTS exam_questions;
DROP TABLE IF EXISTS exam_subject;
DROP TABLE IF EXISTS exams;

CREATE TABLE exams (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    teacher_id BIGINT UNSIGNED NOT NULL,
    classroom_id BIGINT UNSIGNED NULL,

    exam_type ENUM('public','class_single','class_comprehensive') NOT NULL DEFAULT 'public',
    title VARCHAR(250) NOT NULL,
    description TEXT NULL,
    duration_minutes INT UNSIGNED NOT NULL DEFAULT 90,

    section_id BIGINT UNSIGNED NOT NULL,
    grade_id BIGINT UNSIGNED NOT NULL,
    branch_id BIGINT UNSIGNED NOT NULL,
    field_id BIGINT UNSIGNED NOT NULL,
    subfield_id BIGINT UNSIGNED NOT NULL,
    subject_type_id BIGINT UNSIGNED NOT NULL,

    total_questions INT UNSIGNED NULL,
    coefficients JSON NULL,

    start_at DATETIME NULL,
    end_at DATETIME NULL,

    shuffle_questions TINYINT(1) NOT NULL DEFAULT 1,
    shuffle_options TINYINT(1) NOT NULL DEFAULT 1,

    ai_assisted TINYINT(1) NOT NULL DEFAULT 0,
    ai_session_id BIGINT UNSIGNED NULL,  -- FK later

    is_active TINYINT(1) NOT NULL DEFAULT 1,

    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX idx_exams_teacher(teacher_id),
    INDEX idx_exams_classroom(classroom_id),
    INDEX idx_exams_subject_type(subject_type_id),

    CONSTRAINT fk_exams_teacher
        FOREIGN KEY (teacher_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_exams_classroom
        FOREIGN KEY (classroom_id) REFERENCES classrooms(id)
        ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_exams_section
        FOREIGN KEY (section_id) REFERENCES sections(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_exams_grade
        FOREIGN KEY (grade_id) REFERENCES grades(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_exams_branch
        FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_exams_field
        FOREIGN KEY (field_id) REFERENCES fields(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_exams_subfield
        FOREIGN KEY (subfield_id) REFERENCES subfields(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_exams_subject_type
        FOREIGN KEY (subject_type_id) REFERENCES subject_types(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE exam_subject (
    exam_id BIGINT UNSIGNED NOT NULL,
    subject_id BIGINT UNSIGNED NOT NULL,
    question_count INT UNSIGNED NULL,
    PRIMARY KEY(exam_id, subject_id),

    CONSTRAINT fk_exam_subject_exam
        FOREIGN KEY (exam_id) REFERENCES exams(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_exam_subject_subject
        FOREIGN KEY (subject_id) REFERENCES subjects(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE exam_questions (
    exam_id BIGINT UNSIGNED NOT NULL,
    question_id BIGINT UNSIGNED NOT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    PRIMARY KEY(exam_id, question_id),

    CONSTRAINT fk_exam_questions_exam
        FOREIGN KEY (exam_id) REFERENCES exams(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_exam_questions_question
        FOREIGN KEY (question_id) REFERENCES questions(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE exam_attempts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    exam_id BIGINT UNSIGNED NOT NULL,
    student_id BIGINT UNSIGNED NOT NULL,

    started_at DATETIME NOT NULL,
    finished_at DATETIME NULL,
    status ENUM('in_progress','submitted','graded') NOT NULL DEFAULT 'in_progress',
    total_score DECIMAL(6,2) NULL,

    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX idx_attempts_exam(exam_id),
    INDEX idx_attempts_student(student_id),

    CONSTRAINT fk_attempts_exam
        FOREIGN KEY (exam_id) REFERENCES exams(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_attempts_student
        FOREIGN KEY (student_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE attempt_answers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    attempt_id BIGINT UNSIGNED NOT NULL,
    question_id BIGINT UNSIGNED NOT NULL,

    answer LONGTEXT NULL,
    is_correct TINYINT(1) NULL,
    score DECIMAL(6,2) NULL,

    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX idx_attempt_answers_attempt(attempt_id),
    INDEX idx_attempt_answers_question(question_id),

    CONSTRAINT fk_attempt_answers_attempt
        FOREIGN KEY (attempt_id) REFERENCES exam_attempts(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_attempt_answers_question
        FOREIGN KEY (question_id) REFERENCES questions(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* =========================================================
   I) AI LAYER (Tutor/Counselor)
   ========================================================= */

DROP TABLE IF EXISTS ai_feedback;
DROP TABLE IF EXISTS ai_artifacts;
DROP TABLE IF EXISTS ai_messages;
DROP TABLE IF EXISTS ai_sessions;
DROP TABLE IF EXISTS ai_agents;

CREATE TABLE ai_agents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    name_fa VARCHAR(150) NOT NULL,
    role_type ENUM('tutor','counselor','both') NOT NULL DEFAULT 'both',
    model_provider VARCHAR(50) NOT NULL DEFAULT 'openai',
    model_name VARCHAR(100) NOT NULL,
    system_prompt LONGTEXT NULL,
    config JSON NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE ai_sessions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ai_agent_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,

    context_type ENUM('teaching','counseling','qna','exam_help','mixed')
        NOT NULL DEFAULT 'mixed',

    section_id BIGINT UNSIGNED NULL,
    grade_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    field_id BIGINT UNSIGNED NULL,
    subfield_id BIGINT UNSIGNED NULL,
    subject_id BIGINT UNSIGNED NULL,
    topic_id BIGINT UNSIGNED NULL,

    classroom_id BIGINT UNSIGNED NULL,
    exam_id BIGINT UNSIGNED NULL,

    started_at DATETIME NOT NULL,
    ended_at DATETIME NULL,
    metadata JSON NULL,

    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX idx_ai_sessions_user(user_id),
    INDEX idx_ai_sessions_agent(ai_agent_id),

    CONSTRAINT fk_ai_sessions_agent
        FOREIGN KEY (ai_agent_id) REFERENCES ai_agents(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ai_sessions_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_ai_sessions_section
        FOREIGN KEY (section_id) REFERENCES sections(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_ai_sessions_grade
        FOREIGN KEY (grade_id) REFERENCES grades(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_ai_sessions_branch
        FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_ai_sessions_field
        FOREIGN KEY (field_id) REFERENCES fields(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_ai_sessions_subfield
        FOREIGN KEY (subfield_id) REFERENCES subfields(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_ai_sessions_subject
        FOREIGN KEY (subject_id) REFERENCES subjects(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_ai_sessions_topic
        FOREIGN KEY (topic_id) REFERENCES topics(id)
        ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_ai_sessions_classroom
        FOREIGN KEY (classroom_id) REFERENCES classrooms(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_ai_sessions_exam
        FOREIGN KEY (exam_id) REFERENCES exams(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE ai_messages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    session_id BIGINT UNSIGNED NOT NULL,
    sender_type ENUM('user','ai','system') NOT NULL DEFAULT 'user',
    content LONGTEXT NOT NULL,
    tokens_in INT UNSIGNED NULL,
    tokens_out INT UNSIGNED NULL,
    safety_flags JSON NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_ai_messages_session(session_id),

    CONSTRAINT fk_ai_messages_session
        FOREIGN KEY (session_id) REFERENCES ai_sessions(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE ai_artifacts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    session_id BIGINT UNSIGNED NOT NULL,
    artifact_type ENUM('content','question','plan','report','rubric','other')
        NOT NULL DEFAULT 'other',
    title VARCHAR(250) NULL,
    body LONGTEXT NULL,
    linked_table VARCHAR(100) NULL,
    linked_id BIGINT UNSIGNED NULL,
    status ENUM('draft','approved','published','rejected') NOT NULL DEFAULT 'draft',
    reviewer_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_ai_artifacts_session(session_id),
    INDEX idx_ai_artifacts_status(status),

    CONSTRAINT fk_ai_artifacts_session
        FOREIGN KEY (session_id) REFERENCES ai_sessions(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ai_artifacts_reviewer
        FOREIGN KEY (reviewer_id) REFERENCES users(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE ai_feedback (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    session_id BIGINT UNSIGNED NOT NULL,
    message_id BIGINT UNSIGNED NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    feedback_text TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_ai_feedback_session(session_id),
    INDEX idx_ai_feedback_user(user_id),

    CONSTRAINT fk_ai_feedback_session
        FOREIGN KEY (session_id) REFERENCES ai_sessions(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ai_feedback_message
        FOREIGN KEY (message_id) REFERENCES ai_messages(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_ai_feedback_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* ---- Attach AI FKs to core tables now that ai_sessions exists ---- */

ALTER TABLE contents
    ADD CONSTRAINT fk_contents_ai_session
        FOREIGN KEY (ai_generated_by_session_id) REFERENCES ai_sessions(id)
        ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE questions
    ADD CONSTRAINT fk_questions_ai_session
        FOREIGN KEY (ai_generated_by_session_id) REFERENCES ai_sessions(id)
        ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE exams
    ADD CONSTRAINT fk_exams_ai_session
        FOREIGN KEY (ai_session_id) REFERENCES ai_sessions(id)
        ON DELETE SET NULL ON UPDATE CASCADE;


/* =========================================================
   J) PERSONALIZED LEARNING PATH (Academic + Psychological)
   ========================================================= */

DROP TABLE IF EXISTS student_daily_logs;
DROP TABLE IF EXISTS student_counseling_task_logs;
DROP TABLE IF EXISTS counseling_tasks;
DROP TABLE IF EXISTS learning_path_steps;
DROP TABLE IF EXISTS learning_paths;
DROP TABLE IF EXISTS student_goals;
DROP TABLE IF EXISTS psycho_assessments;
DROP TABLE IF EXISTS academic_assessments;
DROP TABLE IF EXISTS student_learning_profiles;

CREATE TABLE student_learning_profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL UNIQUE,

    preferred_style JSON NULL,
    pace_level TINYINT UNSIGNED NOT NULL DEFAULT 3,
    study_time_per_day INT UNSIGNED NULL,
    goals_json JSON NULL,
    counselor_notes TEXT NULL,
    ai_summary TEXT NULL,

    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_slp_student
        FOREIGN KEY (student_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE academic_assessments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,

    section_id BIGINT UNSIGNED NULL,
    grade_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    field_id BIGINT UNSIGNED NULL,
    subfield_id BIGINT UNSIGNED NULL,
    subject_id BIGINT UNSIGNED NULL,
    topic_id BIGINT UNSIGNED NULL,

    assessment_type ENUM('diagnostic','weekly','exam_result','practice')
        NOT NULL DEFAULT 'exam_result',

    score_percent DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    mastery_level DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    weaknesses JSON NULL,
    strengths JSON NULL,

    taken_at DATETIME NOT NULL,

    INDEX idx_acad_assess_student(student_id),
    INDEX idx_acad_assess_subject(subject_id),

    CONSTRAINT fk_acad_assess_student
        FOREIGN KEY (student_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_acad_assess_section FOREIGN KEY (section_id) REFERENCES sections(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_acad_assess_grade FOREIGN KEY (grade_id) REFERENCES grades(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_acad_assess_branch FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_acad_assess_field FOREIGN KEY (field_id) REFERENCES fields(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_acad_assess_subfield FOREIGN KEY (subfield_id) REFERENCES subfields(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_acad_assess_subject FOREIGN KEY (subject_id) REFERENCES subjects(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_acad_assess_topic FOREIGN KEY (topic_id) REFERENCES topics(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE psycho_assessments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,

    assessment_type ENUM('stress','motivation','focus','anxiety','mood','sleep')
        NOT NULL,

    value DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    notes TEXT NULL,
    measured_at DATETIME NOT NULL,
    source ENUM('self_report','counselor','ai_inferred')
        NOT NULL DEFAULT 'self_report',

    INDEX idx_psy_assess_student(student_id),

    CONSTRAINT fk_psy_assess_student
        FOREIGN KEY (student_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE student_goals (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,

    goal_type ENUM('academic','psychological','career')
        NOT NULL DEFAULT 'academic',

    title VARCHAR(250) NOT NULL,
    description TEXT NULL,
    target_date DATE NULL,
    status ENUM('active','done','paused')
        NOT NULL DEFAULT 'active',
    priority TINYINT UNSIGNED NOT NULL DEFAULT 3,

    related_subject_id BIGINT UNSIGNED NULL,
    related_topic_id BIGINT UNSIGNED NULL,

    created_at TIMESTAMP NULL,

    INDEX idx_goals_student(student_id),

    CONSTRAINT fk_goals_student
        FOREIGN KEY (student_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_goals_subject
        FOREIGN KEY (related_subject_id) REFERENCES subjects(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_goals_topic
        FOREIGN KEY (related_topic_id) REFERENCES topics(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE learning_paths (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,

    path_type ENUM('academic','counseling','mixed')
        NOT NULL DEFAULT 'mixed',

    title VARCHAR(250) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NULL,

    status ENUM('active','completed','archived')
        NOT NULL DEFAULT 'active',

    generated_by ENUM('ai','counselor','teacher','system')
        NOT NULL DEFAULT 'system',

    metadata JSON NULL,

    created_at TIMESTAMP NULL,

    INDEX idx_paths_student(student_id),

    CONSTRAINT fk_paths_student
        FOREIGN KEY (student_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE counseling_tasks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(250) NOT NULL,
    description TEXT NULL,
    task_type VARCHAR(100) NOT NULL,      -- breathing/journaling/routine/...
    recommended_minutes INT UNSIGNED NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE learning_path_steps (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    learning_path_id BIGINT UNSIGNED NOT NULL,

    step_type ENUM('topic','subject','content','exercise','exam','counseling_task')
        NOT NULL,

    order_index INT UNSIGNED NOT NULL DEFAULT 0,

    section_id BIGINT UNSIGNED NULL,
    grade_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    field_id BIGINT UNSIGNED NULL,
    subfield_id BIGINT UNSIGNED NULL,
    subject_id BIGINT UNSIGNED NULL,
    topic_id BIGINT UNSIGNED NULL,

    content_id BIGINT UNSIGNED NULL,
    exam_id BIGINT UNSIGNED NULL,
    counseling_task_id BIGINT UNSIGNED NULL,

    estimated_minutes INT UNSIGNED NULL,
    required_mastery DECIMAL(5,2) NOT NULL DEFAULT 0.00,

    status ENUM('locked','current','done','skipped')
        NOT NULL DEFAULT 'locked',

    due_date DATE NULL,
    created_at TIMESTAMP NULL,

    INDEX idx_steps_path(learning_path_id),
    INDEX idx_steps_subject(subject_id),

    CONSTRAINT fk_steps_path
        FOREIGN KEY (learning_path_id) REFERENCES learning_paths(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_steps_section FOREIGN KEY (section_id) REFERENCES sections(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_steps_grade FOREIGN KEY (grade_id) REFERENCES grades(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_steps_branch FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_steps_field FOREIGN KEY (field_id) REFERENCES fields(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_steps_subfield FOREIGN KEY (subfield_id) REFERENCES subfields(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_steps_subject FOREIGN KEY (subject_id) REFERENCES subjects(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_steps_topic FOREIGN KEY (topic_id) REFERENCES topics(id)
        ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_steps_content FOREIGN KEY (content_id) REFERENCES contents(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_steps_exam FOREIGN KEY (exam_id) REFERENCES exams(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_steps_counsel_task FOREIGN KEY (counseling_task_id) REFERENCES counseling_tasks(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE student_counseling_task_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    counseling_task_id BIGINT UNSIGNED NOT NULL,

    done_at DATETIME NOT NULL,
    self_rating TINYINT UNSIGNED NULL,
    notes TEXT NULL,

    INDEX idx_sctl_student(student_id),

    CONSTRAINT fk_sctl_student
        FOREIGN KEY (student_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_sctl_task
        FOREIGN KEY (counseling_task_id) REFERENCES counseling_tasks(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE student_daily_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,

    log_date DATE NOT NULL,
    study_minutes INT UNSIGNED NOT NULL DEFAULT 0,

    mood TINYINT UNSIGNED NULL,          -- 1..5
    stress_level TINYINT UNSIGNED NULL,  -- 1..5
    sleep_hours DECIMAL(4,1) NULL,

    free_text TEXT NULL,
    created_at TIMESTAMP NULL,

    UNIQUE KEY uq_daily(student_id, log_date),
    INDEX idx_daily_student(student_id),

    CONSTRAINT fk_daily_student
        FOREIGN KEY (student_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* =========================================================
   FINALIZE
   ========================================================= */

SET foreign_key_checks = 1;
