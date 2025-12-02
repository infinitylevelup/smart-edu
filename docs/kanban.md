# Smart-Edu Kanban Checklist (Ready for Trello / Notion / GitHub Projects)

> Columns: **Backlog → To Do (Phase X) → In Progress → Review/Test → Done ✅**

---

## BACKLOG (All Epics & Features)

### Epic A — Authentication & Roles ✅

-   [x] A1 OTP register/login
-   [x] A2 OTP verify flow
-   [x] A3 Role-based redirect after login
-   [x] A4 Role middleware protection
-   [x] A5 Guest/App layouts separation

---

### Epic B — Public Exams (No Class Required)

-   [ ] B1 Student: public exams list page
-   [ ] B2 Student: exam start page + timer UX
-   [ ] B3 Student: submit answers
-   [ ] B4 System: create attempt + scoring
-   [ ] B5 Student: result page

---

### Epic C — Classrooms System

-   [ ] C1 Teacher: classroom CRUD (create/list/show/edit/delete)
-   [ ] C2 Teacher: generate join code/link
-   [ ] C3 Student: join classroom (code/request)
-   [ ] C4 Teacher: approve/reject join requests
-   [ ] C5 Student: view joined classrooms
-   [ ] C6 Classroom: announcements/resources (optional)

---

### Epic D — Classroom Exams (Members Only)

-   [ ] D1 Teacher: create classroom exam
-   [ ] D2 System: restrict visibility to classroom members
-   [ ] D3 Student: attempt classroom exam
-   [ ] D4 Teacher: exam monitoring dashboard

---

### Epic E — Analysis Engine

-   [ ] E1 System: academic analysis after attempt
-   [ ] E2 System: developmental analysis scaffold
-   [ ] E3 Student: analysis view page
-   [ ] E4 Counselor: complete/validate developmental analysis
-   [ ] E5 System: analysis history per student

---

### Epic F — Personalized Learning Path

-   [ ] F1 System: generate learning path from analysis
-   [ ] F2 Teacher: edit/approve path
-   [ ] F3 Counselor: edit/approve path
-   [ ] F4 Student: path view page
-   [ ] F5 System: auto-refresh path after new attempt

---

### Epic G — Specialist Matching & Requests

-   [ ] G1 System: suggest teacher/counselor per student
-   [ ] G2 Student: specialist directory + filters
-   [ ] G3 Student: send specialist request
-   [ ] G4 Specialist (Teacher/Counselor): accept/decline request
-   [ ] G5 Student: request status page

---

### Epic H — Admin Oversight

-   [ ] H1 Admin: verify specialists
-   [ ] H2 Admin: public exams CRUD
-   [ ] H3 Admin: global dashboard stats
-   [ ] H4 Admin: user management (toggle active)
-   [ ] H5 Admin: classroom oversight
-   [ ] H6 Admin: reports/export

---

## TO DO — PHASE 1 (Student Fix + Public Exams)

### UI / Views

-   [ ] P1-V1 Fix `dashboard/student/index.blade.php`
-   [ ] P1-V2 Create `dashboard/student/exams/public.blade.php`
-   [ ] P1-V3 Create `dashboard/student/exams/start.blade.php`
-   [ ] P1-V4 Create `dashboard/student/attempts/result.blade.php`
-   [ ] P1-V5 Create `dashboard/student/attempts/analysis.blade.php`

### Routes

-   [ ] P1-R1 Ensure routes in `routes/student.php`:
    -   [ ] `student.exams.public`
    -   [ ] `student.exams.start`
    -   [ ] `student.exams.submit`
    -   [ ] `student.attempts.result`
    -   [ ] `student.attempts.analysis`

### Controllers

-   [ ] P1-C1 StudentExamController@publicIndex
-   [ ] P1-C2 StudentExamController@start
-   [ ] P1-C3 StudentExamController@submit (save answers + score)
-   [ ] P1-C4 StudentAttemptController@result
-   [ ] P1-C5 StudentAttemptController@analysis

### Database / Models

-   [ ] P1-DB1 exams table supports `type=public`
-   [ ] P1-DB2 exam_questions ready
-   [ ] P1-DB3 exam_attempts table ready
-   [ ] P1-DB4 exam_attempt_answers table ready
-   [ ] P1-DB5 academic_analyses table ready (basic json)

### Acceptance

-   [ ] P1-A1 Public exams visible without class join
-   [ ] P1-A2 Student can take exam and see result
-   [ ] P1-A3 Academic analysis shown after attempt

---

## TO DO — PHASE 2 (Classroom + Classroom Exams)

### Teacher Classrooms

-   [ ] P2-T1 Create TeacherClassroomController CRUD
-   [ ] P2-T2 Views: classrooms index/create/show
-   [ ] P2-T3 Generate join code
-   [ ] P2-T4 Approve/reject join requests

### Student Join Class

-   [ ] P2-S1 Join form page
-   [ ] P2-S2 Join request submit
-   [ ] P2-S3 List joined classrooms

### Classroom Exams

-   [ ] P2-E1 Teacher create classroom exam
-   [ ] P2-E2 Student list classroom exams
-   [ ] P2-E3 Visibility rule (member-only)
-   [ ] P2-E4 Teacher monitoring UI

### Acceptance

-   [ ] P2-A1 Only members see classroom exams
-   [ ] P2-A2 Teacher sees participation stats

---

## TO DO — PHASE 3 (Developmental Analysis + Learning Path)

### Counselor Panel

-   [ ] P3-CN1 Counselor dashboard + assigned students
-   [ ] P3-CN2 Requests inbox accept/decline
-   [ ] P3-CN3 Developmental analysis form
-   [ ] P3-CN4 Save/validate developmental analysis

### Learning Path

-   [ ] P3-L1 System path generator
-   [ ] P3-L2 Teacher approve/edit path
-   [ ] P3-L3 Counselor approve/edit path
-   [ ] P3-L4 Student path view

### Acceptance

-   [ ] P3-A1 Dev analysis added by counselor
-   [ ] P3-A2 Personalized path visible to student
-   [ ] P3-A3 Path updates after new attempt

---

## TO DO — PHASE 4 (Specialists + Admin)

### Specialists

-   [ ] P4-SP1 Specialist directory for students
-   [ ] P4-SP2 System suggestion logic
-   [ ] P4-SP3 Request send + status
-   [ ] P4-SP4 Specialist accept/decline UI

### Admin

-   [ ] P4-AD1 Admin verify specialists
-   [ ] P4-AD2 Admin public exams CRUD
-   [ ] P4-AD3 Admin stats dashboard
-   [ ] P4-AD4 Admin user toggle active
-   [ ] P4-AD5 Admin reports

### Acceptance

-   [ ] P4-A1 Only verified specialists shown
-   [ ] P4-A2 Admin controls public exams lifecycle
-   [ ] P4-A3 Admin has global reporting

---
