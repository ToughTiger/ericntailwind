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
        Schema::create('post_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('post_comments')->onDelete('cascade');
            
            $table->text('comment');
            $table->string('type')->default('comment'); // comment, review, approval, rejection
            $table->boolean('is_internal')->default(true); // Internal admin comments only
            $table->json('mentioned_users')->nullable(); // Array of user IDs mentioned
            
            $table->timestamps();
            
            $table->index(['post_id', 'created_at']);
            $table->index('user_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_comments');
    }
};
