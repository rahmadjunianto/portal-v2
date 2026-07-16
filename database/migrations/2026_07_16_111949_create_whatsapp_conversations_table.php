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
        Schema::create('whatsapp_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->index();
            $table->string('session_id', 64)->nullable()->index();
            $table->enum('role', ['system', 'user', 'assistant']);
            $table->text('content');
            $table->string('name', 100)->nullable();
            $table->timestamps();
            $table->index(['phone', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_conversations');
    }
};
