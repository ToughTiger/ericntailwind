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
        Schema::create('post_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Version data
            $table->integer('version_number')->default(1);
            $table->string('title');
            $table->text('content');
            $table->text('excerpt')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('keywords')->nullable();
            
            // Metadata
            $table->string('change_summary')->nullable();
            $table->boolean('is_major_change')->default(false);
            $table->json('changes_made')->nullable(); // Track what changed
            
            $table->timestamps();
            
            $table->index(['post_id', 'version_number']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_versions');
    }
};
