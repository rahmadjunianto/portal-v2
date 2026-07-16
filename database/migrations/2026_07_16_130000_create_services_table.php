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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // Nama layanan
            $table->string('category');          // Kategori layanan
            
            $table->text('description')->nullable();
            $table->longText('requirements')->nullable();
            $table->longText('workflow')->nullable();
            
            $table->string('processing_time')->nullable();
            $table->string('cost')->nullable();
            
            $table->string('contact')->nullable();
            $table->string('office')->nullable();
            
            $table->string('website')->nullable();
            $table->string('download_link')->nullable();
            
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
