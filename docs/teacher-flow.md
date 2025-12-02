# Teacher Scenario & Flow (Smart Education System)

This document describes the Teacher-side functional scenario for the Smart Education System.

---

## 1. Overview

Teachers manage classrooms, create and deliver classroom exams, monitor student performance, and collaborate with counselors to support students academically and developmentally.

---

## 2. Teacher Flow (Step-by-Step)

### Step 0 — Authentication

Teacher signs up / logs in (OTP + role-based access supported).

---

### Step 1 — Dashboard Access

Teacher enters their dashboard and sees:

-   My Classrooms
-   Classroom Exams
-   Student Analytics
-   Requests / Messages

---

### Step 2 — Classroom Management

Teacher can:

1. **Create Classroom**

    - Define class title, grade, subject, description.
    - Generate class join code/link.

2. **Manage Classroom**
    - View enrolled students.
    - Approve/reject join requests (if approval mode enabled).
    - Post announcements/resources.

---

### Step 3 — Create Classroom Exams

Teacher creates exams that are only visible to students who joined the classroom.

Teacher defines:

-   Exam title and description
-   Related classroom
-   Time limit and exam window
-   Questions and answers
-   Scoring rules

---

### Step 4 — Publish & Monitor Exams

After publishing:

-   Students take the exam.
-   Teacher monitors:
    -   participation rate
    -   completion status
    -   average scores
    -   topic-wise performance

---

### Step 5 — Review Student Performance

Teacher reviews each attempt in detail:

-   Overall score
-   Strengths and weaknesses per topic
-   Progress trends across attempts

Teacher can:

-   add academic notes per student
-   flag weak topics for follow-up
-   recommend specific resources

---

### Step 6 — Collaboration with Counselors

Teacher shares student academic analysis with counselors:

-   students with academic risk
-   students needing special attention
-   recommended learning actions

Counselor completes developmental/psychological analysis, and both sides contribute to personalized learning path.

---

### Step 7 — Student Support

Teacher supports students through:

-   targeted exercises
-   follow-up quizzes
-   personalized guidance inside the class

---

## 3. Required Teacher Modules (Implementation Map)

### Routes / Pages

-   Teacher dashboard
-   Create/edit classrooms
-   Classroom student list + join requests
-   Create/edit classroom exams
-   Exam monitoring page
-   Student attempts & academic analysis page
-   Teacher notes / recommendations page

### Core Entities

-   Classrooms
-   Classroom membership & requests
-   Classroom exams
-   Exam attempts
-   Academic analysis
-   Teacher notes/recommendations

---

## 4. Notes

-   Classroom exams are only for enrolled students.
-   Teacher academic analysis should update after each attempt.
-   Personalized paths are produced in collaboration with counselor analysis.

---
