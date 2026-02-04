{{-- resources/views/filament/components/quality-metrics.blade.php --}}

<div class="space-y-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
    <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
        📊 Content Quality Metrics
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Readability Score --}}
        <div class="bg-white dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Readability</span>
                @if(isset($readability['score']))
                    <span class="px-2 py-1 text-xs font-bold rounded-full
                        {{ $readability['score'] >= 60 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                        {{ $readability['score'] >= 40 && $readability['score'] < 60 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                        {{ $readability['score'] < 40 ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                        {{ number_format($readability['score'], 1) }}
                    </span>
                @endif
            </div>
            @if(isset($readability['grade']))
                <div class="text-lg font-bold text-gray-900 dark:text-white">
                    {{ $readability['grade'] }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Grade Level
                </div>
            @endif
        </div>

        {{-- Quality Score --}}
        <div class="bg-white dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Quality</span>
                @if(isset($quality['percentage']))
                    <span class="px-2 py-1 text-xs font-bold rounded-full
                        {{ $quality['percentage'] >= 70 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                        {{ $quality['percentage'] >= 50 && $quality['percentage'] < 70 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                        {{ $quality['percentage'] < 50 ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                        {{ number_format($quality['percentage'], 0) }}%
                    </span>
                @endif
            </div>
            @if(isset($quality['grade']))
                <div class="text-lg font-bold text-gray-900 dark:text-white">
                    Grade {{ $quality['grade'] }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Overall Quality
                </div>
            @endif
        </div>

        {{-- Reading Time --}}
        <div class="bg-white dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Reading Time</span>
                <span class="px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                    {{ $readingTime['minutes'] ?? 0 }} min
                </span>
            </div>
            @if(isset($readingTime['wordCount']))
                <div class="text-lg font-bold text-gray-900 dark:text-white">
                    {{ number_format($readingTime['wordCount']) }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Words
                </div>
            @endif
        </div>
    </div>

    {{-- Additional Details --}}
    @if(isset($readability['details']) || isset($quality['issues']))
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <details class="cursor-pointer">
                <summary class="text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    View Details
                </summary>
                <div class="mt-3 space-y-2">
                    @if(isset($readability['details']))
                        <div class="text-xs text-gray-600 dark:text-gray-400">
                            <strong>Readability:</strong> {{ $readability['details'] }}
                        </div>
                    @endif
                    @if(isset($quality['issues']) && count($quality['issues']) > 0)
                        <div class="text-xs text-gray-600 dark:text-gray-400">
                            <strong>Issues Found:</strong>
                            <ul class="list-disc list-inside ml-2 mt-1">
                                @foreach($quality['issues'] as $issue)
                                    <li>{{ $issue }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </details>
        </div>
    @endif

    {{-- Recommendations --}}
    @php
        $recommendations = [];
        if (isset($readability['score']) && $readability['score'] < 60) {
            $recommendations[] = 'Consider simplifying sentences for better readability';
        }
        if (isset($quality['percentage']) && $quality['percentage'] < 70) {
            $recommendations[] = 'Improve content quality by adding more details or examples';
        }
        if (isset($readingTime['wordCount']) && $readingTime['wordCount'] < 300) {
            $recommendations[] = 'Article may be too short for good SEO (aim for 800+ words)';
        }
    @endphp

    @if(count($recommendations) > 0)
        <div class="mt-4 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
            <div class="text-xs font-medium text-amber-800 dark:text-amber-200 mb-2">
                💡 Recommendations:
            </div>
            <ul class="text-xs text-amber-700 dark:text-amber-300 space-y-1">
                @foreach($recommendations as $recommendation)
                    <li>• {{ $recommendation }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
