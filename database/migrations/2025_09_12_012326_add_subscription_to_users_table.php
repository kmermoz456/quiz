<?php

// database/migrations/2025_09_01_000000_add_subscription_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('subscription_active')->default(true)->after('role');
            $table->timestamp('subscription_ends_at')->nullable()->after('subscription_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['subscription_active','subscription_ends_at']);
        });
    }
};
