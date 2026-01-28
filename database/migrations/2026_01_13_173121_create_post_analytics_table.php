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
        Schema::create('post_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('device_type', 50)->nullable(); // mobile, tablet, desktop
            $table->string('browser', 100)->nullable();
            $table->string('platform', 100)->nullable(); // OS
            $table->string('referrer_url')->nullable();
            $table->string('traffic_source', 50)->nullable(); // direct, social, search, referral
            $table->string('country_code', 10)->nullable();
            $table->string('city', 100)->nullable();
            $table->integer('scroll_depth')->default(0); // percentage
            $table->integer('time_spent')->default(0); // seconds
            $table->boolean('is_unique_visitor')->default(false);
            $table->boolean('read_complete')->default(false);
            $table->timestamp('viewed_at');
            $table->timestamps();
            
            $table->index(['post_id', 'viewed_at']);
            $table->index('ip_address');
            $table->index('traffic_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_analytics');
    }
};
