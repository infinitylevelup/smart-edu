<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class TechUsageExamsSeeder extends Seeder
{
    public function run(): void
    {
        // 1) ساخت معلم
        $teacher = User::updateOrCreate(
            ['id' => '9111434434'],
            [
                'name' => 'Teacher 9111434434',
                'phone' => '09110000000',
                'email' => null,
                'password' => Hash::make('12345678'),
                'status' => 'active', // ✅ به جای is_active
            ]
        );

        // 2) اتصال نقش teacher
        $teacherRoleId = Role::where('slug', 'teacher')->value('id');
        if ($teacherRoleId) {
            $teacher->roles()->syncWithoutDetaching([$teacherRoleId]);
        }

        // اگر دانش‌آموز هم داری همین الگو
        /*
        $student = User::updateOrCreate(
            ['id' => '...'],
            [
                'name' => 'Student ...',
                'phone' => '09...',
                'password' => Hash::make('12345678'),
                'status' => 'active',
            ]
        );

        $studentRoleId = Role::where('slug', 'student')->value('id');
        if ($studentRoleId) {
            $student->roles()->syncWithoutDetaching([$studentRoleId]);
        }
        */
    }
}
