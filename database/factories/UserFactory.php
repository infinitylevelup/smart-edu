<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory برای ساخت کاربران تستی مطابق ساختار واقعی users
 * - OTP ها در جدول otps هستند، پس otp_code / otp_expires_at در users نداریم
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        $data = [];

        if (\Schema::hasColumn('users','phone')) {
            $data['phone'] = $this->faker->unique()->numerify('09#########');
        }

        if (\Schema::hasColumn('users','name')) {
            $data['name'] = $this->faker->name();
        }

        if (\Schema::hasColumn('users','full_name')) {
            $data['full_name'] = $this->faker->name();
        }

        if (\Schema::hasColumn('users','role')) {
            $data['role'] = 'student';
        }

        if (\Schema::hasColumn('users','is_active')) {
            $data['is_active'] = 1;
        }

        return $data;
    }


    public function student(): static
    {
        return $this->state(fn () => [
            'role' => 'student',
        ]);
    }

    public function teacher(): static
    {
        return $this->state(fn () => [
            'role' => 'teacher',
        ]);
    }

    public function noRoleSelected(): static
    {
        return $this->state(fn () => [
            'role' => null,
            'role_selected_at' => null,
        ]);
    }
}
