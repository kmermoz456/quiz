<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            $table->text('statement');

            // On garde string + contrainte CHECK (plus simple à maintenir que ENUM PostgreSQL)
            $table->string('type')->default('single');

            $table->integer('points')->default(1);
            $table->integer('position')->default(0);
            $table->timestamps();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });

        // Ajout contrainte CHECK pour limiter les valeurs de type
        DB::statement("ALTER TABLE questions
            ADD CONSTRAINT questions_type_check
            CHECK (type IN ('single','multiple','true_false'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
