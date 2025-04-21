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
        Schema::create('case_study_downloads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_study_id'); // Foreign key for the post
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();

            $table->foreign('case_study_id')->references('id')->on('case_studies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        Schema::dropIfExists('case_study_downloads');
    }
};
