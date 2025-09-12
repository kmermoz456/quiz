<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempt_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exam_attempt_id')
                ->constrained('exam_attempts')
                ->cascadeOnDelete();

            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete();

            // Une ligne par choix sélectionné. NULL pour les questions à réponse texte.
            $table->foreignId('choice_id')
                ->nullable()
                ->constrained('choices')
                ->cascadeOnDelete();

            // Réponse libre (si applicable)
            $table->text('value_text')->nullable();

            // Points attribués (auto / manuel)
            $table->unsignedInteger('awarded_points')->default(0);

            $table->timestamps();

            // Empêche de cocher deux fois le même choix pour la même question/tentative
            $table->unique(
                ['exam_attempt_id', 'question_id', 'choice_id'],
                'aa_attempt_q_choice_unique'
            );
        });

        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            // Index unique partiel : une seule ligne "texte" (choice_id IS NULL)
            DB::statement("
                CREATE UNIQUE INDEX aa_attempt_q_text_unique
                ON attempt_answers (exam_attempt_id, question_id)
                WHERE choice_id IS NULL
            ");
        } elseif ($driver === 'mysql') {
            // MySQL ne supporte pas les index partiels → colonne générée + unique composite
            Schema::table('attempt_answers', function (Blueprint $table) {
                // tinyint(1) virtuel : 1 si choice_id est NULL (réponse texte), sinon 0
                $table->boolean('is_text')
                      ->virtualAs('IF(`choice_id` IS NULL, 1, 0)')
                      ->after('choice_id');
            });

            Schema::table('attempt_answers', function (Blueprint $table) {
                $table->unique(
                    ['exam_attempt_id', 'question_id', 'is_text'],
                    'aa_attempt_q_text_unique'
                );
            });
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS aa_attempt_q_text_unique');
        } elseif ($driver === 'mysql') {
            // Supprime l’unique sur (exam_attempt_id, question_id, is_text) puis la colonne générée
            Schema::table('attempt_answers', function (Blueprint $table) {
                $table->dropUnique('aa_attempt_q_text_unique');
                // Certaines versions nécessitent un dropColumn après avoir retiré l’index
                $table->dropColumn('is_text');
            });
        }

        Schema::dropIfExists('attempt_answers');
    }
};
