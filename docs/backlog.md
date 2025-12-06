# Smart‑Edu Backlog

## Current Status Sync

Latest backlog synchronization reflecting the real project state after completing **Student UI – Phase 1** and the recent infrastructure fixes.

---

# Current Status — Student UI (Phase 1)

## A) Fixed / Completed Blade Files ✅

1. `resources/views/dashboard/student/exams/public.blade.php`
2. `resources/views/dashboard/student/exams/classroom.blade.php`
3. `resources/views/dashboard/student/exams/show.blade.php`
4. `resources/views/dashboard/student/exams/take.blade.php`
5. `resources/views/dashboard/student/attempts/result.blade.php`

## B) Backend Aligned Files ✅

### Controllers

1. `app/Http/Controllers/Student/StudentExamController.php`

### Models

> Models are now aligned with the **current MySQL export**.

1. `app/Models/ExamAttempt.php` _(replaces the old Attempt naming)_
2. `app/Models/AttemptAnswer.php`
3. `app/Models/Exam.php`
4. `app/Models/Question.php`
5. `app/Models/Classroom.php`
6. `app/Models/Content.php`
7. `app/Models/Role.php`
8. `app/Models/User.php`
9. Other generated models exist and match the SQL schema, but relationships are not yet refined.

### Migrations

> All migration stubs were regenerated from the SQL export.  
> **They are documentation-only for now** until FK/index order is corrected.

## C) Not Yet Updated Blade Files ⏳

1. `resources/views/dashboard/student/classrooms/index.blade.php`
2. `resources/views/dashboard/student/classrooms/show.blade.php`
3. `resources/views/dashboard/student/classrooms/join-class.blade.php`
4. `resources/views/dashboard/student/reports/index.blade.php`
5. `resources/views/dashboard/student/support/*`
6. `resources/views/dashboard/student/learning-path.blade.php`
7. `resources/views/dashboard/student/my-teachers.blade.php`
8. `resources/views/dashboard/student/profile.blade.php`

---

## D) High‑Risk Items / Must Fix Next ⚠️

1. **Eloquent relationships are not defined yet** (must be rebuilt from FK constraints in SQL).  
   Examples: `classrooms ↔ users`, `exams ↔ exam_attempts`, `subjects ↔ topics`, etc.
2. **Branch model naming**: generated `Branche.php` should be renamed/refactored to `Branch.php` for Laravel conventions.
3. **Migration safety**: do **not** run `php artisan migrate` until:
    - all FK + indexes are added
    - migration dependency order is corrected
    - pivot tables are reviewed

---

# Work Completed (Log / Appendix) ✅

1. **OTP Login Flow Fully Fixed**

    - Step-based modal OTP login is stable (send → verify → role select on first login).
    - CSRF token refresh after OTP verification was implemented to prevent 419 errors.
    - The logic now redirects correctly based on `need_role` / `redirect` flags.

2. **Frontend Asset Loading Cleanup**

    - Duplicate/irrelevant JS includes on the landing page were removed to prevent conflicts.
    - `auth.js` was confirmed present and correctly loaded only where needed.

3. **Auth UI Notifications Improved**

    - All `alert()` calls were removed from `auth.js`.
    - Replaced with UI notifications (toast/notif pattern) for a cleaner UX.

4. **Database Restored from Full MySQL Export**

    - The project database was re‑imported from `smart_edu_db.sql` (phpMyAdmin export).
    - This SQL file is now treated as the **source of truth** for schema.

5. **Migrations & Models Regenerated from SQL**

    - A generator script (`Z_CreateAlMigrastionAndModle.sh`) was created and executed.
    - For each `CREATE TABLE` block, a migration + model were produced with unique timestamps.
    - Pivot tables were detected and their models were skipped automatically.

6. **Post‑Rebuild Caution Noted**
    - Because schema was rebuilt automatically, risk of mismatch increased.
    - Next phase will start by re‑validating models, relationships, and key naming.

---

## Next Action (Start Phase 2)

1. Reconstruct accurate model relationships from FK constraints in the SQL export.
2. Then update Student Classroom Blade files to connect real data to UI.
