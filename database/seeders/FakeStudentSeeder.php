<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class FakeStudentSeeder extends Seeder
{
    public function run(): void
    {
        // نقش دانش‌آموز
        $studentRoleId = Role::where('slug', 'student')->value('id');

        // اگر نقش وجود نداشت، Seeder رو خراب نکن
        if (! $studentRoleId) {
            $this->command?->warn("Role [student] not found. Run RoleSeeder first.");
            return;
        }

        // ساخت چند دانش‌آموز فیک
        for ($i = 1; $i <= 20; $i++) {

            $user = User::updateOrCreate(
                ['phone' => '09' . rand(100000000, 999999999)],
                [
                    'name'     => "Fake Student {$i}",
                    'email'    => null,
                    'password' => Hash::make('12345678'),
                    'status'   => 'active',
                ]
            );

            // اتصال نقش در pivot
            $user->roles()->syncWithoutDetaching([$studentRoleId]);
        }
    }
}
