حتماً. این هم **ERD متنی (نقشه ارتباطی جداول)** بر اساس مگریشن‌های نهایی شما. می‌تونی اینو داخل فایل
`docs/database-erd-final.md` بذاری.

---

# ERD متنی نهایی (Smart-Edu)

**تاریخ:** 2025-12-10
**شرح:** روابط کلیدی بین جداول، نوع کلیدها، و کاردینالیتی‌ها.

---

## 1) هسته کاربران و نقش‌ها

### `"users"`

* PK: `id` (uuid)
* Unique/Required: `uuid`
* فیلدهای مهم: `name, phone, email, password, status, selected_role`

### `"roles"`

* PK: `id` (auto increment یا bigint بسته به نسخه نهایی شما)
* Required: `uuid`
* Unique: `slug`
* فیلدهای مهم: `name, slug, is_active`

### Pivot: `"role_user"`

* PK مرکب: (`user_id`, `role_id`)
* FK:

  * `user_id` → `"users"."id"` (cascade)
  * `role_id` → `"roles"."id"` (cascade)

**رابطه:**

* users ⟷ roles : **Many-to-Many**

---

## 2) ساختار آموزشی (Taxonomy)

### `"sections"`

* PK: `id` (uuid)
* Unique: `slug`
* فیلدها: `name_fa, sort_order, is_active`

### `"grades"`

* PK: `id` (uuid)
* FK: `section_id` → sections.id (cascade)
* فیلدها: `value, name_fa, slug, sort_order, is_active`

### `"branches"`

* PK: `id` (uuid)
* FK: `section_id` → sections.id (cascade)
* فیلدها: `slug, name_fa, sort_order, is_active`

### `"fields"`

* PK: `id` (uuid)
* FK: `branch_id` → branches.id (cascade)
* فیلدها: `slug, name_fa, sort_order, is_active`

### `"subfields"`

* PK: `id` (uuid)
* FK: `field_id` → fields.id (cascade)
* فیلدها: `slug, name_fa, icon, sort_order, is_active`

### `"subject_types"`

* PK: `id` (uuid)
* Unique: `slug`
* فیلدها: `name_fa, coefficient, weight_percent, default_question_count, color, icon, sort_order, is_active`

### `"subjects"`

* PK: `id` (uuid)
* FKها:

  * `grade_id` → grades.id (restrict)
  * `branch_id` → branches.id (restrict)
  * `field_id` → fields.id (restrict)
  * `subfield_id` → subfields.id (nullOnDelete)
  * `subject_type_id` → subject_types.id (nullOnDelete)
* فیلدها: `title_fa, slug, code, hours, description, sort_order, is_active`

**روابط:**

* sections → grades : **1-to-Many**
* sections → branches : **1-to-Many**
* branches → fields : **1-to-Many**
* fields → subfields : **1-to-Many**
* grades/branches/fields/subfields/subject_types → subjects : **1-to-Many** (ترکیبی)

---

## 3) کلاس‌ها و عضویت‌ها

### `"classrooms"`

* PK: `id` (string/uuid طبق نسخه شما)
* FKها:

  * `teacher_id` → users.id (cascade)
  * `section_id` → sections.id (restrict)
  * `grade_id` → grades.id (restrict)
  * `branch_id` → branches.id (restrict)
  * `field_id` → fields.id (restrict)
  * `subfield_id` → subfields.id (null)
  * `subject_type_id` → subject_types.id (null)
  * `subject_id` → subjects.id (null)
* فیلدها: `title, description, classroom_type, join_code, is_active, metadata`

### Pivot: `"classroom_user"`

* PK مرکب: (`classroom_id`, `user_id`)
* FK:

  * classroom_id → classrooms.id (cascade)
  * user_id → users.id (cascade)

### Pivot: `"classroom_subject"`

* PK مرکب: (`classroom_id`, `subject_id`)
* FK:

  * classroom_id → classrooms.id (cascade)
  * subject_id → subjects.id (cascade)

**روابط:**

* users(teacher) → classrooms : **1-to-Many**
* users(student) ⟷ classrooms : **Many-to-Many**
* classrooms ⟷ subjects : **Many-to-Many**

---

## 4) تاپیک‌ها و محتوا

### `"topics"`

* (طبق مگریشن فعلی شما)
* FK:

  * user_id → users.id
  * subject_id → subjects.id  *(بهتر است uuid باشد، ولی فعلاً string است)*
* فیلدها: `title_fa, sort_order, is_active`

### `"contents"`

* FKها:

  * user_id → users.id
  * topic_id → topics.id (nullOnDelete)
  * classroom_id → classrooms.id (null)
  * ai_generated_by_session_id → ai_sessions.id (null)
  * سایر taxonomy ids → sections/grades/.../subjects
* فیلدها: `type, title, description, file_path, url, access_level, is_active`

**روابط:**

* subjects → topics : **1-to-Many**
* topics → contents : **1-to-Many**
* classrooms → contents : **1-to-Many (اختیاری)**

---

## 5) سوالات و آزمون‌ها

### `"questions"`

* PK: `id` (uuid)
* FKها:

  * user_id → users.id
  * topic_id → topics.id (null)
  * ai_generated_by_session_id → ai_sessions.id (null)
  * taxonomy ids → sections/grades/.../subjects
* فیلدها: `difficulty, question_type, content, options, correct_answer, explanation, ai_confidence, is_active`

### `"exams"`

* PK: `id` (uuid)
* FKها:

  * user_id → users.id
  * teacher_id → users.id
  * classroom_id → classrooms.id (null)
  * ai_session_id → ai_sessions.id (null)
  * taxonomy ids → sections/grades/.../subject_types
* فیلدها: `exam_type, title, duration_minutes, total_questions, coefficients, start_at, end_at, shuffle_questions, shuffle_options, ai_assisted, is_active, is_published`

### `"exam_questions"` (pivot)

* FK:

  * exam_id → exams.id (cascade)
  * question_id → questions.id (cascade)
* فیلدها: `sort_order`
* PK پیشنهادی: (`exam_id`, `question_id`)

### `"exam_subject"` (pivot)

* FK:

  * exam_id → exams.id (cascade)
  * subject_id → subjects.id (cascade)
* فیلدها: `question_count`
* PK پیشنهادی: (`exam_id`, `subject_id`)

### `"exam_attempts"`

* FK:

  * user_id → users.id
  * exam_id → exams.id
  * student_id → users.id (در عمل)
* فیلدها: `started_at, finished_at, status, total_score`

### `"attempt_answers"`

* FK:

  * user_id → users.id
  * attempt_id → exam_attempts.id (cascade)
  * question_id → questions.id (cascade)
* فیلدها: `answer, is_correct, score`

**روابط:**

* exams ⟷ questions : **Many-to-Many** از طریق exam_questions
* exams ⟷ subjects : **Many-to-Many** از طریق exam_subject
* exams → exam_attempts : **1-to-Many**
* exam_attempts → attempt_answers : **1-to-Many**
* questions → attempt_answers : **1-to-Many**

---

## 6) Learning Path و گام‌ها

### `"learning_paths"`

* FK:

  * user_id → users.id
  * student_id → users.id (در عمل)
* فیلدها: `path_type, title, start_date, end_date, status, generated_by, metadata`

### `"learning_path_steps"`

* FK:

  * user_id → users.id
  * learning_path_id → learning_paths.id
  * topic_id → topics.id (null)
  * content_id → contents.id (null)
  * exam_id → exams.id (null)
  * counseling_task_id → ??? (string فعلی)
  * taxonomy ids → sections/grades/.../subjects
* فیلدها: `step_type, order_index, estimated_minutes, required_mastery, status, due_date`

**روابط:**

* learning_paths → learning_path_steps : **1-to-Many**

---

## 7) پروفایل‌ها و لاگ‌ها

### `"teacher_profiles"`

* FK: user_id → users.id
* رابطه: users(teacher) → teacher_profiles : **1-to-1**

### `"counselor_profiles"`

* FK: user_id → users.id
* رابطه: users(counselor) → counselor_profiles : **1-to-1**

### `"student_profiles"`

* FK: user_id → users.id
* رابطه: users(student) → student_profiles : **1-to-1**

### `"student_learning_profiles"`

* FK: user_id → users.id
* رابطه: users(student) → student_learning_profiles : **1-to-1**

### `"student_daily_logs"`

* FK: user_id → users.id
* رابطه: users(student) → logs : **1-to-Many**

### `"student_goals"`

* FK:

  * user_id → users.id
  * related_topic_id → topics.id (null)
  * related_subject_id → subjects.id (null)
* رابطه: users(student) → goals : **1-to-Many**

### `"student_counseling_task_logs"`

* FK: user_id → users.id
* رابطه: users(student) → task_logs : **1-to-Many**

---

## 8) سیستم AI

### `"ai_sessions"`

* FK:

  * user_id → users.id
  * classroom_id → classrooms.id (null)
  * exam_id → exams.id (null)
  * topic_id → topics.id (null)
  * subject_id → subjects.id (null)
  * taxonomy ids → sections/grades/...
* فیلدها: `ai_agent_id, context_type, started_at, ended_at, metadata`

### `"ai_messages"`

* FK:

  * user_id → users.id
  * session_id → ai_sessions.id (cascade)
* فیلدها: `sender_type, content, tokens_in, tokens_out, safety_flags`

### `"ai_feedback"`

* FK:

  * user_id → users.id
  * session_id → ai_sessions.id (cascade)
  * message_id → ai_messages.id (null)
* فیلدها: `rating, feedback_text`

### `"ai_artifacts"`

* FK:

  * user_id → users.id
  * session_id → ai_sessions.id (cascade)
* فیلدها: `artifact_type, title, body, linked_table, linked_id, status, reviewer_id`

**روابط:**

* users → ai_sessions : **1-to-Many**
* ai_sessions → ai_messages : **1-to-Many**
* ai_sessions → ai_feedback : **1-to-Many**
* ai_sessions → ai_artifacts : **1-to-Many**

---

## 9) نقاط قابل بهبود در آینده (یادداشت)

* یکدست‌سازی نوع کلیدها در `"topics"` و `"teacher_subject"` (الان string هستند).
* اضافه کردن Primary Key برای pivotهایی که PK ندارند (teacher_subject, exam_questions, exam_subject).
* استفاده از enum/lookup برای counseling_task_id در learning_path_steps و task_logs اگر بعداً جدولش ساخته شد.

---

اگر خواستی، قدم بعدی می‌تونم:

1. همین ERD رو به شکل **نمودار تصویری** برایت توضیح بدم (متنی + راهنمای رسم در dbdiagram یا draw.io).
2. یا یک چک‌لیست «استاندارد ساخت FK جدید» برای تیم/آینده بنویسم.
