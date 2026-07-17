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
        Schema::table('services', function (Blueprint $table) {
            // Add foreign key to service_categories
            $table->foreignId('service_category_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('service_categories')
                  ->nullOnDelete();
            
            // Drop the old string category column
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Add back the string category column
            $table->string('category')->nullable();
            
            // Drop the foreign key
            $table->dropForeign(['service_category_id']);
            $table->dropColumn('service_category_id');
        });
    }
};
