<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('word_count')->default(0)->after('content');
            $table->integer('reading_time')->default(0)->after('word_count'); // minutes
            $table->decimal('readability_score', 5, 2)->nullable()->after('reading_time');
            $table->decimal('quality_score', 5, 2)->nullable()->after('readability_score');
            $table->char('quality_grade', 1)->nullable()->after('quality_score');
            
            $table->index('quality_score');
            $table->index('readability_score');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'word_count',
                'reading_time',
                'readability_score',
                'quality_score',
                'quality_grade',
            ]);
        });
    }
};
