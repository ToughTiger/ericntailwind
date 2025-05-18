<?php

namespace Santosh\FilamentAiTools;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Santosh\FilamentAiTools\Http\Livewire\ContentGenerator;

class FilamentAiToolsServiceProvider  extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/filament-ai-tools.php', 'filament-ai-tools');
       

    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-ai-tools');
        $this->publishes([
            __DIR__.'/../config/filament-ai-tools.php' => config_path('filament-ai-tools.php'),
            __DIR__.'/../resources/js' => public_path('vendor/filament-ai-tools'),
        ], 'filament-ai-tools');
        Livewire::component('content-generator', ContentGenerator::class);
    }
}
