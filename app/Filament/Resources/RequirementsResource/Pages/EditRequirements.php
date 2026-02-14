<?php

namespace App\Filament\Resources\RequirementsResource\Pages;

use App\Filament\Resources\RequirementsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRequirements extends EditRecord
{
    protected static string $resource = RequirementsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
