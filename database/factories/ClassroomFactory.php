<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;
use App\Models\Classroom;
use App\Models\User;

class ClassroomFactory extends Factory
{
    protected $model = Classroom::class;

public function definition(): array
{
    $teacher = User::where('role', 'teacher')->inRandomOrder()->first()
        ?? User::factory()->teacher()->create();

    $data = [];

    if (Schema::hasColumn('classrooms', 'teacher_id')) {
        $data['teacher_id'] = $teacher->id;
    }

    if (Schema::hasColumn('classrooms', 'title')) {
        $data['title'] = 'کلاس ' . $this->faker->word();
    }

    if (Schema::hasColumn('classrooms', 'name')) {
        $data['name'] = 'Class ' . $this->faker->word();
    }

    if (Schema::hasColumn('classrooms', 'code')) {
        $data['code'] = strtoupper($this->faker->bothify('CLS###'));
    }

    // ✅ این خط جدید
    if (Schema::hasColumn('classrooms', 'join_code')) {
        $data['join_code'] = strtoupper($this->faker->bothify('JOIN###'));
    }

    if (Schema::hasColumn('classrooms', 'grade')) {
        $data['grade'] = $this->faker->numberBetween(7, 12);
    }

    return $data;
}

}
