# Counselor Scenario & Flow (Smart Education System)

This document describes the Counselor-side functional scenario for the Smart Education System.

---

## 1. Overview

Counselors (educational & developmental) support students by:

-   reviewing academic and psychological/developmental analyses
-   identifying root causes of weaknesses
-   contributing to personalized learning paths
-   providing guidance sessions and follow-ups
-   collaborating with teachers for holistic student growth

Counselors may be auto-suggested by the system or manually chosen by the student.

---

## 2. Counselor Flow (Step-by-Step)

### Step 0 — Authentication

Counselor logs in (OTP + role-based access supported).

---

### Step 1 — Counselor Dashboard

Counselor enters dashboard and sees:

-   Assigned Students
-   New Analysis Requests
-   Student Profiles
-   Sessions / Notes
-   Collaboration with Teachers

---

### Step 2 — Receive Student Assignments

Two ways students reach counselors:

1. **System Recommendation**

    - System assigns/suggests counselor based on student needs.

2. **Student Manual Selection**
    - Student selects counselor from specialists list.
    - Counselor receives a request to accept/decline.

Counselor can:

-   accept request
-   decline request
-   schedule first consultation

---

### Step 3 — Review Student Exam Results & Academic Analysis

Counselor reviews:

-   student exam attempts
-   academic strengths/weaknesses
-   progress trends over time

This helps detect whether weaknesses are:

-   conceptual gaps
-   study habit issues
-   motivation/attention problems
-   anxiety/performance blocks

---

### Step 4 — Developmental / Psychological Assessment

Counselor completes or validates the developmental analysis by:

-   reviewing system indicators
-   using standard counseling rubrics
-   adding professional notes

Areas may include:

-   learning motivation
-   attention and focus
-   test anxiety
-   self-confidence
-   emotional balance
-   classroom behavior and growth mindset

Counselor outputs:

-   developmental strengths
-   developmental weaknesses
-   risk level (if applicable)
-   recommended interventions

---

### Step 5 — Build / Approve Personalized Learning Path

Counselor collaborates with teacher + system to finalize Learning Path:

-   prioritize weak academic topics
-   recommend psychological/developmental exercises
-   suggest pacing and workload suited to student state

Counselor may:

-   add supportive activities
-   recommend follow-up exams
-   set milestones for review

---

### Step 6 — Provide Guidance & Follow-up

Counselor supports student through:

-   counseling notes/messages in system
-   scheduled online/in-person sessions (if enabled)
-   continuous monitoring after new exams

After each new exam attempt:

-   system updates analysis
-   counselor reviews changes
-   path is refreshed if needed

---

### Step 7 — Collaboration with Teachers

Counselor shares relevant developmental insights with teachers:

-   academic issues linked to emotional factors
-   attention/motivation support tips
-   student-specific teaching approach suggestions

Teacher shares academic progress; counselor shares developmental progress.

---

## 3. Required Counselor Modules (Implementation Map)

### Routes / Pages

-   Counselor dashboard
-   Assigned students list
-   Student profile view
-   Student attempts & academic analysis view
-   Developmental analysis form/view
-   Personalized learning path approval/edit page
-   Specialist requests inbox (accept/decline)
-   Counselor notes + session history page

### Core Entities

-   Specialist profiles (counselor)
-   Specialist requests (student → counselor)
-   Student profiles
-   Exam attempts + academic analysis
-   Developmental analysis + counselor notes
-   Personalized learning paths
-   Teacher–Counselor collaboration notes

---

## 4. Notes

-   Counselor analysis is essential for psychological/developmental dimension.
-   Final personalized paths should consider both academic and developmental factors.
-   Counselor visibility to students depends on admin verification.
-   Continuous follow-up after each attempt is required to keep path updated.

---
