<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-actions-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = -1; // Show at top
    
    public function getViewData(): array
    {
        return [
            'recentDrafts' => Post::where('status', 'draft')
                ->where('author_id', auth()->id())
                ->latest('updated_at')
                ->take(5)
                ->get(),
                
            'scheduledCount' => Post::where('status', 'scheduled')->count(),
            
            'draftCount' => Post::where('status', 'draft')->count(),
            
            'publishedThisWeek' => Post::where('status', 'published')
                ->where('published_at', '>=', now()->startOfWeek())
                ->count(),
        ];
    }
}
