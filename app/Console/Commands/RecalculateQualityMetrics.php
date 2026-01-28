<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class RecalculateQualityMetrics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:recalculate-quality {--force : Recalculate for all posts even if already calculated}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate quality metrics for all posts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = Post::query();
        
        if (!$this->option('force')) {
            $query->whereNull('quality_score');
        }
        
        $posts = $query->get();
        
        if ($posts->isEmpty()) {
            $this->info('No posts to process.');
            return 0;
        }
        
        $this->info("Recalculating quality metrics for {$posts->count()} posts...");
        
        $bar = $this->output->createProgressBar($posts->count());
        $bar->start();
        
        $successCount = 0;
        $failCount = 0;
        
        foreach ($posts as $post) {
            try {
                $post->updateQualityMetrics();
                $successCount++;
            } catch (\Exception $e) {
                $failCount++;
                $this->error("\nFailed to process post ID {$post->id}: {$e->getMessage()}");
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        
        $this->newLine(2);
        $this->info("✅ Successfully processed: {$successCount} posts");
        
        if ($failCount > 0) {
            $this->warn("⚠️  Failed to process: {$failCount} posts");
        }
        
        return 0;
    }
}
