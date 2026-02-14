<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            stat::make('New Orders',Order::query()->where('status', 'new')->count())
                ->color('success')
                ->icon('heroicon-o-shopping-cart')
                ->description('Total new orders placed in the last 30 days'),
            stat::make('processing Orders',Order::query()->where('status', 'processing')->count())
                ->color('warning')
                ->icon('heroicon-o-arrow-path')
                ->description('Total orders currently being processed'),
                // stat::make('Cancelled Orders',Order::query()->where('status', 'cancelled')->count())
                // ->color('info')
                // ->icon('heroicon-o-x-circle')
                // ->description('Total orders currently being cancelled'),
            stat::make('shipped Orders',Order::query()->where('status', 'shipped')->count())
                ->color('info')
                ->icon('heroicon-o-truck')
                ->description('Total orders currently being shipped'),
            stat::make('Average Price', Number::currency(Order::query()->avg('grand_total'))),

        ];
    }
}
