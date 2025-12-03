# Student Phase 1 Structure Fix

Goal: Align the Student module structure with the official scenario and backlog,
so we can safely fill in content afterward without future renaming/rework.

---

## 1) Target Student Views Structure

resources/views/dashboard/student/
index.blade.php
learning-path.blade.php
my-teachers.blade.php
profile.blade.php

classrooms/
index.blade.php
join-class.blade.php
show.blade.php

exams/
public.blade.php (NEW)
classroom.blade.php (NEW)
show.blade.php
take.blade.php

attempts/
result.blade.php (MOVE from exams/result)
analysis.blade.php (NEW)

reports/
index.blade.php

support/
index.blade.php

yaml
Copy code

---

## 2) View Changes Required

-   Create 3 new files:

    -   `student/exams/public.blade.php`
    -   `student/exams/classroom.blade.php`
    -   `student/attempts/analysis.blade.php`

-   Move/rename result view:

    -   From: `student/exams/result.blade.php`
    -   To: `student/attempts/result.blade.php`

-   Remove unused files:

    -   `student/support.blade.php`
    -   `student/teachers.blade.php`

-   Edit student dashboard:
    -   `student/index.blade.php`
    -   Add two clear links: **Public Exams** and **Classroom Exams**

---

## 3) Route Updates Required (`routes/student.php`)

-   Split exam lists into two routes:

    -   `student.exams.public`
    -   `student.exams.classroom`

-   Split attempt result vs analysis:
    -   `student.attempts.result`
    -   `student.attempts.analysis`

---

## 4) Controller Updates Required

File:

-   `app/Http/Controllers/Student/StudentExamController.php`

Required methods:

-   `publicIndex()`
-   `classroomIndex()`
-   `result($attemptId)`
-   `analysis($attemptId)`

Also update old result view path:

-   Replace `dashboard.student.exams.result`
-   With `dashboard.student.attempts.result`

---

## 5) Minimum Files Affected

-   Views: 7 files
-   PHP: 2 files  
    Total: **9 files**

---
