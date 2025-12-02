# Student Scenario & Flow (Smart Education System)

This document describes the Student-side functional scenario for the Smart Education System.

---

## 1. Overview

A student can take exams in two categories:

1. **Public Exams (No Class Membership Required)**

    - Visible to all students.
    - Used for general assessment and initial evaluation.

2. **Classroom Exams (Class Membership Required)**
    - Visible only after the student joins a classroom.
    - Exams are tied to specific classes, lessons, and teachers.

Regardless of category, the student takes exams and receives a detailed analysis.

---

## 2. Student Flow (Step-by-Step)

### Step 0 — Authentication

Student signs up / logs in (OTP + role-based access supported).

---

### Step 1 — Choose Exam Type

Student enters the dashboard and sees:

-   **Public Exams**
-   **My Classroom Exams** (only if they have joined a class)

---

### Step 2 — Take Exam

Student starts an exam:

-   Reads exam instructions (time limit, number of questions, rules).
-   Answers questions with timer support.
-   Submits exam to finalize attempt.

---

### Step 3 — Result Analysis (Strengths & Weaknesses)

After submission, the system generates analysis in two dimensions:

1. **Academic Strengths & Weaknesses**

    - Based on subject/topic performance.
    - Shows strong areas and weak areas.
    - Provides scores/percentages per topic.

2. **Psychological / Developmental Strengths & Weaknesses**
    - Reflects emotional, motivational, and educational-growth factors.
    - Produced using system indicators and counselor evaluation.
    - Examples: focus, motivation, anxiety level, learning attitude, etc.

---

### Step 4 — Personalized Learning Path Suggestion

Based on combined analysis (academic + developmental):

-   System proposes a **Personalized Learning Path**
    -   Prioritized lessons/topics
    -   Recommended resources (videos, exercises, quizzes)
    -   Next-step exams to reinforce weak areas

---

### Step 5 — Specialist (Teacher/Counselor) Assignment or Selection

Two supported modes:

1. **System Recommendation**

    - Automatically suggests suitable:
        - **Teacher** (academic guidance)
        - **Counselor** (psychological/developmental guidance)
    - Based on the student’s needs and performance profile.

2. **Student Manual Selection**
    - Student can browse specialists registered in the system.
    - Can select preferred teacher/counselor and request support.

---

## 3. Required Student Modules (Implementation Map)

For full scenario support, the Student-side should include:

### Routes / Pages

-   Student dashboard
-   Public exams list
-   Classroom exams list
-   Exam start / submit
-   Exam results
-   Analysis view
-   Personalized learning path view
-   Specialists suggested + specialists list + request page

### Core Entities

-   Exams (public/classroom)
-   Questions
-   Attempts + answers
-   Academic analysis
-   Developmental analysis
-   Personalized learning path
-   Specialists (teachers/counselors) + requests

---

## 4. Notes

-   Public exams are always available.
-   Classroom exams become visible only after class join.
-   Personalized learning path is dynamic and should update after every new attempt.
-   Developmental analysis may require counselor input/approval.

---
