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
        Schema::create('knowledge_banks', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('category')->nullable()->comment('Kategori: nikah, zakat, pendidikan, dll');
            $table->string('tags')->nullable()->comment('Tag keywords, pisahkan dengan koma');
            $table->boolean('is_active')->default(true)->comment('Aktif/nonaktif');
            $table->integer('priority')->default(0)->comment('Prioritas lebih tinggi = dicek lebih dulu');
            $table->timestamps();
            
            $table->index(['is_active', 'priority']);
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_banks');
    }
};
