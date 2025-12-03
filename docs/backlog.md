### ✅ Done

-   Counselor views scaffolded.
-   `routes/counselor.php` created and grouped under dashboard.
-   Placeholder controllers created with correct namespace:
    -   `CounselorDashboardController`
    -   `CounselorStudentController`
    -   `CounselorAttemptController`
    -   `CounselorLearningPathController`
    -   `CounselorProfileController`
-   Laravel 12 middleware aliases registered in `bootstrap/app.php`.

### 🔜 Next

-   Real counselor features:
    1. Students list with search/filters.
    2. Attempt review (per-question correctness + notes).
    3. Counselor analysis form → stored in DB.
    4. Create/persist personalized learning path per student.
    5. Counselor reports dashboard (risk students, top weaknesses).

---

## 4) Teacher Module

### 🟡 Partially Done

-   Teacher dashboards + routes exist in project tree.

### 🔜 Next

-   Teacher creates exams per classroom.
-   Question bank UI.
-   Publish/unpublish & active/inactive toggles.
-   Manual grading for essay questions.
-   Teacher analytics linked to student attempts.

---

## 5) Admin Module

### 🟡 Partially Done

-   Admin routes/controllers/views exist in project tree.

### 🔜 Next

-   Manage users/roles.
-   Approve counselors/teachers.
-   System-wide settings (levels, scopes, durations).
-   Content moderation.

---

## 6) Data Model / Technical Debt

### ✅ Done

-   Factories stabilized to match DB schema.
-   `HasFactory` added where required.
-   Seeders safe for OTP-based users.
-   Subject FK issues resolved via `SubjectSeeder`.

### 🔜 Next

-   Decide final storage for `exams.level`:
    -   **Option A:** enum strings (`taghviyati`, `konkur`, `olympiad`).
    -   **Option B:** numeric levels (1–3) with code mapping.
-   Confirm `Classroom` model namespace + user relation correctness.
-   Add tests for exam visibility rules.

---

## 7) Immediate Next Sprint (Suggested)

1. Finalize counselor DB flows:
    - analysis save table
    - learning_path save table
2. Teacher exam creation UI
3. Student reports v1
4. Sidebar refactor for all roles
5. Unify `exams.level` type

---
