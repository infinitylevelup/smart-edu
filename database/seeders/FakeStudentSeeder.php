<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

use App\Models\User;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Attempt;
use App\Models\AttemptAnswer;

class FakeStudentSeeder extends Seeder
{
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
if (Schema::hasColumn('users', 'pin')) {
    $userData['pin'] = Hash::make('1234');
}
if (Schema::hasColumn('users', 'name')) {
    $userData['name'] = 'Fake Student 9391414434';
}
if (Schema::hasColumn('users', 'full_name')) {
    $userData['full_name'] = 'Fake Student 9391414434';
}
if (Schema::hasColumn('users', 'email')) {
    $userData['email'] = 'fake9391414434@example.com';
}
if (Schema::hasColumn('users', 'username')) {
    $userData['username'] = 'fake_9391414434';
}

$student = User::updateOrCreate(
    ['phone' => '9391414434'],
    $userData
);

        // ---------------------------
        // 2) Create classrooms (need columns from factory)
        // ---------------------------
        $classrooms = Classroom::factory()->count(2)->create();

        // attach student to first classroom
        $student->classrooms()->syncWithoutDetaching($classrooms->first()->id);

        // ---------------------------
        // 3) Public/free exams
        // ---------------------------
        $publicExams = Exam::factory()
            ->count(3)
            ->create([
                'scope' => 'free',
                'is_published' => 1,
                'is_active' => 1,
                'classroom_id' => null,
            ]);

        // ---------------------------
        // 4) Classroom exams
        // ---------------------------
        $classroomExams = Exam::factory()
            ->count(3)
            ->create([
                'scope' => 'classroom',
                'is_published' => 1,
                'is_active' => 1,
                'classroom_id' => $classrooms->first()->id,
            ]);

        $allExams = $publicExams->concat($classroomExams);

        // ---------------------------
        // 5) Questions + Attempts + AttemptAnswers
        // ---------------------------
        foreach ($allExams as $exam) {

            $questions = Question::factory()
                ->count(5)
                ->create([
                    'exam_id' => $exam->id,
                    'type' => 'mcq',
                    'score' => 1,
                ]);

            $attempt = Attempt::create([
                'exam_id' => $exam->id,
                'student_id' => $student->id,
                'answers' => [],
                'score' => 0,
                'percent' => 0,
                'started_at' => now()->subDays(rand(1,5)),
                'submitted_at' => now()->subDays(rand(0,2)),
                'finished_at' => now()->subDays(rand(0,2)),
                'status' => 'submitted',
                'score_total' => 5,
                'score_obtained' => 0,
            ]);

            $scoreObtained = 0;

            foreach ($questions as $q) {
                $isCorrect = (bool) rand(0,1);
                $awarded = $isCorrect ? 1 : 0;
                $scoreObtained += $awarded;

                AttemptAnswer::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $q->id,
                    'answer' => $isCorrect ? ($q->correct_option ?? 'A') : 'wrong',
                    'is_correct' => $isCorrect ? 1 : 0,
                    'score_awarded' => $awarded,
                ]);
            }

            $percent = round(($scoreObtained / 5) * 100, 2);

            $attempt->update([
                'score_obtained' => $scoreObtained,
                'score' => $scoreObtained,
                'percent' => $percent,
            ]);
        }

        $this->command?->info("✅ Fake student seeded: phone=9391414434");
    }
}
