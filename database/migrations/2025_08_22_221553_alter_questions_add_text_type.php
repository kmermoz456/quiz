<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // étend l’ENUM pour inclure 'text'
            $table->string('type', ['single','multiple','true_false','text'])
                  ->default('single')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // revenir sans 'text' si besoin
            $table->enum('type', ['single','multiple','true_false'])
                  ->default('single')
                  ->change();
        });
    }
};
