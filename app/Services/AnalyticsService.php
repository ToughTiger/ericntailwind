<?php

namespace App\Services;

use Spatie\Analytics\Period;
use Spatie\Analytics\Facades\Analytics;
use Carbon\Carbon;

class AnalyticsService
{

    public function fetchData(array $metrics, Period $period, array $dimensions = [], int $maxResults = 10)
    {
        return Analytics::get(
            $period,
            $metrics,
            $dimensions,
            $maxResults
        );
    }

    public function getVisitorsAndPageViews(Period $period)
    {
        return Analytics::fetchVisitorsAndPageViews($period);
    }

    public function getTotalVisitorsAndPageViews(Period $period)
    {
        $data = $this->getVisitorsAndPageViews($period);
        
        return [
            'visitors' => $data->sum('visitors'),
            'pageViews' => $data->sum('pageViews'),
        ];
    }

    public function getMostVisitedPages(Period $period, int $maxResults = 20)
    {
        return Analytics::fetchMostVisitedPages($period, $maxResults);
    }

    public function getTopReferrers(Period $period, int $maxResults = 20)
    {
        return Analytics::fetchTopReferrers($period, $maxResults);
    }

    public function getUserTypes(Period $period)
    {
        return Analytics::fetchUserTypes($period);
    }

    public function getTopBrowsers(Period $period, int $maxResults = 10)
    {
        return Analytics::fetchTopBrowsers($period, $maxResults);
    }

    public function getDeviceCategories(Period $period)
    {
        return $this->fetchData(
            ['activeUsers'],
            $period,
            ['deviceCategory'],
            10
        );
    }

    public function getAcquisitionOverview(Period $period)
    {
        return $this->fetchData(
            ['activeUsers'],
            $period,
            ['sessionDefaultChannelGroup'],
            10
        );
    }

    public function getGeoData(Period $period)
    {
        return $this->fetchData(
            ['activeUsers'],
            $period,
            ['country'],
            10
        );
    }

    public function createPeriod(string $range, ?Carbon $startDate = null, ?Carbon $endDate = null): Period
    {
        $endDate = $endDate ?? now();
        
        return match ($range) {
            'today' => Period::create($endDate->copy()->startOfDay(), $endDate),
            'yesterday' => Period::create($endDate->copy()->subDay()->startOfDay(), $endDate->copy()->subDay()->endOfDay()),
            '7days' => Period::create($endDate->copy()->subDays(6)->startOfDay(), $endDate),
            '30days' => Period::create($endDate->copy()->subDays(29)->startOfDay(), $endDate),
            '90days' => Period::create($endDate->copy()->subDays(89)->startOfDay(), $endDate),
            '12months' => Period::create($endDate->copy()->subMonths(11)->startOfDay(), $endDate),
            'month' => Period::create($endDate->copy()->startOfMonth(), $endDate),
            'quarter' => Period::create($endDate->copy()->startOfQuarter(), $endDate),
            'year' => Period::create($endDate->copy()->startOfYear(), $endDate),
            'custom' => Period::create($startDate, $endDate),
            default => Period::days(7),
        };
    }
}