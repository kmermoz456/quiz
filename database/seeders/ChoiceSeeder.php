<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Choice;
use App\Models\Question;

class ChoiceSeeder extends Seeder
{
    public function run(): void
    {
        $questions = Question::all();

        if ($questions->isEmpty()) {
            $this->command->warn("⚠️ Aucune question trouvée. Lance QuestionSeeder d'abord.");
            return;
        }

        foreach ($questions as $q) {
            if ($q->type === 'text') {
                continue; // pas de choix pour réponse libre
            }

            if ($q->type === 'true_false') {
                Choice::insert([
                    ['question_id'=>$q->id,'label'=>'Vrai','is_correct'=>true],
                    ['question_id'=>$q->id,'label'=>'Faux','is_correct'=>false],
                ]);
            } else {
                for ($i = 1; $i <= 4; $i++) {
                    Choice::create([
                        'question_id' => $q->id,
                        'label'       => "Option $i",
                        'is_correct'  => ($i === 1), // première option correcte
                    ]);
                }
            }
        }
    }
}
