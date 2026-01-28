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
        Schema::create('post_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // Template content
            $table->text('content_template')->nullable();
            $table->string('default_title_pattern')->nullable();
            $table->text('default_excerpt')->nullable();
            $table->text('default_meta_description')->nullable();
            $table->string('default_keywords')->nullable();
            
            // Template settings
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            
            // Usage tracking
            $table->integer('usage_count')->default(0);
            
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('is_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_templates');
    }
};
