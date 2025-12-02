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
