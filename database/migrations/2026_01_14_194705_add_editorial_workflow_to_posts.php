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
            // Add reviewer and editor assignments
            $table->foreignId('reviewer_id')->nullable()->after('author_id')->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->after('reviewer_id')->constrained('users')->onDelete('set null');
            
            // Add workflow timestamps
            $table->timestamp('submitted_for_review_at')->nullable()->after('scheduled_at');
            $table->timestamp('reviewed_at')->nullable()->after('submitted_for_review_at');
            $table->timestamp('approved_at')->nullable()->after('reviewed_at');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            
            // Add rejection/feedback reason
            $table->text('rejection_reason')->nullable()->after('rejected_at');
            
            $table->index('reviewer_id');
            $table->index('approved_by');
        });
        
        // Update existing posts to have proper status
        DB::table('posts')->whereNull('status')->update(['status' => 'draft']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['reviewer_id']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'reviewer_id',
                'approved_by',
                'submitted_for_review_at',
                'reviewed_at',
                'approved_at',
                'rejected_at',
                'rejection_reason',
            ]);
        });
    }
};
