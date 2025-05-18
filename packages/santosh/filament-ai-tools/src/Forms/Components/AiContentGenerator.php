<?php

namespace Santosh\FilamentAiTools\Forms\Components;

use Santosh\FilamentAiTools\Http\Livewire\ContentGenerator;
use Filament\Forms\Components\Livewire;
use Closure;
class AiContentGenerator extends Livewire
{
    protected string|Closure $component = ContentGenerator::class;
    protected string $targetField = 'content';
   
    public static function make(Closure|string $component, array|Closure $data = []): static
    {
        return parent::make($component, $data);
    }

    public function targetField(string $field): static
    {
        return $this->statePath($field);
    }
    
   
   
}