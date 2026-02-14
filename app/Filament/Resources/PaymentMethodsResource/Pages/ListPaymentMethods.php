<?php

namespace App\Filament\Resources\PaymentMethodsResource\Pages;

use App\Filament\Resources\PaymentMethodsResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListPaymentMethods extends ListRecords
{
    protected static string $resource = PaymentMethodsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'manual' => Tab::make()->query(fn($query) => $query->where('type' , 'manual')),
            'automaic' => Tab::make()->query(fn($query) => $query->where('type' , 'automatic')),
            
        ];
    }
}
