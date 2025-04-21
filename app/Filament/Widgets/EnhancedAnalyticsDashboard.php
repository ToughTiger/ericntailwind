<?php

namespace App\Filament\Widgets;

use App\Services\AnalyticsService;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Spatie\Analytics\Period;

class EnhancedAnalyticsDashboard extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.enhanced-analytics-dashboard';

    protected int|string|array $columnSpan = 'full';

    public ?array $data = [];

    public function mount()
    {
        $this->form->fill([
            'range' => '7days',
            'start_date' => Carbon::now()->subDays(6)->format('Y-m-d'),
            'end_date' => Carbon::now()->format('Y-m-d'),
        ]);
        $this->updateData();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('range')
                    ->options([
                        'today' => 'Today',
                        'yesterday' => 'Yesterday',
                        '7days' => 'Last 7 days',
                        '30days' => 'Last 30 days',
                        '90days' => 'Last 90 days',
                        '12months' => 'Last 12 months',
                        'month' => 'This month',
                        'quarter' => 'This quarter',
                        'year' => 'This year',
                        'custom' => 'Custom range',
                    ])
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state !== 'custom') {
                            $set('start_date', null);
                            $set('end_date', null);
                        }
                    }),
                DatePicker::make('start_date')
                    ->hidden(fn ($get) => $get('range') !== 'custom')
                    ->required(fn ($get) => $get('range') === 'custom')
                    ->maxDate(fn ($get) => $get('end_date') ?: now()),
                DatePicker::make('end_date')
                    ->hidden(fn ($get) => $get('range') !== 'custom')
                    ->required(fn ($get) => $get('range') === 'custom')
                    ->minDate(fn ($get) => $get('start_date'))
                    ->maxDate(now()),
            ])
            ->statePath('data')
            ->columns(4);
    }

    public function updateData(): void
    {
        try{
        $state = $this->form->getState();
       
        
        $analyticsService = app(AnalyticsService::class);

        $range = $state['range'] ?? '7days';
        $startDate = isset($state['start_date']) && !empty($state['start_date']) 
            ? Carbon::parse($state['start_date']) 
            : now()->subDays(6);
        $endDate = isset($state['end_date']) && !empty($state['end_date'])
            ? Carbon::parse($state['end_date'])
            : now();
        
        $period = $analyticsService->createPeriod(
            $range,
            $startDate,
            $endDate
        );
       
        $this->data = [
        'period' => $period,
        'totals' => $analyticsService->getTotalVisitorsAndPageViews($period),
        'visitorsAndPageViews' => $analyticsService->getVisitorsAndPageViews($period),
        'mostVisitedPages' => $analyticsService->getMostVisitedPages($period),
        'topReferrers' => $analyticsService->getTopReferrers($period),
        'userTypes' => $analyticsService->getUserTypes($period),
        'topBrowsers' => $analyticsService->getTopBrowsers($period),
        'deviceCategories' => $analyticsService->getDeviceCategories($period),
        'acquisitionOverview' => $analyticsService->getAcquisitionOverview($period),
        'geoData' => $analyticsService->getGeoData($period),
        'range' => $range,
        'startDate' => $startDate,
        'endDate' => $endDate,
        ];
    }
        catch (\Exception $e) {
            $this->data['error'] = 'Failed to load analytics data';
        logger()->error('Analytics error: ' . $e->getMessage());
        }
    }

    public function getViewData(): array
    {
        return $this->data;
    }
}