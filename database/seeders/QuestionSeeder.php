<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Subject;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = Subject::all();

        if ($subjects->isEmpty()) {
            $this->command->warn("⚠️ Aucun sujet trouvé. Lance SubjectSeeder d'abord.");
            return;
        }

        foreach ($subjects as $subject) {
            for ($i = 1; $i <= 5; $i++) {
                Question::create([
                    'subject_id' => $subject->id,
                    'statement'  => "Énoncé de la question $i pour le sujet {$subject->title}",
                    'type'       => ['single','multiple','true_false'][array_rand([0,1,2])],
                    'points'     => rand(1, 5),
                    'position'   => $i,
                'user_id' => 1, // lie à un user au hasard

                ]);
            }
        }
    }
}
