<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
$table->foreignId('subject_id')->constrained()->cascadeOnDelete();
$table->string('title');
$table->unsignedInteger('duration_minutes')->default(30);
$table->timestamp('starts_at')->nullable();
$table->timestamp('ends_at')->nullable();
$table->boolean('is_published')->default(false);
$table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
