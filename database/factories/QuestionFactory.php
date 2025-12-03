<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;
use App\Models\Question;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        $data = [];

        // ✅ فیلد اصلی و اجباری شما
        if (Schema::hasColumn('questions', 'question_text')) {
            $data['question_text'] = $this->faker->sentence(8) . '?';
        }

        // نوع سوال
        if (Schema::hasColumn('questions', 'type')) {
            $data['type'] = 'mcq';
        }

        // امتیاز
        if (Schema::hasColumn('questions', 'score')) {
            $data['score'] = 1;
        }

        // توضیح
        if (Schema::hasColumn('questions', 'explanation')) {
            $data['explanation'] = $this->faker->sentence(10);
        }

        // گزینه‌ها
        if (Schema::hasColumn('questions', 'options')) {
            $data['options'] = ['A', 'B', 'C', 'D'];
        }

        // correct_option برای mcq
        if (Schema::hasColumn('questions', 'correct_option')) {
            $data['correct_option'] = 'A';
        }

        // correct_tf اگر وجود دارد
        if (Schema::hasColumn('questions', 'correct_tf')) {
            $data['correct_tf'] = true;
        }

        // correct_answer اگر وجود دارد
        if (Schema::hasColumn('questions', 'correct_answer')) {
            $data['correct_answer'] = ['answer1'];
        }

        // difficulty
        if (Schema::hasColumn('questions', 'difficulty')) {
            $data['difficulty'] = $this->faker->randomElement(['easy', 'medium', 'hard']);
        }

        // is_active
        if (Schema::hasColumn('questions', 'is_active')) {
            $data['is_active'] = 1;
        }

        return $data;
    }
}
