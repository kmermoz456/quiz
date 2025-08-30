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
            $table->string('title');

            // durée en minutes (par défaut 30)
            $table->integer('duration_minutes')->default(30);

            // dates/heures de début et de fin
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            // état de publication
            $table->boolean('is_published')->default(false);

            $table->timestamps();
             $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
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
