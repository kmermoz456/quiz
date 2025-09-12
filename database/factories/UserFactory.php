<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'level' => $this->faker->randomElement(['L1', 'L2']),
            'role' => 'student',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // 🔑 mot de passe par défaut
            'remember_token' => \Str::random(10),
        ];
    }
}
