#!/usr/bin/env bash
set -e

DIR="database/migrations"
mkdir -p "$DIR"

# -----------------------------
# بخش 1
# -----------------------------
cat > "$DIR/2025_12_06_103010_create_roles_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
PHP

cat > "$DIR/2025_12_05_103024_create_users_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('name', 150);
            $table->string('email', 190)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('password', 255);
            $table->string('status', 30);
            $table->string('selected_role', 30)->nullable(); // منتقل از add_

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
PHP

cat > "$DIR/2025_12_06_102953_1_create_sections_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('slug', 100)->unique();
            $table->string('name_fa', 150);

            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
PHP

cat > "$DIR/2025_12_06_102953_2_0_create_grades_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('section_id')
                ->constrained('sections')
                ->cascadeOnDelete();

            $table->string('value', 50);
            $table->string('name_fa', 150);
            $table->string('slug', 100)->unique(); // منتقل از add_
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
PHP

cat > "$DIR/2025_12_06_102953_2_1_create_branches_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('section_id')
                ->constrained('sections')
                ->cascadeOnDelete();

            $table->string('slug', 100)->unique();
            $table->string('name_fa', 150);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
PHP

# -----------------------------
# بخش 2
# -----------------------------
cat > "$DIR/2025_12_06_102953_3_create_fields_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('branch_id')
                ->constrained('branches')
                ->cascadeOnDelete();

            $table->string('slug', 100)->unique();
            $table->string('name_fa', 150);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
PHP

cat > "$DIR/2025_12_06_102953_4_create_subfields_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subfields', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('field_id')
                ->constrained('fields')
                ->cascadeOnDelete();

            $table->string('slug', 100)->unique();
            $table->string('name_fa', 150);
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subfields');
    }
};
PHP

cat > "$DIR/2025_12_06_102953_5_0_create_subject_types_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_types', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('slug', 100)->unique();
            $table->string('name_fa', 150);
            $table->decimal('coefficient', 5, 2)->nullable();
            $table->unsignedTinyInteger('weight_percent')->nullable();
            $table->unsignedSmallInteger('default_question_count')->nullable();
            $table->string('color')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_types');
    }
};
PHP

cat > "$DIR/2025_12_06_102953_5_1_create_subjects_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('title_fa', 200);
            $table->string('slug', 255)->unique(); // منتقل از add_
            $table->string('code', 50)->nullable();
            $table->unsignedSmallInteger('hours')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->foreignId('grade_id')->constrained('grades')->restrictOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('field_id')->constrained('fields')->restrictOnDelete();
            $table->foreignId('subfield_id')->nullable()->constrained('subfields')->nullOnDelete();
            $table->foreignId('subject_type_id')->nullable()->constrained('subject_types')->nullOnDelete();

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
PHP

cat > "$DIR/2025_12_06_103023_create_topics_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();

            $table->string('title_fa', 200);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
PHP

# -----------------------------
# بخش 3
# -----------------------------
cat > "$DIR/2025_12_06_102953_create_classrooms_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();

            $table->string('title', 200);
            $table->text('description')->nullable();

            $table->foreignId('section_id')->constrained('sections')->restrictOnDelete();
            $table->foreignId('grade_id')->constrained('grades')->restrictOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('field_id')->constrained('fields')->restrictOnDelete();
            $table->foreignId('subfield_id')->nullable()->constrained('subfields')->nullOnDelete();
            $table->foreignId('subject_type_id')->nullable()->constrained('subject_types')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete(); // منتقل از add_

            $table->enum('classroom_type', ['single', 'comprehensive']);
            $table->string('join_code', 20)->unique();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
PHP

cat > "$DIR/2025_12_06_102954_create_classroom_subject_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classroom_subject', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();

            $table->primary(['classroom_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_subject');
    }
};
PHP

cat > "$DIR/2025_12_06_102955_create_classroom_user_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classroom_user', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->primary(['classroom_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_user');
    }
};
PHP

cat > "$DIR/2025_12_06_103022_create_teacher_subject_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_subject', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();

            $table->primary(['teacher_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_subject');
    }
};
PHP

cat > "$DIR/2025_12_06_184150_create_role_user_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("role_user", function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            $table->foreignId("role_id")->constrained("roles")->cascadeOnDelete();

            $table->primary(["user_id","role_id"]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("role_user");
    }
};
PHP

# -----------------------------
# بخش 4 تا 7 و add نهایی
# (به خاطر طول زیاد، همین الگو رو ادامه بده:
#  هر فایل cat > ... <<'PHP' محتوا PHP )
# -----------------------------

echo "✅ migrations 1-3 ساخته شد. برای بقیه بخش‌ها همین الگو رو ادامه بده."

# -----------------------------
# بخش 4
# -----------------------------
cat > "$DIR/2025_12_06_102956_create_contents_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('type', ['video','pdf','article','link','quiz','other']);
            $table->string('title', 250);
            $table->text('description')->nullable();
            $table->text('file_path')->nullable();
            $table->text('url')->nullable();

            $table->foreignId('section_id')->constrained('sections')->restrictOnDelete();
            $table->foreignId('grade_id')->constrained('grades')->restrictOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('field_id')->constrained('fields')->restrictOnDelete();
            $table->foreignId('subfield_id')->nullable()->constrained('subfields')->nullOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->restrictOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();

            $table->enum('access_level', ['public','classroom','private'])->default('public');

            $table->foreignId('classroom_id')->nullable()
                ->constrained('classrooms')
                ->nullOnDelete();

            // FK بعداً در add_ نهایی
            $table->unsignedBigInteger('ai_generated_by_session_id')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
PHP

cat > "$DIR/2025_12_06_103009_create_questions_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('section_id')->constrained('sections')->restrictOnDelete();
            $table->foreignId('grade_id')->constrained('grades')->restrictOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('field_id')->constrained('fields')->restrictOnDelete();
            $table->foreignId('subfield_id')->nullable()->constrained('subfields')->nullOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->restrictOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();

            $table->unsignedTinyInteger('difficulty')->default(1);

            $table->enum('question_type', [
                'mcq','descriptive','true_false','matching','short_answer','other'
            ])->default('mcq');

            $table->longText('content');
            $table->longText('options')->nullable();
            $table->longText('correct_answer');
            $table->longText('explanation')->nullable();

            // FK بعداً در add_ نهایی
            $table->unsignedBigInteger('ai_generated_by_session_id')->nullable();
            $table->decimal('ai_confidence', 5, 2)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
PHP

cat > "$DIR/2025_12_06_102959_create_exams_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('teacher_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('classroom_id')->nullable()
                ->constrained('classrooms')
                ->nullOnDelete();

            $table->enum('exam_type', ['public','class_single','class_comprehensive'])
                ->default('public');

            $table->string('title', 250);
            $table->text('description')->nullable();

            $table->unsignedSmallInteger('duration_minutes');

            $table->foreignId('section_id')->constrained('sections')->restrictOnDelete();
            $table->foreignId('grade_id')->constrained('grades')->restrictOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('field_id')->constrained('fields')->restrictOnDelete();
            $table->foreignId('subfield_id')->nullable()->constrained('subfields')->nullOnDelete();
            $table->foreignId('subject_type_id')->nullable()->constrained('subject_types')->nullOnDelete();

            $table->unsignedSmallInteger('total_questions')->nullable();
            $table->longText('coefficients')->nullable();

            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();

            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('shuffle_options')->default(false);
            $table->boolean('ai_assisted')->default(false);

            // FK بعداً در add_ نهایی
            $table->unsignedBigInteger('ai_session_id')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_published')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
PHP

cat > "$DIR/2025_12_06_103001_create_exam_questions_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();

            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete();

            $table->unsignedInteger('sort_order')->default(0);

            $table->primary(['exam_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};
PHP

cat > "$DIR/2025_12_06_103000_create_exam_attempts_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();

            $table->foreignId('student_id')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('started_at');
            $table->dateTime('finished_at')->nullable();

            $table->enum('status', ['in_progress','submitted','graded'])
                ->default('in_progress');

            $table->decimal('total_score', 6, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
    }
};
PHP

cat > "$DIR/2025_12_06_102951_create_attempt_answers_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempt_answers', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('attempt_id')
                ->constrained('exam_attempts')
                ->cascadeOnDelete();

            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete();

            $table->longText('answer')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->decimal('score', 6, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempt_answers');
    }
};
PHP

cat > "$DIR/2025_12_06_103002_create_exam_subject_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_subject', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();

            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            $table->unsignedSmallInteger('question_count')->nullable();

            $table->primary(['exam_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_subject');
    }
};
PHP

# -----------------------------
# بخش 5
# -----------------------------
cat > "$DIR/2025_12_06_103005_create_learning_paths_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_paths', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('path_type', ['academic','counseling','mixed'])->default('academic');
            $table->string('title', 250);

            $table->date('start_date');
            $table->date('end_date')->nullable();

            $table->enum('status', ['active','completed','archived'])->default('active');
            $table->enum('generated_by', ['ai','counselor','teacher','system'])->default('system');

            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_paths');
    }
};
PHP

cat > "$DIR/2025_12_06_103006_create_learning_path_steps_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_path_steps', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('learning_path_id')
                ->constrained('learning_paths')
                ->cascadeOnDelete();

            $table->enum('step_type', [
                'topic','subject','content','exercise','exam','counseling_task'
            ]);

            $table->unsignedInteger('order_index')->default(0);

            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->foreignId('grade_id')->nullable()->constrained('grades')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('field_id')->nullable()->constrained('fields')->nullOnDelete();
            $table->foreignId('subfield_id')->nullable()->constrained('subfields')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();

            $table->foreignId('content_id')->nullable()->constrained('contents')->nullOnDelete();
            $table->foreignId('exam_id')->nullable()->constrained('exams')->nullOnDelete();

            // FK بعداً وقتی counseling_tasks ساخته شد
            $table->unsignedBigInteger('counseling_task_id')->nullable();

            $table->unsignedSmallInteger('estimated_minutes')->nullable();
            $table->decimal('required_mastery', 5, 2)->default(0);

            $table->enum('status', ['locked','current','done','skipped'])->default('locked');

            $table->date('due_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_path_steps');
    }
};
PHP

cat > "$DIR/2025_12_06_102945_create_academic_assessments_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_assessments', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->foreignId('grade_id')->nullable()->constrained('grades')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('field_id')->nullable()->constrained('fields')->nullOnDelete();
            $table->foreignId('subfield_id')->nullable()->constrained('subfields')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();

            $table->enum('assessment_type', ['diagnostic','weekly','exam_result','practice']);

            $table->decimal('score_percent', 5, 2)->default(0);
            $table->decimal('mastery_level', 5, 2)->default(0);

            $table->longText('weaknesses')->nullable();
            $table->longText('strengths')->nullable();

            $table->dateTime('taken_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_assessments');
    }
};
PHP

cat > "$DIR/2025_12_06_103008_create_psycho_assessments_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('psycho_assessments', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('assessment_type', ['stress','motivation','focus','anxiety','mood','sleep']);
            $table->decimal('value', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->dateTime('measured_at');
            $table->enum('source', ['self_report','counselor','ai_inferred'])->default('self_report');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('psycho_assessments');
    }
};
PHP

cat > "$DIR/2025_12_06_103007_create_otps_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("otps", function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->id();
            $table->uuid('uuid')->unique();

            $table->string("phone", 15)->index();
            $table->string("code", 6);
            $table->string("token", 255)->nullable();

            $table->enum("type", ["login","register","password_reset"])
                  ->default("login");

            $table->unsignedTinyInteger("attempts")->default(0);
            $table->boolean("verified")->default(false);

            $table->timestamp("expires_at");
            $table->timestamp("verified_at")->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("otps");
    }
};
PHP

# -----------------------------
# بخش 6
# -----------------------------
cat > "$DIR/2025_12_06_102958_create_counselor_profiles_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counselor_profiles', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->unique();

            $table->longText('focus_area')->nullable();

            $table->foreignId('main_section_id')->nullable()
                ->constrained('sections')->nullOnDelete();

            $table->foreignId('main_branch_id')->nullable()
                ->constrained('branches')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counselor_profiles');
    }
};
PHP

cat > "$DIR/2025_12_06_103021_create_teacher_profiles_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->unique();

            $table->text('bio')->nullable();

            $table->foreignId('main_section_id')->nullable()
                ->constrained('sections')->nullOnDelete();

            $table->foreignId('main_branch_id')->nullable()
                ->constrained('branches')->nullOnDelete();

            $table->foreignId('main_field_id')->nullable()
                ->constrained('fields')->nullOnDelete();

            $table->foreignId('main_subfield_id')->nullable()
                ->constrained('subfields')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_profiles');
    }
};
PHP

cat > "$DIR/2025_12_06_103017_create_student_profiles_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->unique();

            $table->foreignId('section_id')->constrained('sections')->restrictOnDelete();
            $table->foreignId('grade_id')->constrained('grades')->restrictOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('field_id')->constrained('fields')->restrictOnDelete();
            $table->foreignId('subfield_id')->nullable()->constrained('subfields')->nullOnDelete();

            $table->string('national_code', 20)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
PHP

cat > "$DIR/2025_12_06_103016_create_student_learning_profiles_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_learning_profiles', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->unique();

            $table->longText('preferred_style')->nullable();
            $table->unsignedTinyInteger('pace_level')->default(1);
            $table->string('study_time_per_day')->nullable();

            $table->json('goals_json')->nullable();
            $table->text('counselor_notes')->nullable();
            $table->text('ai_summary')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_learning_profiles');
    }
};
PHP

cat > "$DIR/2025_12_06_103015_create_student_goals_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_goals', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('goal_type', ['academic','psychological','career']);
            $table->string('title', 250);
            $table->text('description')->nullable();
            $table->date('target_date')->nullable();

            $table->enum('status', ['active','done','paused'])->default('active');
            $table->unsignedTinyInteger('priority')->default(1);

            $table->foreignId('related_subject_id')->nullable()
                ->constrained('subjects')->nullOnDelete();

            $table->foreignId('related_topic_id')->nullable()
                ->constrained('topics')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_goals');
    }
};
PHP

cat > "$DIR/2025_12_06_103014_create_student_daily_logs_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_daily_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->date('log_date');
            $table->unsignedSmallInteger('study_minutes')->default(0);

            $table->unsignedTinyInteger('mood')->nullable();
            $table->unsignedTinyInteger('stress_level')->nullable();
            $table->decimal('sleep_hours', 4, 1)->nullable();

            $table->text('free_text')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_daily_logs');
    }
};
PHP

cat > "$DIR/2025_12_06_103013_create_student_counseling_task_logs_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_counseling_task_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // FK بعداً وقتی counseling_tasks ساخته شد
            $table->unsignedBigInteger('counseling_task_id');

            $table->dateTime('done_at');
            $table->unsignedTinyInteger('self_rating')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_counseling_task_logs');
    }
};
PHP

# -----------------------------
# بخش 7
# -----------------------------
cat > "$DIR/2025_12_06_102950_create_ai_sessions_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_sessions', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('ai_agent_id')->nullable();

            $table->enum('context_type', ['teaching','counseling','qna','exam_help','mixed'])
                ->default('mixed');

            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->foreignId('grade_id')->nullable()->constrained('grades')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('field_id')->nullable()->constrained('fields')->nullOnDelete();
            $table->foreignId('subfield_id')->nullable()->constrained('subfields')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();

            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->nullOnDelete();
            $table->foreignId('exam_id')->nullable()->constrained('exams')->nullOnDelete();

            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_sessions');
    }
};
PHP

cat > "$DIR/2025_12_06_102949_create_ai_messages_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_messages', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('session_id')
                ->constrained('ai_sessions')
                ->cascadeOnDelete();

            $table->enum('sender_type', ['user','ai','system']);
            $table->longText('content');

            $table->unsignedInteger('tokens_in')->nullable();
            $table->unsignedInteger('tokens_out')->nullable();

            $table->json('safety_flags')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_messages');
    }
};
PHP

cat > "$DIR/2025_12_06_102948_create_ai_feedback_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_feedback', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('session_id')
                ->constrained('ai_sessions')
                ->cascadeOnDelete();

            $table->foreignId('message_id')->nullable()
                ->constrained('ai_messages')
                ->nullOnDelete();

            $table->unsignedTinyInteger('rating');
            $table->text('feedback_text')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_feedback');
    }
};
PHP

cat > "$DIR/2025_12_06_102947_create_ai_artifacts_table.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_artifacts', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('session_id')
                ->constrained('ai_sessions')
                ->cascadeOnDelete();

            $table->enum('artifact_type', ['content','question','plan','report','rubric','other']);
            $table->string('title', 250)->nullable();
            $table->longText('body')->nullable();

            $table->string('linked_table', 100)->nullable();
            $table->unsignedBigInteger('linked_id')->nullable();

            $table->enum('status', ['draft','approved','published','rejected'])
                ->default('draft');

            $table->foreignId('reviewer_id')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_artifacts');
    }
};
PHP

# -----------------------------
# add نهایی برای FKهای ai_sessions
# -----------------------------
cat > "$DIR/2025_12_10_030000_add_ai_session_fks_to_tables.php" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->foreign('ai_generated_by_session_id')
                ->references('id')->on('ai_sessions')
                ->nullOnDelete();
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->foreign('ai_generated_by_session_id')
                ->references('id')->on('ai_sessions')
                ->nullOnDelete();
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->foreign('ai_session_id')
                ->references('id')->on('ai_sessions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropForeign(['ai_generated_by_session_id']);
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['ai_generated_by_session_id']);
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['ai_session_id']);
        });
    }
};
PHP

echo "✅ تمام migrations نهایی (بخش 1 تا 7 + add نهایی) ساخته شد."
