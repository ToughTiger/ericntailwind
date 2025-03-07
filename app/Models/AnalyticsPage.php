<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
class AnalyticsPage extends Model
{
    protected $fillable = ['pageTitle', 'url', 'pageViews'];

    public static function fromCollection(Collection $data): Builder
    {
        return (new static)->newQuery()->getQuery()->setQuery($data);
    }
}
