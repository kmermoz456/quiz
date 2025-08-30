<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            // si la FK existe déjà et est NOT NULL, il faut souvent la drop/recreate
            try { $table->dropForeign(['subject_id']); } catch (\Throwable $e) {}
            $table->integer('subject_id')->nullable()->change();
            $table->foreign('subject_id')->references('id')->on('subjects')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            try { $table->dropForeign(['subject_id']); } catch (\Throwable $e) {}
            $table->unsignedBigInteger('subject_id')->nullable(false)->change();
            $table->foreign('subject_id')->references('id')->on('subjects')->cascadeOnDelete();
        });
    }
};

