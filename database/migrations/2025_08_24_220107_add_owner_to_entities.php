<?php

// database/migrations/2025_08_24_000900_add_owner_to_entities.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        foreach (['units','subjects','questions','exams'] as $table) {
            Schema::table($table, function (Blueprint $t) use ($table) {
                if (!Schema::hasColumn($table,'user_id')) {
                    $t->foreignId('user_id')->nullable()
                      ->constrained()->nullOnDelete(); // user supprimé => ressources orphelines (à toi de voir)
                }
            });
        }
    }

    public function down(): void
    {
        foreach (['units','subjects','questions','exams'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                if (Schema::hasColumn($t->getTable(),'user_id')) {
                    $t->dropConstrainedForeignId('user_id');
                }
            });
        }
    }
};
