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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan field-field baru untuk Portal Kemenag
            $table->string('username', 50)->unique()->nullable()->after('id');
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('photo')->nullable()->after('phone');
            $table->string('role_name', 20)->default('user')->after('password');
            $table->boolean('is_active')->default(true)->after('role_name');
            $table->string('legacy_username', 50)->nullable()->unique()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'phone',
                'photo',
                'role_name',
                'is_active',
                'legacy_username',
            ]);
        });
    }
};
