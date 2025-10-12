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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->string('media_path')->nullable();
            $table->enum('media_type', ['none','image','video','article'])->default('none');
            $table->enum('status', ['draft','queued','publishing','published','failed'])->default('draft');
            $table->timestamp('scheduled_for')->nullable();
            $table->string('visibility')->default(env('LINKEDIN_DEFAULT_VISIBILITY', 'PUBLIC'));
            $table->string('owner_urn')->nullable(); // optional override (org/page)
            $table->string('external_post_urn')->nullable();
            $table->unsignedTinyInteger('retries')->default(0);
            $table->text('last_error')->nullable();
            $table->timestamps();
            $table->index(['status','scheduled_for']);
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
