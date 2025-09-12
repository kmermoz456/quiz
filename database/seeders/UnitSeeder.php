<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;
use App\Models\User;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $levels = ['L1', 'L2'];

        // Récupère tous les utilisateurs (au besoin filtre les admins)
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn("⚠️ Aucun utilisateur trouvé. Lance d'abord UserSeeder.");
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            Unit::create([
                'code'    => 'UE' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'name'    => 'Unité d’Enseignement ' . $i,
                'level'   => $levels[array_rand($levels)],
                'user_id' => 1, // lie à un user au hasard
            ]);
        }
    }
}
