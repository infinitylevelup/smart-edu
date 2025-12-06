<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'id'       => (string) fake()->unique()->numerify('##########'),
            'name'     => fake()->name(),
            'email'    => null,
            'phone'    => fake()->unique()->numerify('09#########'),
            'password' => Hash::make('12345678'),
            'status'   => 'active',
        ];
    }

    // اگر state لازم داری، فقط فیلدهای واقعی users رو تغییر بده
    public function inactive()
    {
        return $this->state(fn () => [
            'status' => 'inactive',
        ]);
    }
    public function student()
{
    return $this->state(fn () => [
        // فقط فیلدهای واقعی جدول users
        'status' => 'active',
    ]);
}

public function teacher()
{
    return $this->state(fn () => [
        'status' => 'active',
    ]);
}

}
