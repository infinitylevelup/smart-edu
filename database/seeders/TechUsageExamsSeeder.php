<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

use App\Models\User;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Classroom;

class TechUsageExamsSeeder extends Seeder
{
    public function run(): void
    {
        // ----------------------------------------------------
        // 0) Teacher (ID ثابت)
        // ----------------------------------------------------
        $teacherId = 9111434434;

        $teacher = User::query()->find($teacherId);

        if (!$teacher) {
            // فقط ستون‌های واقعی users را ست می‌کنیم
            $data = [
                'id'        => $teacherId,
                'role'      => 'teacher',
                'phone'     => '09110000000',
                'is_active' => 1,
            ];

            if (Schema::hasColumn('users', 'name')) {
                $data['name'] = 'Teacher 9111434434';
            }
            if (Schema::hasColumn('users', 'full_name')) {
                $data['full_name'] = 'Teacher 9111434434';
            }
            if (Schema::hasColumn('users', 'role_selected_at')) {
                $data['role_selected_at'] = now();
            }

            // از factory استفاده می‌کنیم ولی با داده‌های امن
            $teacher = User::factory()->create($data);
        }

        // ----------------------------------------------------
        // 1) Subject
        // ----------------------------------------------------
        $subject = Subject::query()->firstOrCreate(
            [
                'teacher_id'  => $teacher->id,
                'title'       => 'کاربرد فناوری‌های نوین',
                'grade_level' => 'یازدهم فنی - کامپیوتر',
            ],
            [
                'description' => 'آزمون‌های پودمانی درس کاربرد فناوری‌های نوین',
            ]
        );

        // ----------------------------------------------------
        // 2) Classroom برای آزمون‌های کلاسی
        // ----------------------------------------------------
        $classroom = Classroom::query()
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$classroom) {
            $classroom = Classroom::create([
                'teacher_id' => $teacher->id,
                'title'      => 'کلاس فناوری‌های نوین یازدهم',
                'subject'    => $subject->title,
                'grade'      => '11',
                'description'=> 'کلاس پیش‌فرض برای آزمون‌های کلاسی',
                'join_code'  => strtoupper(Str::random(8)),
                'is_active'  => 1,
            ]);
        }

        // ----------------------------------------------------
        // 3) بانک پودمانی سوالات
        // ----------------------------------------------------
        $bankByPoodman = $this->bankByPoodman();

        // هر پودمان ~۱۰ آزمون می‌گیرد
        $poodmanKeys  = array_keys($bankByPoodman);
        $poodmanCycle = $this->cycle($poodmanKeys, 50);

        // ----------------------------------------------------
        // 4) ساخت ۵۰ آزمون (۲۵ free + ۲۵ classroom)
        // ----------------------------------------------------
        for ($i = 1; $i <= 50; $i++) {

            $isFree = $i <= 25;
            $poodman = $poodmanCycle[$i - 1];

            $exam = Exam::create([
                'teacher_id'   => $teacher->id,
                'classroom_id' => $isFree ? null : $classroom->id,
                'subject_id'   => $subject->id,

                'title'        => $isFree
                    ? "آزمون عمومی ({$poodman}) #{$i}"
                    : "آزمون کلاسی ({$poodman}) #".($i - 25),

                'description'  => $isFree
                    ? "آزمون آزاد پودمان {$poodman}"
                    : "آزمون کلاسی پودمان {$poodman} توسط معلم",

                'level'        => $isFree
                    ? $this->randLevel(['easy','average'])
                    : $this->randLevel(['average','hard','tough']),

                'duration'     => rand(15, 30),
                'start_at'     => now()->subDays(rand(0, 10)),
                'is_published' => true,
                'is_active'    => true,
                'scope'        => $isFree ? 'free' : 'classroom',
            ]);

            $pool = $bankByPoodman[$poodman];

            $qCount = rand(10, 20);
            for ($q = 0; $q < $qCount; $q++) {
                $item = $pool[array_rand($pool)];

                if ($item['type'] === 'mcq') {
                    $this->createMcq($exam->id, $item);
                } else {
                    $this->createTrueFalse($exam->id, $item);
                }
            }
        }
    }

    // ======================================================
    // ✅ normalize difficulty تا average وارد DB نشه
    // ======================================================
    private function normalizeDifficulty(?string $d): string
    {
        $d = strtolower(trim($d ?? ''));

        return match ($d) {
            'easy' => 'easy',
            'hard' => 'hard',
            'average', 'medium', 'normal', 'mid' => 'medium',
            'tough' => 'hard',
            default => 'medium',
        };
    }

    // ======================================================
    // بانک سوالات پودمانی
    // ======================================================
    private function bankByPoodman(): array
    {
        return [
            // ---------------- پودمان 1: سواد فناورانه ----------------
            'پودمان۱-سوادفناورانه' => [
                [
                    'type' => 'mcq',
                    'text' => 'سواد فناورانه بیشتر به چه معناست؟',
                    'options' => [
                        'a' => 'حفظ مفاهیم علمی',
                        'b' => 'استفاده آگاهانه و مسئولانه از فناوری',
                        'c' => 'تعمیر سخت‌افزار',
                        'd' => 'برنامه‌نویسی حرفه‌ای',
                    ],
                    'correct' => 'b',
                    'difficulty' => 'easy',
                ],
                [
                    'type' => 'true_false',
                    'text' => 'رعایت حریم خصوصی در فضای دیجیتال بخشی از سواد فناورانه است.',
                    'correct_tf' => true,
                    'difficulty' => 'easy',
                ],
                ...$this->autoFillMcqTf('سواد فناورانه', 12, 8),
            ],

            // ---------------- پودمان 2: فاوا ----------------
            'پودمان۲-فاوا' => [
                [
                    'type' => 'mcq',
                    'text' => 'فاوا ترکیبی از کدام دو حوزه است؟',
                    'options' => [
                        'a' => 'زیست و نانو',
                        'b' => 'اطلاعات و ارتباطات',
                        'c' => 'انرژی و مواد',
                        'd' => 'ریاضی و فیزیک',
                    ],
                    'correct' => 'b',
                ],
                [
                    'type' => 'true_false',
                    'text' => 'فیشینگ یک روش کلاهبرداری اینترنتی است.',
                    'correct_tf' => true,
                ],
                ...$this->autoFillMcqTf('فاوا', 12, 8),
            ],

            // ---------------- پودمان 3: فناوری‌های همگرا ----------------
            'پودمان۳-فناوریهایهمگرا' => [
                [
                    'type' => 'mcq',
                    'text' => 'فناوری‌های همگرا یعنی:',
                    'options' => [
                        'a' => 'جدا شدن علوم',
                        'b' => 'ترکیب چند فناوری برای حل مسائل',
                        'c' => 'کاهش پیشرفت فناوری',
                        'd' => 'محدود شدن کاربردها',
                    ],
                    'correct' => 'b',
                ],
                [
                    'type' => 'true_false',
                    'text' => 'نانو فناوری در ابعاد بسیار کوچک باعث تغییر خواص مواد می‌شود.',
                    'correct_tf' => true,
                ],
                ...$this->autoFillMcqTf('فناوری‌های همگرا/نانو/زیست‌فناوری', 12, 8),
            ],

            // ---------------- پودمان 4: انرژی‌های تجدیدپذیر ----------------
            'پودمان۴-انرژیهای‌تجدیدپذیر' => [
                [
                    'type' => 'mcq',
                    'text' => 'کدام مورد جزو انرژی‌های تجدیدپذیر است؟',
                    'options' => [
                        'a' => 'گاز طبیعی',
                        'b' => 'خورشیدی',
                        'c' => 'بنزین',
                        'd' => 'زغال‌سنگ',
                    ],
                    'correct' => 'b',
                ],
                [
                    'type' => 'true_false',
                    'text' => 'انرژی باد نمونه‌ای از انرژی تجدیدپذیر است.',
                    'correct_tf' => true,
                ],
                ...$this->autoFillMcqTf('انرژی‌های تجدیدپذیر', 12, 8),
            ],

            // ---------------- پودمان 5: از ایده تا محصول ----------------
            'پودمان۵-ایدهتامحصول' => [
                [
                    'type' => 'mcq',
                    'text' => 'Prototype (پایلوت) یعنی:',
                    'options' => [
                        'a' => 'تبلیغ محصول نهایی',
                        'b' => 'نمونه اولیه برای تست',
                        'c' => 'ثبت اختراع',
                        'd' => 'فروش عمده',
                    ],
                    'correct' => 'b',
                ],
                [
                    'type' => 'true_false',
                    'text' => 'غربالگری ایده‌ها قبل از ساخت نمونه اولیه انجام می‌شود.',
                    'correct_tf' => true,
                ],
                ...$this->autoFillMcqTf('ایده تا محصول/ثبت اختراع/تجاری‌سازی', 12, 8),
            ],
        ];
    }

    // ======================================================
    // تولید سوالات تالیفی برای تکمیل هر پودمان
    // ======================================================
    private function autoFillMcqTf(string $topic, int $mcqCount, int $tfCount): array
    {
        $out = [];

        for ($i = 0; $i < $mcqCount; $i++) {
            $out[] = [
                'type' => 'mcq',
                'text' => "({$topic}) سؤال تستی تالیفی شماره ".($i + 1),
                'options' => [
                    'a' => 'گزینه الف',
                    'b' => 'گزینه ب',
                    'c' => 'گزینه ج',
                    'd' => 'گزینه د',
                ],
                'correct' => ['a','b','c','d'][rand(0,3)],
                'difficulty' => ['easy','medium','hard'][rand(0,2)],
                'explanation' => "توضیح کوتاه برای {$topic}",
            ];
        }

        for ($i = 0; $i < $tfCount; $i++) {
            $out[] = [
                'type' => 'true_false',
                'text' => "({$topic}) گزاره صحیح/غلط تالیفی شماره ".($i + 1),
                'correct_tf' => (bool)rand(0,1),
                'difficulty' => ['easy','medium','hard'][rand(0,2)],
                'explanation' => "توضیح کوتاه برای {$topic}",
            ];
        }

        return $out;
    }

    // ======================================================
    // Helpers
    // ======================================================
    private function cycle(array $items, int $n): array
    {
        $out = [];
        $i = 0;
        while (count($out) < $n) {
            $out[] = $items[$i % count($items)];
            $i++;
        }
        return $out;
    }

    private function randLevel(array $levels): string
    {
        return $levels[array_rand($levels)];
    }

    private function createMcq(int $examId, array $item): void
    {
        Question::create([
            'exam_id'       => $examId,
            'question_text' => $item['text'],
            'type'          => 'mcq',
            'score'         => 1,
            'difficulty'    => $this->normalizeDifficulty($item['difficulty'] ?? null),
            'explanation'   => $item['explanation'] ?? null,

            'options'       => [
                'a' => $item['options']['a'],
                'b' => $item['options']['b'],
                'c' => $item['options']['c'],
                'd' => $item['options']['d'],
            ],
            'correct_answer'=> [$item['correct']],

            // legacy
            'option_a'      => $item['options']['a'],
            'option_b'      => $item['options']['b'],
            'option_c'      => $item['options']['c'],
            'option_d'      => $item['options']['d'],
            'correct_option'=> $item['correct'],

            'is_active'     => 1,
        ]);
    }

    private function createTrueFalse(int $examId, array $item): void
    {
        Question::create([
            'exam_id'       => $examId,
            'question_text' => $item['text'],
            'type'          => 'true_false',
            'score'         => 1,
            'difficulty'    => $this->normalizeDifficulty($item['difficulty'] ?? null),
            'explanation'   => $item['explanation'] ?? null,
            'correct_tf'    => (bool)$item['correct_tf'],

            // legacy null
            'option_a'      => null,
            'option_b'      => null,
            'option_c'      => null,
            'option_d'      => null,
            'correct_option'=> null,

            'is_active'     => 1,
        ]);
    }
}
