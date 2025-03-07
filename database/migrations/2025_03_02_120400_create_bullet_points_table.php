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
        Schema::create('bullet_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('featured_id'); // Foreign key for the post
            $table->text('text'); // Bullet point text
            $table->string('heading'); // Icon for the bullet point
            $table->integer('order')->default(0); // Order for sorting
            $table->string('icon_color'); 
            $table->string('heading_color'); 
            $table->string('link_color'); 
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('featured_id')->references('id')->on('featureds')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bullet_points');
    }
};
