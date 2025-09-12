<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ✅ Un admin par défaut (id=1)
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'phone' => '0101010101',
                'level' => null,
                'role' => 'admin',
                'password' => Hash::make('password'), // 🔑 change si tu veux
                'email_verified_at' => now(),
            ]
        );

        // ✅ 50 étudiants aléatoires
        User::factory()->count(50)->create();
    }
}
