<x-filament::widget>
@isset($data['error'])
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Error:</strong>
        <span class="block sm:inline">{{ $data['error'] }}</span>
        {{ $data['error'] }}
   </div>
@endisset
<!-- <pre>{{ dd($period, $userTypes, $totals,$mostVisitedPages,$topReferrers,$topBrowsers,$geoData,$range, $startDate, $endDate, $deviceCategories, $acquisitionOverview, $visitorsAndPageViews) }}</pre> -->
    <x-filament::card>
        <div class="space-y-6">
            <!-- Date Range Selector -->
            <div class="bg-gray-50 p-4 rounded-lg">
                {{ $this->form }}

                <div class="mt-4 flex justify-end">
                    <x-filament::button wire:click="updateData">
                        Apply
                    </x-filament::button>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <x-filament::card>
                    <div class="p-4">
                        <h3 class="text-lg font-medium text-gray-900">Total Visitors</h3>
                        <p class="text-2xl font-bold">{{ number_format($totals['visitors']) }}</p>
                        <p class="text-sm text-gray-500">
                            {{ $startDate?->format('M d, Y') ?? $period->startDate->format('M d, Y') }} - 
                            {{ $endDate?->format('M d, Y') ?? $period->endDate->format('M d, Y') }}
                        </p>
                    </div>
                </x-filament::card>

                <x-filament::card>
                    <div class="p-4">
                        <h3 class="text-lg font-medium text-gray-900">Total Page Views</h3>
                        <p class="text-2xl font-bold">{{ number_format($totals['pageViews']) }}</p>
                        <p class="text-sm text-gray-500">
                            {{ $startDate?->format('M d, Y') ?? $period->startDate->format('M d, Y') }} - 
                            {{ $endDate?->format('M d, Y') ?? $period->endDate->format('M d, Y') }}
                        </p>
                    </div>
                </x-filament::card>

                <x-filament::card>
                    <div class="p-4">
                        <h3 class="text-lg font-medium text-gray-900">New vs Returning</h3>
                        @php
                            $totalSessions = collect($userTypes)->sum('activeUsers');
                        @endphp
                        @foreach($userTypes as $type)
                            @php
                                $percentage = $totalSessions > 0
                                    ? round(($type['activeUsers'] / $totalSessions) * 100, 2)
                                    : 0;
                            @endphp
                            <p class="text-sm">
                                {{ $type['newVsReturning'] ?? 'Unknown' }}: {{ $type['activeUsers'] }} ({{ $percentage }}%)
                            </p>
                        @endforeach
                    </div>
                </x-filament::card>

                <x-filament::card>
                    <div class="p-4">
                        <h3 class="text-lg font-medium text-gray-900">Devices</h3>
                        @php
                            $deviceCollection = collect($deviceCategories);
                            $totalUsers = $deviceCollection->sum('activeUsers');
                        @endphp
                        @foreach($deviceCollection as $device)
                            @php
                                $percentage = $totalUsers > 0
                                    ? round(($device['activeUsers'] / $totalUsers) * 100, 2)
                                    : 0;
                            @endphp
                            <p class="text-sm">
                                {{ $device['deviceCategory'] }}: {{ $device['activeUsers'] }} ({{ $percentage }}%)
                            </p>
                        @endforeach
                    </div>
                </x-filament::card>
            </div>

            <!-- Main Chart -->
            <x-filament::card>
                <div class="p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Visitors & Page Views</h3>
                    <div x-data="{
                        visitors: @js($visitorsAndPageViews->pluck('visitors')->toArray()),
                        pageViews: @js($visitorsAndPageViews->pluck('pageViews')->toArray()),
                        labels: @js($visitorsAndPageViews->pluck('date')->map(fn($d) => $d->format('M d'))->toArray()),
                        init() {
                            const chart = new Chart(this.$refs.canvas.getContext('2d'), {
                                type: 'line',
                                data: {
                                    labels: this.labels,
                                    datasets: [
                                        {
                                            label: 'Visitors',
                                            data: this.visitors,
                                            borderColor: '#3b82f6',
                                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                            tension: 0.1,
                                            fill: true
                                        },
                                        {
                                            label: 'Page Views',
                                            data: this.pageViews,
                                            borderColor: '#10b981',
                                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                            tension: 0.1,
                                            fill: true
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            position: 'top',
                                        },
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });

                            this.$watch('visitors', () => {
                                chart.data.datasets[0].data = this.visitors;
                                chart.update();
                            });

                            this.$watch('pageViews', () => {
                                chart.data.datasets[1].data = this.pageViews;
                                chart.update();
                            });
                        }
                    }">
                        <canvas x-ref="canvas" height="300"></canvas>
                    </div>
                </div>
            </x-filament::card>

            <!-- Acquisition and Geo Data -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-filament::card>
                    <div class="p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Acquisition Overview</h3>
                        <div class="space-y-2">
                           
                            @foreach($acquisitionCollection as $source)
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium">{{ $source['sessionDefaultChannelGroup'] }}</span>
                                        <span class="text-sm">{{ $source['activeUsers'] }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" 
                                            style="width: {{ ($source['activeUsers'] / $maxUsers) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-filament::card>

                <!-- Top Countries -->
                <x-filament::card>
                    <div class="p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Top Countries</h3>
                        <x-filament-tables::table>
                            <x-slot name="header">
                                <x-filament-tables::header>Country</x-filament-tables::header>
                                <x-filament-tables::header numeric>Users</x-filament-tables::header>
                            </x-slot>
                            @foreach($geoData as $geo)
                                <x-filament-tables::row>
                                    <x-filament-tables::cell>
                                        {{ $geo['country'] ?? 'Direct' }}
                                    </x-filament-tables::cell>
                                    <x-filament-tables::cell numeric>
                                        {{ number_format($geo['activeUsers']) }}
                                    </x-filament-tables::cell>
                                </x-filament-tables::row>
                            @endforeach
                        </x-filament-tables::table>
                    </div>
                </x-filament::card>
            </div>

            <!-- Most Visited Pages and Top Referrers -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-filament::card>
                    <div class="p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Most Visited Pages</h3>
                        <x-filament-tables::table>
                            <x-slot name="header">
                                <x-filament-tables::header>Page</x-filament-tables::header>
                                <x-filament-tables::header numeric>Views</x-filament-tables::header>
                            </x-slot>
                            @foreach($mostVisitedPages as $page)
                                <x-filament-tables::row>
                                    <x-filament-tables::cell>
                                        {{ Str::limit($page['pageTitle'] ?: $page['pagePath'], 50) }}
                                    </x-filament-tables::cell>
                                    <x-filament-tables::cell numeric>
                                        {{ number_format($page['screenPageViews']) }}
                                    </x-filament-tables::cell>
                                </x-filament-tables::row>
                            @endforeach
                        </x-filament-tables::table>
                    </div>
                </x-filament::card>

                <x-filament::card>
                    <div class="p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Top Referrers</h3>
                        <x-filament-tables::table>
                            <x-slot name="header">
                                <x-filament-tables::header>Source</x-filament-tables::header>
                                <x-filament-tables::header numeric>Views</x-filament-tables::header>
                            </x-slot>
                            @foreach($topReferrers as $referrer)
                                <x-filament-tables::row>
                                    <x-filament-tables::cell>
                                        {{ Str::limit($referrer['sessionSource'], 50) }}
                                    </x-filament-tables::cell>
                                    <x-filament-tables::cell numeric>
                                        {{ number_format($referrer['screenPageViews']) }}
                                    </x-filament-tables::cell>
                                </x-filament-tables::row>
                            @endforeach
                        </x-filament-tables::table>
                    </div>
                </x-filament::card>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush
@livewireScripts
@livewireStyles