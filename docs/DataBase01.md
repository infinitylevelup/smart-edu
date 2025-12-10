حتماً. اینم یک مستند تمیز و قابل کپی داخل پوشه‌ی مستنداتت (مثلاً `docs/database-migrations-final.md`).
من همه‌ی تغییرنام‌ها + ترتیب نهایی اجرا + قوانین آینده رو یکجا آوردم.

---

# مستند نهایی مگریشن‌ها و ترتیب اجرا (Smart-Edu)

**تاریخ نهایی‌سازی:** 2025-12-10
**هدف:** یکدست‌سازی کلیدها (UUID/FK)، رفع خطاهای FK، و تثبیت ترتیب اجرای مگریشن‌ها برای توسعه‌ی آینده.

---

## 1) ترتیب نهایی اجرای مگریشن‌ها (Final Order)

> این ترتیب دقیقاً همان ترتیبی است که در اجرای آخر شما بدون خطا بالا آمد.

1. `database/migrations/2025_12_05_103024_create_users_table.php`

**Taxonomy پایه**
2. `database/migrations/2025_12_06_102953_1_create_sections_table.php`
3. `database/migrations/2025_12_06_102953_2_0_create_grades_table.php`
4. `database/migrations/2025_12_06_102953_2_1_create_branches_table.php`
5. `database/migrations/2025_12_06_102953_3_create_fields_table.php`
6. `database/migrations/2025_12_06_102953_4_create_subfields_table.php`
7. `database/migrations/2025_12_06_102953_5_0_create_subject_types_table.php`
8. `database/migrations/2025_12_06_102953_5_1_create_subjects_table.php`

**Classrooms**
9. `database/migrations/2025_12_06_102953_create_classrooms_table.php`
10. `database/migrations/2025_12_06_102954_create_classroom_subject_table.php`
11. `database/migrations/2025_12_06_102955_create_classroom_user_table.php`

**Profiles**
12. `database/migrations/2025_12_06_102958_create_counselor_profiles_table.php`
13. `database/migrations/2025_12_06_103021_create_teacher_profiles_table.php`
14. `database/migrations/2025_12_06_103017_create_student_profiles_table.php`
15. `database/migrations/2025_12_06_103016_create_student_learning_profiles_table.php`

**Exams**
16. `database/migrations/2025_12_06_102959_create_exams_table.php`
17. `database/migrations/2025_12_06_103000_create_exam_attempts_table.php`
18. `database/migrations/2025_12_06_103002_create_exam_subject_table.php`
19. `database/migrations/2025_12_06_103120_create_exam_questions_table.php`
20. `database/migrations/2025_12_06_103200_create_attempt_answers_table.php`

**Learning Paths**
21. `database/migrations/2025_12_06_103005_create_learning_paths_table.php`
22. `database/migrations/2025_12_06_103300_create_learning_path_steps_table.php`

**Logs & Goals**
23. `database/migrations/2025_12_06_103013_create_student_counseling_task_logs_table.php`
24. `database/migrations/2025_12_06_103014_create_student_daily_logs_table.php`
25. `database/migrations/2025_12_06_103310_create_student_goals_table.php`

**Auth & Roles**
26. `database/migrations/2025_12_06_103007_create_otps_table.php`
27. `database/migrations/2025_12_06_103010_create_roles_table.php`
28. `database/migrations/2025_12_06_184150_create_role_user_table.php`

**Topics / Contents / Questions**
29. `database/migrations/2025_12_06_103023_create_topics_table.php`
30. `database/migrations/2025_12_06_103050_create_contents_table.php`
31. `database/migrations/2025_12_06_103060_create_questions_table.php`

**Assessments**
32. `database/migrations/2025_12_06_103110_create_academic_assessments_table.php`
33. `database/migrations/2025_12_06_103008_create_psycho_assessments_table.php`

**AI System**
34. `database/migrations/2025_12_06_200000_create_ai_sessions_table.php`
35. `database/migrations/2025_12_06_200010_create_ai_messages_table.php`
36. `database/migrations/2025_12_06_200020_create_ai_feedback_table.php`
37. `database/migrations/2025_12_06_200030_create_ai_artifacts_table.php`
38. `database/migrations/2025_12_10_030000_add_ai_session_fks_to_tables.php`

---

## 2) فایل‌هایی که Rename شدند (برای رفع FK Order)

این تغییرها فقط «اسم فایل» بوده، محتوا دست نخورده مگر جایی که قبلاً اصلاح ساختاری داشتیم.

* `academic_assessments`

  * از: `2025_12_06_102945_create_academic_assessments_table.php`
  * به: `2025_12_06_103110_create_academic_assessments_table.php`

* `contents`

  * از: `2025_12_06_102956_create_contents_table.php`
  * به: `2025_12_06_103050_create_contents_table.php`

* `questions`

  * از: `2025_12_06_103009_create_questions_table.php`
  * به: `2025_12_06_103060_create_questions_table.php`

* `exam_questions`

  * از: `2025_12_06_103001_create_exam_questions_table.php`
  * به: `2025_12_06_103120_create_exam_questions_table.php`

* `attempt_answers`

  * از: `2025_12_06_102951_create_attempt_answers_table.php`
  * به: `2025_12_06_103200_create_attempt_answers_table.php`

* `learning_path_steps`

  * از: `2025_12_06_103006_create_learning_path_steps_table.php`
  * به: `2025_12_06_103300_create_learning_path_steps_table.php`

* `student_goals`

  * از: `2025_12_06_103015_create_student_goals_table.php`
  * به: `2025_12_06_103310_create_student_goals_table.php`

* **AI tables (همگی منتقل به انتهای اجرا)**

  * `ai_sessions`

    * از: `2025_12_06_102950_create_ai_sessions_table.php`
    * به: `2025_12_06_200000_create_ai_sessions_table.php`
  * `ai_messages`

    * از: `2025_12_06_102949_create_ai_messages_table.php`
    * به: `2025_12_06_200010_create_ai_messages_table.php`
  * `ai_feedback`

    * از: `2025_12_06_102948_create_ai_feedback_table.php`
    * به: `2025_12_06_200020_create_ai_feedback_table.php`
  * `ai_artifacts`

    * از: `2025_12_06_102947_create_ai_artifacts_table.php`
    * به: `2025_12_06_200030_create_ai_artifacts_table.php`

---

## 3) Seederهای هماهنگ‌شده

### RoleSeeder

* ستون‌های جدول `"roles"`: `id, uuid, name, slug, is_active, created_at, updated_at`
* Seeder نهایی رول‌ها:

```php
$roles = [
    ['slug' => 'admin',     'name_fa' => 'مدیر سیستم'],
    ['slug' => 'teacher',   'name_fa' => 'معلم'],
    ['slug' => 'student',   'name_fa' => 'دانش‌آموز'],
    ['slug' => 'counselor', 'name_fa' => 'مشاور'],
];
```

و پرکردن `uuid / name / slug / is_active` مطابق نسخه نهایی.

---

## 4) قانون طلایی برای جلوگیری از خطاهای FK

**هر جدول که FK دارد باید بعد از جدول مرجعش ساخته شود.**

مثال:

* اگر `contents.topic_id -> topics.id`
  پس **topics باید قبل از contents باشد.**

راه ساده:

> در صورت خطای FK، فقط timestamp فایل وابسته را بزرگ‌تر کن تا عقب‌تر اجرا شود.

---

## 5) دستور اجرای استاندارد بانک

برای ریست کامل و ساخت مجدد:

```bash
php artisan migrate:fresh --seed
```

---

اگر دوست داری، یک فایل دوم هم می‌سازم برای **نقشه‌ی ارتباطی جداول (ERD ساده متنی)** تا توسعه‌ی بعدی خیلی راحت‌تر بشه.
