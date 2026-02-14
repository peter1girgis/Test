<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\payments;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Order Chart';

    protected static ?int $sort = 1;

    protected static string $color = 'warning';

    protected function getData(): array
    {
        $filter = $this->filter; // قيمة الفلتر المُختار
        $start = now();
        $end = now();

        switch ($filter) {
            case 'today':
                $start = now()->startOfDay();
                $end = now()->endOfDay();
                break;
            case 'week':
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                break;
            case 'month':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                break;
            case 'year':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                break;
            default:
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                break;
        }

        $data = Trend::model(Order::class)
            ->between(
                start: $start,
                end: $end,
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    protected function getFilters(): ?array
{
    return [
        'today' => 'Today',
        'week' => 'Last week',
        'month' => 'Last month',
        'year' => 'This year',
    ];
}
}
