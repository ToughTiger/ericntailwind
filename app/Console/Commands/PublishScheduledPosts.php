<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class PublishScheduledPosts extends Command
{
    protected $signature = 'posts:publish-scheduled';
    protected $description = 'Publish posts that are scheduled for the current time or earlier';

    public function handle()
    {
        $posts = Post::scheduled()->get();

        if ($posts->isEmpty()) {
            $this->info('No posts to publish.');
            return 0;
        }

        $count = 0;
        foreach ($posts as $post) {
            if ($post->shouldBePublished()) {
                $post->publish();
                $this->info("Published: {$post->title}");
                $count++;
            }
        }

        $this->info("Successfully published {$count} post(s).");
        return 0;
    }
}
