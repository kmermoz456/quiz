<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Choice;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Unit;
use App\Models\User;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           $admin = User::factory()->create(['name' => 'Enseignant', 'email' => 'teacher@example.com', 'role' => 'admin']);
        $student = User::factory()->create(['name' => 'Étudiant', 'email' => 'student@example.com', 'role' => 'student', 'level' => 'L1']);
        $ue = Unit::create(['code' => 'BIO101', 'name' => 'Biologie Générale']);
        $subject = Subject::create(['unit_id' => $ue->id, 'title' => 'Génétique', 'level' => 'L1']);
        $q1 = Question::create(['subject_id' => $subject->id, 'statement' => 'ADN= acide
désoxyribonucléique ?', 'type' => 'true_false', 'points' => 1, 'position' => 1]);
        Choice::insert([
            ['question_id' => $q1->id, 'label' => 'Vrai', 'is_correct' => true],
            ['question_id' => $q1->id, 'label' => 'Faux', 'is_correct' => false],
        ]);
        Exam::create(['subject_id' => $subject->id, 'title' => 'Quiz
Intro', 'duration_minutes' => 10, 'is_published' => true]);
    }
    }

