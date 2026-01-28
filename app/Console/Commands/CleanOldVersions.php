<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Services\VersionControlService;
use Illuminate\Console\Command;

class CleanOldVersions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:clean-versions {--keep=10 : Number of versions to keep per post}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old post versions (keeps major changes and latest N versions)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $keepCount = (int) $this->option('keep');
        $versionService = app(VersionControlService::class);
        
        $posts = Post::has('versions')->get();
        
        if ($posts->isEmpty()) {
            $this->info('No posts with versions found.');
            return 0;
        }
        
        $this->info("Cleaning old versions for {$posts->count()} posts (keeping {$keepCount} versions)...");
        
        $bar = $this->output->createProgressBar($posts->count());
        $bar->start();
        
        $totalDeleted = 0;
        
        foreach ($posts as $post) {
            $deleted = $versionService->cleanOldVersions($post, $keepCount);
            $totalDeleted += $deleted;
            $bar->advance();
        }
        
        $bar->finish();
        
        $this->newLine(2);
        $this->info("✅ Cleaned up {$totalDeleted} old versions");
        $this->comment("   Kept: Latest {$keepCount} versions + all major changes");
        
        return 0;
    }
}
