<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Quick Actions
        </x-slot>
        
        <x-slot name="description">
            Shortcuts to common tasks
        </x-slot>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <!-- Quick Stats -->
            <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="flex-shrink-0">
                    <x-heroicon-o-document-text class="w-8 h-8 text-primary-500" />
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ $draftCount }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Drafts</div>
                </div>
            </div>

            <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="flex-shrink-0">
                    <x-heroicon-o-clock class="w-8 h-8 text-warning-500" />
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ $scheduledCount }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Scheduled</div>
                </div>
            </div>

            <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="flex-shrink-0">
                    <x-heroicon-o-check-circle class="w-8 h-8 text-success-500" />
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ $publishedThisWeek }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">This Week</div>
                </div>
            </div>

            <!-- New Post Button -->
            <a href="{{ route('filament.admin.resources.posts.create') }}" 
               class="flex items-center justify-center gap-2 p-4 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition">
                <x-heroicon-o-plus-circle class="w-6 h-6" />
                <span class="font-semibold">New Post</span>
            </a>
        </div>

        <!-- Recent Drafts -->
        @if($recentDrafts->count() > 0)
        <div class="mt-6">
            <h3 class="text-sm font-semibold mb-3 text-gray-700 dark:text-gray-300">Recent Drafts</h3>
            <div class="space-y-2">
                @foreach($recentDrafts as $draft)
                <a href="{{ route('filament.admin.resources.posts.edit', $draft) }}" 
                   class="flex items-center justify-between p-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-primary-500 dark:hover:border-primary-500 transition">
                    <div class="flex-1 min-w-0">
                        <div class="font-medium truncate">{{ $draft->title ?? 'Untitled Draft' }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Updated {{ $draft->updated_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($draft->quality_grade)
                        <span class="px-2 py-1 text-xs font-semibold rounded 
                            {{ $draft->quality_grade === 'A' ? 'bg-success-100 text-success-800' : '' }}
                            {{ $draft->quality_grade === 'B' ? 'bg-info-100 text-info-800' : '' }}
                            {{ $draft->quality_grade === 'C' ? 'bg-warning-100 text-warning-800' : '' }}
                            {{ in_array($draft->quality_grade, ['D', 'F']) ? 'bg-danger-100 text-danger-800' : '' }}">
                            {{ $draft->quality_grade }}
                        </span>
                        @endif
                        <x-heroicon-o-chevron-right class="w-5 h-5 text-gray-400" />
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Quick Links -->
        <div class="mt-6 grid gap-3 md:grid-cols-3">
            <a href="{{ route('filament.admin.resources.posts.index') }}" 
               class="flex items-center gap-2 p-3 text-sm bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <x-heroicon-o-document-text class="w-5 h-5 text-gray-500" />
                <span>All Posts</span>
            </a>
            
            <a href="{{ route('filament.admin.resources.post-templates.index') }}" 
               class="flex items-center gap-2 p-3 text-sm bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <x-heroicon-o-document-duplicate class="w-5 h-5 text-gray-500" />
                <span>Templates</span>
            </a>
            
            <a href="{{ route('filament.admin.pages.blog-analytics') }}" 
               class="flex items-center gap-2 p-3 text-sm bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <x-heroicon-o-chart-bar class="w-5 h-5 text-gray-500" />
                <span>Analytics</span>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
