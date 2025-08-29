<?php

// database/migrations/2025_08_24_000002_update_attempt_answers_columns.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attempt_answers', function (Blueprint $table) {
            if (!Schema::hasColumn('attempt_answers','choice_id')) {
                $table->unsignedBigInteger('choice_id')->nullable()->after('question_id');
            }
            if (!Schema::hasColumn('attempt_answers','choice_ids')) {
                $table->json('choice_ids')->nullable()->after('choice_id');
            }
            if (!Schema::hasColumn('attempt_answers','text_answer')) {
                $table->text('text_answer')->nullable()->after('choice_ids');
            }
        });
    }

    public function down(): void
    {
        Schema::table('attempt_answers', function (Blueprint $table) {
            // supprimer si besoin
            // $table->dropColumn(['choice_id','choice_ids','text_answer']);
        });
    }
};
