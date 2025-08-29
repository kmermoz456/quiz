<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            // Nécessite doctrine/dbal pour change()
            $table->unsignedTinyInteger('score')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->unsignedTinyInteger('score')->nullable(false)->default(0)->change();
        });
    }
};
