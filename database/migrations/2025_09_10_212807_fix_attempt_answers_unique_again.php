<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        // 1) Corriger toutes les valeurs 0 -> NULL pour éviter de retomber sur le même souci
        try {
            DB::table('attempt_answers')->where('choice_id', 0)->update(['choice_id' => null]);
        } catch (\Throwable $e) {}

        // 2) Supprimer tous les index "historiques" qui peuvent traîner
        //    (le nom visible dans ton erreur est 'aa_attempt_q_text_unique')
        $dropCandidates = [
            'aa_attempt_q_text_unique',
            'attempt_answers_aa_attempt_q_text_unique',
            'attempt_answers_attempt_q_text_unique',
        ];

        foreach ($dropCandidates as $name) {
            try {
                Schema::table('attempt_answers', function (Blueprint $table) use ($name) {
                    $table->dropUnique($name);
                });
            } catch (\Throwable $e) {
                // ignore si l'index n'existe pas
            }
        }

        // 3) choice_id NULL par défaut (plus de DEFAULT 0)
        if ($driver === 'mysql') {
            // supprimer l'FK si elle empêche le MODIFY (au cas où)
            try {
                DB::statement('ALTER TABLE attempt_answers DROP FOREIGN KEY attempt_answers_choice_id_foreign');
            } catch (\Throwable $e) {}

            DB::statement('ALTER TABLE attempt_answers MODIFY choice_id BIGINT UNSIGNED NULL DEFAULT NULL');

            // recréer l'FK proprement
            try {
                DB::statement('ALTER TABLE attempt_answers
                    ADD CONSTRAINT attempt_answers_choice_id_foreign
                    FOREIGN KEY (choice_id) REFERENCES choices(id) ON DELETE CASCADE');
            } catch (\Throwable $e) {}

            // 4) recréer l’index unique correct (permet plusieurs NULL)
            try {
                DB::statement('ALTER TABLE attempt_answers
                    ADD UNIQUE KEY aa_attempt_q_unique (exam_attempt_id,question_id,choice_id)');
            } catch (\Throwable $e) {}
        } else { // PostgreSQL
            DB::statement('ALTER TABLE attempt_answers ALTER COLUMN choice_id DROP NOT NULL');
            DB::statement('ALTER TABLE attempt_answers ALTER COLUMN choice_id DROP DEFAULT');
            DB::statement('ALTER TABLE attempt_answers ALTER COLUMN choice_id TYPE BIGINT');

            // FK si absente
            try {
                DB::statement('ALTER TABLE attempt_answers
                    ADD CONSTRAINT attempt_answers_choice_id_foreign
                    FOREIGN KEY (choice_id) REFERENCES choices(id) ON DELETE CASCADE');
            } catch (\Throwable $e) {}

            // recréer unique correct
            try {
                DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS aa_attempt_q_unique
                    ON attempt_answers (exam_attempt_id, question_id, choice_id)');
            } catch (\Throwable $e) {}
        }
    }

    public function down(): void
    {
        // On ne remet PAS l’ancien index buggué
        try {
            Schema::table('attempt_answers', function (Blueprint $table) {
                $table->dropUnique('aa_attempt_q_unique');
            });
        } catch (\Throwable $e) {}
    }
};
