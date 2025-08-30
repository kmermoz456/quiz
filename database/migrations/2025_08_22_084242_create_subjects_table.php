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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();

            // Référence vers units.id
            $table->foreignId('unit_id')
                ->constrained('units')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            // En PGSQL : on évite enum, on utilise string + CHECK
            $table->string('level'); // pas de default pour respecter ta version

            $table->timestamps();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });

        // Contrainte CHECK sur level
        DB::statement("ALTER TABLE subjects
            ADD CONSTRAINT subjects_level_check
            CHECK (level IN ('L1','L2'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // La contrainte sera supprimée avec la table
        Schema::dropIfExists('subjects');
    }
};
