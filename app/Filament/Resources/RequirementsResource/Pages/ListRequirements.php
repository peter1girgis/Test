<?php

namespace App\Filament\Resources\RequirementsResource\Pages;

use App\Filament\Resources\RequirementsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRequirements extends ListRecords
{
    protected static string $resource = RequirementsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
