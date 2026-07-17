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
        Schema::create('unknown_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question')->comment('Original question from user');
            $table->string('normalized_question')->comment('Lowercase, trimmed for deduplication');
            $table->integer('count')->default(1)->comment('How many times this question was asked');
            $table->timestamp('last_asked_at')->nullable();
            $table->enum('status', ['unprocessed', 'processing', 'resolved'])->default('unprocessed');
            $table->foreignId('suggested_service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->timestamps();
            
            $table->index('normalized_question');
            $table->index('count');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unknown_questions');
    }
};
