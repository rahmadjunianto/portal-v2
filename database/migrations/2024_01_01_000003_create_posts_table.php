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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('legacy_id')->nullable()->unique();
            $table->foreignId('category_id')->nullable()->constrained('post_categories')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 255);
            $table->string('subtitle', 255)->nullable();
            $table->string('slug', 255)->unique();
            $table->longText('content');
            $table->string('thumbnail')->nullable();
            $table->text('image_caption')->nullable();
            $table->string('youtube_url')->nullable();
            $table->enum('type', ['berita', 'pengumuman', 'lowongan'])->default('berita');
            $table->boolean('is_headline')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            // Indexes for common queries
            $table->index(['status', 'is_active']);
            $table->index(['is_headline', 'is_featured']);
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
