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
    Schema::table('exam_attempts', function (Blueprint $t) {
        $t->decimal('score', 5, 2)->default(0)->change(); // 0.00–100.00
        $t->decimal('max_score', 6, 2)->default(0)->change();
    });
}
public function down(): void
{
    Schema::table('exam_attempts', function (Blueprint $t) {
        $t->integer('score')->default(0)->change();
        $t->integer('max_score')->default(0)->change();
    });
}

};
