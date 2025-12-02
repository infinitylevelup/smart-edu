# Admin Scenario & Flow (Smart Education System)

This document describes the Admin-side functional scenario for the Smart Education System.

---

## 1. Overview

Admins supervise and manage the entire platform:

-   Users (students, teachers, counselors)
-   Classrooms and exams
-   System content and policies
-   Reports and analytics
-   Specialist verification and availability

---

## 2. Admin Flow (Step-by-Step)

### Step 0 — Authentication

Admin logs in (OTP + role-based access supported).

---

### Step 1 — Admin Dashboard

Admin sees global system stats:

-   Total users by role
-   Active classrooms
-   Exams (public + classroom)
-   Attempts per day/week
-   System alerts

---

### Step 2 — User Management

Admin can:

1. **Approve / Verify Specialists**

    - Teachers
    - Counselors
    - Verify profile, subject/skill, availability.

2. **Manage Users**
    - list/search/filter users
    - activate/deactivate accounts
    - reset user access if needed

---

### Step 3 — Public Exam Management

Admins manage platform-wide public exams:

-   create/update/delete exams
-   define categories and grades
-   publish/unpublish exams
-   maintain question banks

Public exams are always visible to all students.

---

### Step 4 — Classroom Oversight

Admin monitors classrooms:

-   list classrooms by teacher/grade/subject
-   check classroom activity
-   resolve complaints/issues
-   disable/remove problematic classrooms

---

### Step 5 — Analytics & Reports

Admin views:

-   global performance trends
-   exam difficulty metrics
-   student success/failure distribution
-   counselor/teacher activity
-   top weak topics per grade

Admin can export reports for decision-making.

---

### Step 6 — System Configuration

Admin controls:

-   roles and permissions
-   OTP/auth settings
-   content visibility rules
-   policy pages (terms/privacy)
-   notification templates

---

## 3. Required Admin Modules (Implementation Map)

### Routes / Pages

-   Admin dashboard
-   User list & management panel
-   Specialist verification panel
-   Public exams CRUD
-   Question bank CRUD
-   Classroom oversight panel
-   Analytics & reporting pages
-   Settings/config pages

### Core Entities

-   Users & roles
-   Specialist profiles + verification state
-   Public exams + questions
-   Classrooms + metadata
-   Attempts & global analytics
-   System settings

---

## 4. Notes

-   Public exams are under admin control.
-   Specialist approval should be mandatory before appearing to students.
-   Analytics drives platform improvement and content tuning.

---
