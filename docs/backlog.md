# Smart-Edu Development Backlog (Role-Based Scenarios → Implementation Map)

This backlog is derived from the role-based scenarios (Student, Teacher, Counselor, Admin).
It serves as the master roadmap for implementing and fixing the Smart Education System.

---

## 0. Architecture Baseline (Current Project Style)

### Modular Role-Based Routing

-   `routes/web.php` → includes role modules
-   `routes/auth.php`
-   `routes/student.php`
-   `routes/teacher.php`
-   `routes/counselor.php` _(to be added if not present)_
-   `routes/admin.php`

### Role-Based Controllers

-   `App\Http\Controllers\Auth\*`
-   `App\Http\Controllers\Student\*`
-   `App\Http\Controllers\Teacher\*`
-   `App\Http\Controllers\Counselor\*`
-   `App\Http\Controllers\Admin\*`

### Role-Based Views

-   `resources/views/dashboard/student/*`
-   `resources/views/dashboard/teacher/*`
-   `resources/views/dashboard/counselor/*`
-   `resources/views/dashboard/admin/*`

---

## 1. Epics & Features

### Epic A — Authentication & Roles ✅ (Mostly Done)

**Goal:** Secure OTP auth + correct role access.

**Features**

-   A1. OTP register/login
-   A2. OTP verify flow
-   A3. Role-based redirect after login
-   A4. Role middleware protection for dashboards
-   A5. Guest vs App layouts unified

**Acceptance Criteria**

-   Guest → Landing → Login → OTP verify → Role dashboard
-   No dashboard accessible without auth and correct role.

---

### Epic B — Public Exams (No Class Required)

**Goal:** Students can take global/public exams anytime.

**Features**

-   B1. Public exams list for students
-   B2. Exam start page + timer
-   B3. Answer submission
-   B4. Attempt scoring + storage
-   B5. Result page

**Acceptance Criteria**

-   Student sees public exams without joining class.
-   Attempt + answers stored; result visible immediately.

---

### Epic C — Classrooms System

**Goal:** Teachers create classes; students join with code/request.

**Features**

-   C1. Teacher creates classroom (CRUD)
-   C2. Join code/link generation
-   C3. Student joins classroom (request or direct)
-   C4. Teacher approves/rejects requests (if enabled)
-   C5. Students view joined classrooms
-   C6. Classroom announcements/resources _(Phase 2+ optional)_

**Acceptance Criteria**

-   Student only sees classroom content after joining.
-   Teacher sees member list and join status.

---

### Epic D — Classroom Exams (Members Only)

**Goal:** Exams tied to classrooms, visible only to members.

**Features**

-   D1. Teacher creates classroom exam
-   D2. Restrict visibility to classroom members
-   D3. Student attempts classroom exam
-   D4. Teacher monitoring dashboard

**Acceptance Criteria**

-   Non-member student cannot view/start classroom exams.
-   Teacher sees participation + avg scores.

---

### Epic E — Analysis Engine (Academic + Developmental)

**Goal:** Generate strengths/weaknesses after each attempt.

**Features**

-   E1. Automatic academic analysis (topics/subjects)
-   E2. Developmental/psychological analysis scaffold
-   E3. Student analysis page
-   E4. Counselor completes/validates developmental analysis
-   E5. Analysis history per student

**Acceptance Criteria**

-   Every attempt produces academic analysis.
-   Counselor can add developmental insights.

---

### Epic F — Personalized Learning Path

**Goal:** System proposes a dynamic learning path per student.

**Features**

-   F1. System-generated learning path after analysis
-   F2. Teacher + counselor can edit/approve path
-   F3. Student path view
-   F4. Path updates after new attempts

**Acceptance Criteria**

-   Student has 1 active path.
-   Path refreshes with new attempt data.

---

### Epic G — Specialist Matching & Requests

**Goal:** Assign or let student choose teachers/counselors.

**Features**

-   G1. System suggests teacher/counselor
-   G2. Specialist directory (filterable)
-   G3. Student sends specialist request
-   G4. Specialist accepts/declines
-   G5. Student sees request progress/status

**Acceptance Criteria**

-   Student can accept system suggestion or choose manually.
-   Specialists manage requests properly.

---

### Epic H — Admin Oversight

**Goal:** Platform governance, public content control, analytics.

**Features**

-   H1. Verify/approve specialists
-   H2. CRUD public exams + question banks
-   H3. Global dashboard stats
-   H4. Users management (activate/deactivate)
-   H5. Classrooms oversight
-   H6. Reports export

**Acceptance Criteria**

-   Unverified specialists don’t show to students.
-   Admin controls public exams lifecycle.

---

## 2. Implementation Map per Role

---

### 2.1 Student Implementation Map

#### Routes (`routes/student.php`)

-   `GET  student/dashboard` → `student.dashboard`
-   `GET  student/exams/public` → `student.exams.public`
-   `GET  student/exams/classroom` → `student.exams.classroom`
-   `GET  student/exams/{exam}/start` → `student.exams.start`
-   `POST student/exams/{exam}/submit` → `student.exams.submit`
-   `GET  student/attempts/{attempt}/result` → `student.attempts.result`
-   `GET  student/attempts/{attempt}/analysis` → `student.attempts.analysis`
-   `GET  student/learning-path` → `student.path.show`
-   `GET  student/specialists/suggested` → `student.specialists.suggested`
-   `GET  student/specialists` → `student.specialists.index`
-   `POST student/specialists/{id}/request` → `student.specialists.request`
-   `GET  student/classrooms/join` → `student.classrooms.joinForm`
-   `POST student/classrooms/join` → `student.classrooms.join`

#### Controllers (`Controllers/Student`)

-   `StudentDashboardController@index`
-   `StudentExamController`
    -   `publicIndex()`
    -   `classroomIndex()`
    -   `start($examId)`
    -   `submit($examId)`
-   `StudentAttemptController`
    -   `result($attemptId)`
    -   `analysis($attemptId)`
-   `StudentLearningPathController@show`
-   `StudentSpecialistController`
    -   `suggested()`
    -   `index()`
    -   `request($specialistId)`
-   `StudentClassroomController`
    -   `joinForm()`
    -   `join()`

#### Views (`dashboard/student`)

-   `index.blade.php`
-   `exams/public.blade.php`
-   `exams/classroom.blade.php`
-   `exams/start.blade.php`
-   `attempts/result.blade.php`
-   `attempts/analysis.blade.php`
-   `path/show.blade.php`
-   `specialists/suggested.blade.php`
-   `specialists/index.blade.php`
-   `classrooms/join-class.blade.php` ✅ existing

---

### 2.2 Teacher Implementation Map

#### Routes (`routes/teacher.php`)

-   `GET  teacher/dashboard` → `teacher.dashboard`
-   `GET  teacher/classrooms` → `teacher.classrooms.index`
-   `GET  teacher/classrooms/create` → `teacher.classrooms.create`
-   `POST teacher/classrooms` → `teacher.classrooms.store`
-   `GET  teacher/classrooms/{classroom}` → `teacher.classrooms.show`
-   `POST teacher/classrooms/{classroom}/requests/{req}/approve`
-   `POST teacher/classrooms/{classroom}/requests/{req}/reject`
-   `GET  teacher/exams` → `teacher.exams.index`
-   `GET  teacher/exams/create` → `teacher.exams.create`
-   `POST teacher/exams` → `teacher.exams.store`
-   `GET  teacher/exams/{exam}/monitor` → `teacher.exams.monitor`
-   `GET  teacher/students/{student}/analysis` → `teacher.students.analysis`
-   `POST teacher/students/{student}/notes` → `teacher.students.notes`

#### Controllers (`Controllers/Teacher`)

-   `TeacherDashboardController@index`
-   `TeacherClassroomController`
    -   `index, create, store, show`
    -   `approveRequest($classroomId, $reqId)`
    -   `rejectRequest($classroomId, $reqId)`
-   `TeacherExamController`
    -   `index, create, store, monitor`
-   `TeacherStudentController`
    -   `analysis($studentId)`
    -   `addNote($studentId)`

#### Views (`dashboard/teacher`)

-   `index.blade.php`
-   `classrooms/index.blade.php`
-   `classrooms/create.blade.php`
-   `classrooms/show.blade.php`
-   `exams/index.blade.php`
-   `exams/create.blade.php`
-   `exams/monitor.blade.php`
-   `students/analysis.blade.php`

---

### 2.3 Counselor Implementation Map

#### Routes (`routes/counselor.php`)

-   `GET  counselor/dashboard` → `counselor.dashboard`
-   `GET  counselor/requests` → `counselor.requests.index`
-   `POST counselor/requests/{req}/accept`
-   `POST counselor/requests/{req}/decline`
-   `GET  counselor/students` → `counselor.students.index`
-   `GET  counselor/students/{student}/profile` → `counselor.students.profile`
-   `GET  counselor/students/{student}/attempts/{attempt}/analysis`
-   `POST counselor/students/{student}/developmental-analysis`
-   `GET  counselor/students/{student}/learning-path`
-   `POST counselor/students/{student}/learning-path/approve`
-   `POST counselor/students/{student}/notes`

#### Controllers (`Controllers/Counselor`)

-   `CounselorDashboardController@index`
-   `CounselorRequestController@index, accept, decline`
-   `CounselorStudentController`
    -   `index()`
    -   `profile($studentId)`
    -   `analysis($studentId, $attemptId)`
    -   `storeDevelopmentalAnalysis($studentId)`
    -   `learningPath($studentId)`
    -   `approveLearningPath($studentId)`
    -   `addNote($studentId)`

#### Views (`dashboard/counselor`)

-   `index.blade.php`
-   `requests/index.blade.php`
-   `students/index.blade.php`
-   `students/profile.blade.php`
-   `students/analysis.blade.php`
-   `students/developmental-form.blade.php`
-   `students/path.blade.php`

---

### 2.4 Admin Implementation Map

#### Routes (`routes/admin.php`)

-   `GET  admin/dashboard` → `admin.dashboard`
-   `GET  admin/users` → `admin.users.index`
-   `PATCH admin/users/{user}/toggle`
-   `GET  admin/specialists` → `admin.specialists.index`
-   `POST admin/specialists/{sp}/verify`
-   `GET  admin/exams/public` → `admin.exams.public.index`
-   `GET  admin/exams/public/create` → `admin.exams.public.create`
-   `POST admin/exams/public` → `admin.exams.public.store`
-   `GET  admin/exams/public/{exam}/edit` → `admin.exams.public.edit`
-   `PATCH admin/exams/public/{exam}` → `admin.exams.public.update`
-   `DELETE admin/exams/public/{exam}` → `admin.exams.public.destroy`
-   `GET  admin/classrooms` → `admin.classrooms.index`
-   `GET  admin/reports` → `admin.reports.index`

#### Controllers (`Controllers/Admin`)

-   `AdminDashboardController@index`
-   `AdminUserController@index, toggle`
-   `AdminSpecialistController@index, verify`
-   `AdminPublicExamController`
    -   `index, create, store, edit, update, destroy`
-   `AdminClassroomController@index`
-   `AdminReportController@index`

#### Views (`dashboard/admin`)

-   `index.blade.php`
-   `users/index.blade.php`
-   `specialists/index.blade.php`
-   `exams/public/index.blade.php`
-   `exams/public/create.blade.php`
-   `exams/public/edit.blade.php`
-   `classrooms/index.blade.php`
-   `reports/index.blade.php`

---

## 3. Data Model Checklist (Minimum Required)

### Core Tables

-   `users` (role, status, otp fields)
-   `classrooms`
-   `classroom_memberships` (student_id, classroom_id, status)
-   `classroom_requests` _(if approval flow is used)_

### Exams

-   `exams` (type = public|classroom, classroom_id nullable)
-   `exam_questions`
-   `exam_choices` _(if MCQ)_
-   `exam_attempts` (student_id, exam_id, score, status)
-   `exam_attempt_answers`

### Analysis

-   `academic_analyses` (attempt_id, strengths json, weaknesses json)
-   `developmental_analyses` (attempt_id, counselor_id, notes, strengths json, weaknesses json)

### Learning Path

-   `learning_paths` (student_id, status)
-   `learning_path_items` (path_id, type, ref_id, priority)

### Specialists

-   `specialist_profiles` (user_id, role teacher|counselor, subjects/skills, verified)
-   `specialist_requests` (student_id, specialist_id, status)

---

## 4. Roadmap / Phases

### Phase 1 — Student Fix + Public Exams (NOW)

1. Student dashboard UI fix
2. Public exams list/start/submit/result
3. Academic analysis (basic)
4. Student analysis UI

### Phase 2 — Classroom + Classroom Exams

1. Teacher classroom CRUD
2. Student join class flow
3. Classroom exams CRUD + visibility rules
4. Teacher monitoring UI

### Phase 3 — Developmental Analysis + Learning Path

1. Counselor requests + assigned students
2. Developmental analysis form + approval
3. System learning path generator
4. Teacher/counselor path approval
5. Student path UI

### Phase 4 — Specialists + Admin Oversight

1. Specialist directory + student requests
2. Specialist accept/decline UI
3. Admin verification panel
4. Admin public exams + reports

---

## 5. Working Rule

Whenever a feature is completed:

-   Update this backlog by marking it ✅
-   Ensure routes/controllers/views follow the maps above
-   Add/adjust migrations + seeders as required

---
