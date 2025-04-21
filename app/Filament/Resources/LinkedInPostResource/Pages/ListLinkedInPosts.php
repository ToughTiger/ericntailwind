<?php

namespace App\Filament\Resources\LinkedInPostResource\Pages;

use App\Filament\Resources\LinkedInPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLinkedInPosts extends ListRecords
{
    protected static string $resource = LinkedInPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
