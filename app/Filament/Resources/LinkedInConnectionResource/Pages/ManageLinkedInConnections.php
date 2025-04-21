<?php

namespace App\Filament\Resources\LinkedInConnectionResource\Pages;

use App\Filament\Resources\LinkedInConnectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLinkedInConnections extends ManageRecords
{
    protected static string $resource = LinkedInConnectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
