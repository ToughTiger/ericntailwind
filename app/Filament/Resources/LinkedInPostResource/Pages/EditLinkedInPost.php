<?php

namespace App\Filament\Resources\LinkedInPostResource\Pages;

use App\Filament\Resources\LinkedInPostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLinkedInPost extends EditRecord
{
    protected static string $resource = LinkedInPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
