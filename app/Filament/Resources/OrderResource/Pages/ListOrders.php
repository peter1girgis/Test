<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }
    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'New' => Tab::make()->query(fn($query) => $query->where('status' , 'new')),
            'processing' => Tab::make()->query(fn($query) => $query->where('status' , 'processing')),
            'shipping' => Tab::make()->query(fn($query) => $query->where('status' , 'shipping')),
            'cancelled' => Tab::make()->query(fn($query) => $query->where('status' , 'cancelled')),
            'delivered' => Tab::make()->query(fn($query) => $query->where('status' , 'delivered')),
            'Paid' => Tab::make()->query(fn($query) => $query->where('payment_status' , 'paid')),
            'pending' => Tab::make()->query(fn($query) => $query->where('payment_status' , 'pending')),
            'Manual Payments' => Tab::make()->query(fn($query) => $query->where('payment_method','!=','cod')->Where('payment_method','!=','stripe')),
        ];
    }
}
