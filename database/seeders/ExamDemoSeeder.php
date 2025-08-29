<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\{Unit, Subject, Question, Choice, Exam};

class ExamDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // ---------- Unités d’enseignement ----------
            $ueBio = Unit::firstOrCreate(
                ['code' => 'BIC1001'],
                ['name' => 'Biologie Cellulaire']
            );

            $ueMath = Unit::firstOrCreate(
                ['code' => 'MAT1102'],
                ['name' => 'Mathématiques Générales']
            );

            // ---------- Sujets ----------
            $sBioL1 = Subject::firstOrCreate(
                ['title' => 'Cellule', 'level' => 'L1'],
                ['unit_id' => $ueBio->id, 'description' => 'Biologie cellulaire : structures et fonctions']
            );

            $sGeneL2 = Subject::firstOrCreate(
                ['title' => 'Génétique', 'level' => 'L2'],
                ['unit_id' => $ueBio->id, 'description' => 'Bases de la génétique mendélienne et moléculaire']
            );

            $sMathL1 = Subject::firstOrCreate(
                ['title' => 'Algèbre linéaire', 'level' => 'L1'],
                ['unit_id' => $ueMath->id, 'description' => 'Vecteurs, matrices, systèmes linéaires']
            );

            // Petite fonction utilitaire pour créer une question + ses choix
            $makeQ = function (Subject $subject, string $type, string $statement, array $choices = [], int $points = 1, int $position = 0) {
                $q = Question::create([
                    'subject_id' => $subject->id,
                    'type'       => $type,           // 'single'|'multiple'|'true_false'|'text'
                    'statement'  => $statement,
                    'points'     => $points,
                    'position'   => $position,
                ]);

                // Créer les choices si donnés (pas pour "text")
                foreach ($choices as $choice) {
                    // $choice = ['label' => '…', 'is_correct' => bool]
                    Choice::create([
                        'question_id' => $q->id,
                        'label'       => $choice['label'],
                        'is_correct'  => (bool) ($choice['is_correct'] ?? false),
                    ]);
                }

                return $q;
            };

            // ---------- Questions (quelques exemples par type) ----------
            // Sujet L1 : Cellule
            $q1 = $makeQ($sBioL1, 'single',
                "La mitochondrie est le siège principal de la production d'ATP ?",
                [
                    ['label' => 'Vrai', 'is_correct' => true],
                    ['label' => 'Faux', 'is_correct' => false],
                ],
                points: 1, position: 1
            );

            $q2 = $makeQ($sBioL1, 'multiple',
                "Sélectionnez les organites présents chez les cellules eucaryotes :",
                [
                    ['label' => 'Ribosomes', 'is_correct' => true],
                    ['label' => 'Mitochondries', 'is_correct' => true],
                    ['label' => 'Paroi peptidoglycane', 'is_correct' => false],
                    ['label' => 'Appareil de Golgi', 'is_correct' => true],
                ],
                points: 2, position: 2
            );

            $q3 = $makeQ($sBioL1, 'text',
                "Décrivez brièvement le rôle du réticulum endoplasmique rugueux.",
                choices: [], points: 0, position: 3
            );

            // Sujet L2 : Génétique
            $q4 = $makeQ($sGeneL2, 'true_false',
                "La transcription synthétise un brin d’ARN à partir de l’ADN.",
                [
                    ['label' => 'Vrai', 'is_correct' => true],
                    ['label' => 'Faux', 'is_correct' => false],
                ],
                points: 1, position: 1
            );

            $q5 = $makeQ($sGeneL2, 'single',
                "Chez l’humain, le caryotype normal diploïde possède :",
                [
                    ['label' => '22 paires d’autosomes + XY/XX', 'is_correct' => true],
                    ['label' => '23 paires d’autosomes', 'is_correct' => false],
                    ['label' => '21 paires d’autosomes + XY/XX', 'is_correct' => false],
                ],
                points: 1, position: 2
            );

            // Sujet L1 : Algèbre linéaire
            $q6 = $makeQ($sMathL1, 'single',
                "Le rang d’une matrice est égal au nombre maximal de colonnes (ou lignes) :",
                [
                    ['label' => 'Linéairement indépendantes', 'is_correct' => true],
                    ['label' => 'Identiques', 'is_correct' => false],
                    ['label' => 'Nuls', 'is_correct' => false],
                ],
                points: 1, position: 1
            );

            $q7 = $makeQ($sMathL1, 'multiple',
                "Soit A une matrice carrée inversible. Cochez les affirmations vraies :",
                [
                    ['label' => 'det(A) ≠ 0', 'is_correct' => true],
                    ['label' => 'rang(A) = nombre de lignes', 'is_correct' => true],
                    ['label' => 'A n’a pas d’inverse', 'is_correct' => false],
                    ['label' => 'Ker(A) = {0}', 'is_correct' => true],
                ],
                points: 2, position: 2
            );

            // ---------- Examen de démonstration ----------
            $exam = Exam::firstOrCreate(
                ['title' => 'Examen de démonstration ITF'],
                [
                    'duration_minutes' => 15,
                    'starts_at'        => now()->subHour(),
                    'ends_at'          => now()->addWeek(),
                    'is_published'     => true,
                ]
            );

            // Attacher des questions multi-sujets via le pivot + positions
            $attach = [
                $q1->id => ['position' => 1],
                $q2->id => ['position' => 2],
                $q4->id => ['position' => 3],
                $q5->id => ['position' => 4],
                $q6->id => ['position' => 5],
                $q7->id => ['position' => 6],
                // on pourrait ignorer $q3 (texte) pour l'examen si souhaité
            ];

            $exam->questions()->syncWithoutDetaching($attach);
        });
    }
}
