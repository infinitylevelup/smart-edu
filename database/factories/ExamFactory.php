<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;

use App\Models\Exam;
use App\Models\User;
use App\Models\Classroom;

class ExamFactory extends Factory
{
    protected $model = Exam::class;

    public function definition(): array
    {
        $data = [];

        // ---------- پایه‌ای‌ترین فیلدها ----------
        if (Schema::hasColumn('exams', 'title')) {
            $data['title'] = 'آزمون ' . $this->faker->word();
        }

        if (Schema::hasColumn('exams', 'name')) {
            $data['name'] = 'Exam ' . $this->faker->word();
        }

        if (Schema::hasColumn('exams', 'description')) {
            $data['description'] = $this->faker->sentence(8);
        }

        if (Schema::hasColumn('exams', 'duration')) {
            $data['duration'] = $this->faker->numberBetween(10, 60);
        }

if (Schema::hasColumn('exams', 'level')) {
    $data['level'] = $this->faker->randomElement([1, 2, 3]);
}


        // scope رو seed اصلی تعیین می‌کنه (free / classroom)
        if (Schema::hasColumn('exams', 'scope')) {
            $data['scope'] = 'free';
        }

        // ---------- وضعیت انتشار ----------
        if (Schema::hasColumn('exams', 'is_published')) {
            $data['is_published'] = 1;
        }

        if (Schema::hasColumn('exams', 'is_active')) {
            $data['is_active'] = 1;
        }

        // ---------- زمان شروع/پایان اگر داری ----------
        if (Schema::hasColumn('exams', 'start_at')) {
            $data['start_at'] = now()->subDays(rand(0, 3));
        }
        if (Schema::hasColumn('exams', 'end_at')) {
            $data['end_at'] = now()->addDays(rand(3, 10));
        }

        // ---------- اگر teacher_id اجباری باشه ----------
        if (Schema::hasColumn('exams', 'teacher_id')) {
            $teacher = User::where('role', 'teacher')->inRandomOrder()->first()
                ?? User::factory()->teacher()->create();

            $data['teacher_id'] = $teacher->id;
        }

        // ---------- اگر subject_id اجباری باشه ولی مدل Subject نداری ----------
        // فعلاً فقط اگر ستونش nullable نباشه ممکنه گیر بده؛
        // اینجا اگر ستون هست مقدار تصادفی می‌ذاریم (بعداً با Subject واقعی اصلاح می‌کنیم)
if (Schema::hasColumn('exams', 'subject_id')) {
    // اگر هیچ subject نداریم، null بذار تا FK نخوره
    $firstSubjectId = \App\Models\Subject::query()->value('id');
    $data['subject_id'] = $firstSubjectId; // null اگر خالی باشه
}

        // ---------- classroom_id اگه ستونش هست ولی seed تعیین می‌کنه ----------
        if (Schema::hasColumn('exams', 'classroom_id')) {
            $data['classroom_id'] = null;
        }

        return $data;
    }
}
