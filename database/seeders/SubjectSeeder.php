<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Unit;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $units = Unit::all();

        if ($units->isEmpty()) {
            $this->command->warn("⚠️ Aucune UE trouvée. Lance UnitSeeder d'abord.");
            return;
        }

        foreach ($units as $unit) {
            for ($i = 1; $i <= 3; $i++) {
                Subject::create([
                    'unit_id'    => $unit->id,
                    'title'      => "Sujet $i de {$unit->code}",
                    'description'=> "Description du sujet $i pour l’unité {$unit->name}",
                    'level'      => $unit->level,
                'user_id' => 1, // lie à un user au hasard

                ]);
            }
        }
    }
}
