<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('exam_question', function (Blueprint $table) {
            // ajoute la colonne si elle n’existe pas
            if (!Schema::hasColumn('exam_question', 'position')) {
                $table->integer('position')->default(0)->after('question_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('exam_question', function (Blueprint $table) {
            if (Schema::hasColumn('exam_question', 'position')) {
                $table->dropColumn('position');
            }
        });
    }
};

