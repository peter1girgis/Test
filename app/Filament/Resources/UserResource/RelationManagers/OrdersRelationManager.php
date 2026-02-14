<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Order Info')->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->required()
                            ->preload()
                            ->searchable(),

                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'stripe' => '💳 Stripe',
                                'cod' => '💵 Cash on Delivery',
                            ])
                            ->required(),
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'paid' => 'Paid',
                                'pending' => 'Pending',
                                'failed' => 'Failed',
                            ])
                            ->default('pending')
                            ->required(),
                        Forms\Components\ToggleButtons::make('status')
                            ->inline()->default('new')
                            ->required()
                            ->options([
                                'new' => 'New',
                                'processing' => 'Processing',
                                'shipped' => 'Shopping',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])->colors([
                                'new' => 'success',
                                'processing' => 'warning',
                                'shopping' => 'info',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                            ])->icons([
                                'new' => 'heroicon-o-sparkles',
                                'processing' => 'heroicon-o-arrow-path',
                                'shopping' => 'heroicon-o-truck',
                                'delivered' => 'heroicon-o-check-badge',
                                'cancelled' => 'heroicon-o-x-circle',
                            ]),
                        Forms\Components\Select::make('currency')
                            ->options([
                                'USD' => 'US Dollar',
                                'EUR' => 'Euro',
                                'EGP' => 'Egyptian Pound',
                            ])
                            ->default('USD')
                            ->required(),
                        Forms\Components\Select::make('shipping_amount')
                            ->label('Shipping Amount')
                            ->options([
                                'frdex' => 'FedEx',
                                'dhl' => 'DHL',
                                'ups' => 'UPS',
                                'usps' => 'USPS',
                            ])
                            ->default('frdex'),
                        Forms\Components\TextArea::make('notes')
                            ->label('Order Notes')
                            ->maxLength(500)
                            ->placeholder('Any special instructions or notes for the order')->columnSpanFull(),
                    ])->columns(2),
                    Forms\Components\Section::make('Order Details')->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->required()
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn($state ,Set $set)=> $set('unit_amount', Product::find($state)?->price ?? 0))
                                    ->afterStateUpdated(fn($state ,Set $set)=> $set('total_amount', Product::find($state)?->price ?? 0))
                                    ->columnSpan(4),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->minValue(1)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn($state ,Set $set , Get $get)=> $set('total_amount', $state* $get('unit_amount')))
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('unit_amount')
                                    ->label('Unit Price')
                                    ->numeric()
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('total_amount')
                                    ->label('Total amount')
                                    ->numeric()
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(3),


                            ])->columns(12)

                            ->createItemButtonLabel('Add Product'),
                            Forms\Components\Placeholder::make('grand_total_placeholder')
                                ->label('Grand Total')
                                ->content(function (Set $set, Get $get) {
                                    $items = 0;
                                    if(!$repeaters = $get('items')) {
                                        return $items;
                                    }
                                    foreach ($repeaters as $key => $repeater) {
                                        $items += $get('items')[$key]['total_amount'] ?? 0;

                                    }
                                    $set('grand_total', $items);
                                    return Number::currency($items, 'USD');
                                }),
                                Forms\Components\Hidden::make('grand_total')
                                ->default(0),
                        ])->columnSpanFull(),

                    // Forms\Components\TextInput::make('grand_total')
                    //     ->required()
                    //     ->numeric(),

                    // Forms\Components\TextInput::make('shipping_method')
                    //     ->maxLength(255)
                    //     ->default(null),


                    ])->columnSpanFull(),
                ])  ;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('grand_total')
            ->columns([
                Tables\Columns\TextColumn::make('grand_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_status')
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shipping_method')
                ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('notes')
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
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Action::make('orderDetails')
                        ->label('Order Details')
                        ->url(fn(Order $record):string=> OrderResource::getUrl('view',['record' => $record]))
                        ->icon('heroicon-o-eye')
                        ->color('info'),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
