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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');

            // En PostgreSQL, on évite enum natif pour simplifier les évolutions :
            $table->string('level')->default('L1');

            $table->timestamps();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });

        // Contrainte CHECK pour limiter les valeurs de "level"
        DB::statement("ALTER TABLE units
            ADD CONSTRAINT units_level_check
            CHECK (level IN ('L1','L2'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // La contrainte est supprimée automatiquement avec la table,
        // mais on peut aussi la retirer explicitement si besoin :
        // DB::statement('ALTER TABLE units DROP CONSTRAINT IF EXISTS units_level_check');

        Schema::dropIfExists('units');
    }
};
