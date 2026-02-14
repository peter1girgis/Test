<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Components\Tab;
use Filament\Tables\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;
    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->limit(12)
                    ->tooltip(fn($record) => $record->user->name)
                    ->searchable(),
                Tables\Columns\TextColumn::make('grand_total')
                    ->limit(12)
                    ->tooltip(fn($record) => $record->grand_total)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->limit(12)
                    ->tooltip(fn($record) => $record->payment_method)
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->limit(12)
                    ->tooltip(fn($record) => $record->payment_status)
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Columns\TextColumn::make('currency')
                    ->limit(12)
                    ->tooltip(fn($record) => $record->currency)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_amount')
                    ->limit(12)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip(fn($record) => $record->shipping_amount)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shipping_method')
                    ->limit(12)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip(fn($record) => $record->shipping_method)
                ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('notes')
                    ->limit(12)
                    ->tooltip(fn($record) => $record->notes)
                ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('view')
                    ->url(fn($record) => OrderResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-o-eye')
                    ->color('primary'),

            ]);
    }
    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'Paid' => Tab::make()->query(fn($query) => $query->where('payment_status' , 'paid')),
            'Pending' => Tab::make()->query(fn($query) => $query->where('payment_status' , 'pending')),

        ];
    }
}
