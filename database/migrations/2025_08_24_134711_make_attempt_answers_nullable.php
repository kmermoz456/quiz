<?php

// database/migrations/2025_08_24_000001_make_attempt_answers_nullable.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attempt_answers', function (Blueprint $table) {
            // selon votre schéma, adaptez les colonnes existantes
            if (Schema::hasColumn('attempt_answers', 'choice_id')) {
                $table->unsignedBigInteger('choice_id')->nullable()->change();
            }
            if (Schema::hasColumn('attempt_answers', 'choice_ids')) {
                // Option A : nullable
                $table->json('choice_ids')->nullable()->change();

                // Option B (si vous préférez un tableau vide par défaut et MySQL 8) :
                // $table->json('choice_ids')->default(DB::raw('(JSON_ARRAY())'))->change();
            }
            if (Schema::hasColumn('attempt_answers', 'text_answer')) {
                $table->text('text_answer')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        // laissez tel quel ou remettez NOT NULL si besoin
    }
};

