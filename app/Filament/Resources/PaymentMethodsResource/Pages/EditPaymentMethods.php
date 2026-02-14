<?php

namespace App\Filament\Resources\PaymentMethodsResource\Pages;

use App\Filament\Resources\PaymentMethodsResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\EditRecord;

class EditPaymentMethods extends EditRecord
{
    protected static string $resource = PaymentMethodsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'required' => Tab::make()->query(fn($query) => $query->where('is_required' , 1)),
            'nullable' => Tab::make()->query(fn($query) => $query->where('is_required' , 0)),

        ];
    }
}
