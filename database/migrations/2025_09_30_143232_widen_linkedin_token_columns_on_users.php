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
        Schema::table('users', function (Blueprint $table) {
            DB::statement('ALTER TABLE `users` MODIFY `linkedin_access_token` TEXT NULL');
            DB::statement('ALTER TABLE `users` MODIFY `linkedin_refresh_token` TEXT NULL');
            // Optional: keep URN short but safe
            DB::statement('ALTER TABLE `users` MODIFY `linkedin_urn` VARCHAR(191) NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement('ALTER TABLE `users` MODIFY `linkedin_access_token` VARCHAR(255) NULL');
            DB::statement('ALTER TABLE `users` MODIFY `linkedin_refresh_token` VARCHAR(255) NULL');
            DB::statement('ALTER TABLE `users` MODIFY `linkedin_urn` VARCHAR(255) NULL');
        });
    }
};
