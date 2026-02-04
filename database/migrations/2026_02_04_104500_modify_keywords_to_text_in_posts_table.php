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
            // Change keywords from VARCHAR(255) to TEXT to accommodate long keyword lists
            $table->text('keywords')->nullable()->change();
            
            // Also increase meta_description size for better SEO descriptions
            $table->string('meta_description', 500)->nullable()->change();
            
            // Increase title size for longer titles
            $table->string('title', 500)->change();
            
            // Make alt text nullable and longer
            $table->string('alt', 500)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('keywords', 255)->change();
            $table->string('meta_description', 255)->change();
            $table->string('title', 255)->change();
            $table->string('alt', 255)->change();
        });
    }
};
