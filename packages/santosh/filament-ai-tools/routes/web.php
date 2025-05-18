<?php

use Illuminate\Support\Facades\Route;
use Santosh\FilamentAiTools\Http\Livewire\ContentGenerator;

Route::get('/ai-tools', ContentGenerator::class);
