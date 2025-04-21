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
        Schema::create('linked_in_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->text('content');
            $table->string('media_path')->nullable();
            $table->string('media_type')->nullable(); // 'image', 'video', 'article'
            $table->string('status')->default('draft'); // 'draft', 'published', 'failed'
            $table->string('linkedin_post_id')->nullable();
            $table->timestamp('scheduled_for')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linked_in_posts');
    }
};
