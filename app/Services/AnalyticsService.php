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

    public function createPeriod(string $range, ?Carbon $start_date = null, ?Carbon $end_date = null): Period
    {
        $end_date = $end_date ?? now();
        
        return match ($range) {
            'today' => Period::create($end_date->copy()->startOfDay(), $end_date),
            'yesterday' => Period::create($end_date->copy()->subDay()->startOfDay(), $end_date->copy()->subDay()->endOfDay()),
            '7days' => Period::create($end_date->copy()->subDays(6)->startOfDay(), $end_date),
            '30days' => Period::create($end_date->copy()->subDays(29)->startOfDay(), $end_date),
            '90days' => Period::create($end_date->copy()->subDays(89)->startOfDay(), $end_date),
            '12months' => Period::create($end_date->copy()->subMonths(11)->startOfDay(), $end_date),
            'month' => Period::create($end_date->copy()->startOfMonth(), $end_date),
            'quarter' => Period::create($end_date->copy()->startOfQuarter(), $end_date),
            'year' => Period::create($end_date->copy()->startOfYear(), $end_date),
            'custom' => Period::create($start_date, $end_date),
            default => Period::days(7),
        };
    }
}