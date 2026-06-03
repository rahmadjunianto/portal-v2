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
        Schema::table('posts', function (Blueprint $table) {
            // Responsive image paths (stores filename, actual files are in storage/posts/)
            $table->string('thumbnail_webp')->nullable()->after('thumbnail');
            $table->string('thumbnail_avif')->nullable()->after('thumbnail_webp');
            $table->unsignedInteger('thumbnail_width')->nullable()->after('thumbnail_avif');
            $table->unsignedInteger('thumbnail_height')->nullable()->after('thumbnail_width');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['thumbnail_webp', 'thumbnail_avif', 'thumbnail_width', 'thumbnail_height']);
        });
    }
};
