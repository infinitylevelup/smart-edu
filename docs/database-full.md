# Smart-Edu Database Full Documentation

Generated automatically by `AllDatabaseDoc.sh` on: Wed Dec  3 04:51:42 EAST 2025

---

## 1) Migrations

### Migration Files List

- 2025_11_27_123115_create_users_table.php
- 2025_11_27_123116_create_otps_table.php
- 2025_11_27_123813_create_cache_table.php
- 2025_11_27_163118_create_sessions_table.php
- 2025_11_27_171139_create_exams_table.php
- 2025_11_27_171140_create_questions_table.php
- 2025_11_27_171141_create_attempts_table.php
- 2025_11_28_130019_add_type_and_payload_to_questions_table.php
- 2025_11_28_171030_create_classrooms_table.php
- 2025_11_28_171119_create_classroom_student_table.php
- 2025_11_28_172851_add_classroom_id_to_exams_table.php
- 2025_11_29_071751_update_questions_for_multi_type.php
- 2025_11_29_075345_create_attempt_answers_table.php
- 2025_11_29_080759_update_users_add_core_fields.php
- 2025_11_29_080823_create_student_profiles_table.php
- 2025_11_29_080903_create_teacher_profiles_table.php
- 2025_11_29_151943_create_subjects_table.php
- 2025_11_29_152045_add_subject_id_to_exams_table.php
- 2025_11_29_154352_update_exam_level_enum.php
- 2025_11_29_161355_add_start_at_to_exams_table.php
- 2025_11_29_183041_add_score_to_attempt_answers_table.php
- 2025_11_29_183226_add_score_and_percent_to_attempts_table.php
- 2025_11_29_183623_update_attempts_table_add_grading_fields.php
- 2025_11_30_183624_add_scope_to_exams_table.php
- 2025_11_30_185758_add_scope_to_exams_table.php
- 2025_11_30_185759_add_is_active_to_exams_table.php
- 2025_11_30_191000_patch_questions_table_for_multi_types.php
- 2025_12_03_001912_create_roles_table.php
- 2025_12_03_001940_create_role_user_table.php

### Tables Extracted From Migrations (best-effort)

- attempt_answers
- attempts
- cache
- cache_locks
- classroom_student
- classrooms
- exams
- otps
- questions
- role_user
- roles
- sessions
- student_profiles
- subjects
- teacher_profiles
- users

### Schema::table Alterations

- attempt_answers
- attempts
- exams
- questions
- users
/c/xampp/htdocs/smart-edu/database/migrations/2025_11_30_191000_patch_questions_table_for_multi_types.php:61:        // (Separate Schema::table because of change())

---

## 2) Eloquent Models

### Models List

- app/Models/Attempt.php
- app/Models/AttemptAnswer.php
- app/Models/Classroom.php
- app/Models/Exam.php
- app/Models/Otp.php
- app/Models/Question.php
- app/Models/Role.php
- app/Models/StudentProfile.php
- app/Models/Subject.php
- app/Models/TeacherProfile.php
- app/Models/User.php

### Relations Detected (best-effort)

#### Attempt.php

```php
62:        return $this->belongsTo(Exam::class);
67:        return $this->belongsTo(User::class, 'student_id');
72:        return $this->hasMany(AttemptAnswer::class);
```

#### AttemptAnswer.php

```php
35:        return $this->belongsTo(Attempt::class);
40:        return $this->belongsTo(Question::class);
```

#### Classroom.php

```php
22:        return $this->belongsToMany(
```

#### Exam.php

```php
37:        return $this->belongsTo(User::class, 'teacher_id');
42:        return $this->hasMany(Question::class);
47:        return $this->hasMany(Attempt::class);
52:        return $this->belongsTo(Classroom::class);
57:        return $this->belongsTo(\App\Models\Subject::class);
```

#### Question.php

```php
47:        return $this->belongsTo(Exam::class);
62:        return $this->belongsTo(Subject::class);
```

#### Role.php

```php
16:        return $this->belongsToMany(User::class, 'role_user');
```

#### StudentProfile.php

```php
15:        return $this->belongsTo(User::class);
```

#### Subject.php

```php
15:        return $this->belongsTo(User::class, 'teacher_id');
20:        return $this->hasMany(Exam::class);
```

#### TeacherProfile.php

```php
14:        return $this->belongsTo(User::class);
```

#### User.php

```php
63:        return $this->hasMany(Classroom::class, 'teacher_id');
72:        return $this->belongsToMany(
85:        return $this->hasMany(
97:    //     return $this->belongsToMany(Classroom::class, 'classroom_user')
116:    return $this->hasOne(\App\Models\StudentProfile::class, 'user_id');
122:    return $this->belongsToMany(Role::class, 'role_user');
```

---

## 3) Seeders

### Seeders List

- database/seeders/DatabaseSeeder.php
- database/seeders/FakeStudentSeeder.php
- database/seeders/RoleSeeder.php
- database/seeders/SubjectSeeder.php

### Seeder run() Summary (best-effort)

#### DatabaseSeeder.php

```php
    public function run(): void
    {
        // 1) roles first
       // $this->call([
      ///      RoleSeeder::class,
      //  ]);

        // 2) then users
        User::factory()->count(10)->student()->create();
        User::factory()->count(5)->teacher()->create();
    }
```

#### FakeStudentSeeder.php

```php
    public function run(): void
    {
      $this->call(SubjectSeeder::class);

// ---------------------------
// 1) Build user payload ONLY with existing columns
// ---------------------------
$userData = [
    'phone' => '9391414434',
    'role'  => 'student',
];

// optional columns if they exist
if (Schema::hasColumn('users', 'password')) {
    $userData['password'] = Hash::make('password');
}
```

#### RoleSeeder.php

```php
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'title' => 'مدیر سیستم'],
            ['name' => 'teacher', 'title' => 'معلم'],
            ['name' => 'student', 'title' => 'دانش‌آموز'],
            ['name' => 'counselor', 'title' => 'مشاور'],
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r['name']], $r);
        }
```

#### SubjectSeeder.php

```php
    public function run(): void
    {
        $items = [
            ['title' => 'ریاضی'],
            ['title' => 'فیزیک'],
            ['title' => 'شیمی'],
            ['title' => 'زیست'],
            ['title' => 'ادبیات'],
        ];

        foreach ($items as $it) {
            Subject::firstOrCreate($it);
        }
```

---

## 4) Factories

### Factories List

- database/factories/ClassroomFactory.php
- database/factories/ExamFactory.php
- database/factories/QuestionFactory.php
- database/factories/UserFactory.php

### Factory definition() Summary (best-effort)

#### ClassroomFactory.php

```php
public function definition(): array
{
    $teacher = User::where('role', 'teacher')->inRandomOrder()->first()
        ?? User::factory()->teacher()->create();

    $data = [];

    if (Schema::hasColumn('classrooms', 'teacher_id')) {
        $data['teacher_id'] = $teacher->id;
    }
```

#### ExamFactory.php

```php
    public function definition(): array
    {
        $data = [];

        // ---------- پایه‌ای‌ترین فیلدها ----------
        if (Schema::hasColumn('exams', 'title')) {
            $data['title'] = 'آزمون ' . $this->faker->word();
        }
```

#### QuestionFactory.php

```php
    public function definition(): array
    {
        $data = [];

        // ✅ فیلد اصلی و اجباری شما
        if (Schema::hasColumn('questions', 'question_text')) {
            $data['question_text'] = $this->faker->sentence(8) . '?';
        }
```

#### UserFactory.php

```php
    public function definition(): array
    {
        return [
            // شماره موبایل یکتا
            'phone' => $this->faker->unique()->numerify('09#########'),

            // نقش
            'role'  => $this->faker->randomElement(['student', 'teacher']),

            // زمان انتخاب نقش (اگر ستونش را داری)
            'role_selected_at' => now(),

            // فعال بودن کاربر (اگر ستونش را داری)
            'is_active' => true,
            // زمان آخرین ورود (اگر ستونش را داری)
           'last_login_at' => null,

        ];
    }
```

---

## 5) ERD Hints (From Migrations)

Foreign keys detected automatically (best-effort):

- database/migrations/2025_11_27_163118_create_sessions_table.php:16:            $table->foreignId('user_id')->nullable()->index();
- database/migrations/2025_11_27_171139_create_exams_table.php:14:            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
- database/migrations/2025_11_27_171140_create_questions_table.php:13:            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
- database/migrations/2025_11_27_171141_create_attempts_table.php:20:            $table->foreignId('exam_id')
- database/migrations/2025_11_27_171141_create_attempts_table.php:25:            $table->foreignId('student_id')
- database/migrations/2025_11_28_171030_create_classrooms_table.php:12:            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
- database/migrations/2025_11_28_171119_create_classroom_student_table.php:11:            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
- database/migrations/2025_11_28_171119_create_classroom_student_table.php:12:            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
- database/migrations/2025_11_28_172851_add_classroom_id_to_exams_table.php:10:            $table->foreignId('classroom_id')
- database/migrations/2025_11_29_075345_create_attempt_answers_table.php:19:            $table->foreignId('attempt_id')
- database/migrations/2025_11_29_075345_create_attempt_answers_table.php:24:            $table->foreignId('question_id')
- database/migrations/2025_11_29_075345_create_attempt_answers_table.php:50:            $table->foreignId('graded_by')
- database/migrations/2025_11_29_080823_create_student_profiles_table.php:13:            $table->foreignId('user_id')
- database/migrations/2025_11_29_080903_create_teacher_profiles_table.php:13:            $table->foreignId('user_id')
- database/migrations/2025_11_29_151943_create_subjects_table.php:14:            $table->foreignId('teacher_id')
- database/migrations/2025_11_29_152045_add_subject_id_to_exams_table.php:15:        $table->foreignId('subject_id')
- database/migrations/2025_11_30_183624_add_scope_to_exams_table.php:22:            $table->foreignId('classroom_id')->nullable()->change();
- database/migrations/2025_12_03_001940_create_role_user_table.php:13:            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
- database/migrations/2025_12_03_001940_create_role_user_table.php:14:            $table->foreignId('role_id')->constrained()->cascadeOnDelete();

