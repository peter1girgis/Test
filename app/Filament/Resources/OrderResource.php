<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\PaymentsRelationManager;
use App\Models\Order;
use App\Models\Product;
use Faker\Provider\ar_EG\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;
use function Livewire\on;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 5;
    // protected static ?string $recordTitleAttribute = 'user.name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Order Info')->schema([
                        Forms\Components\Select::make('user_id')->label('User')->relationship('user', 'name')
                            ->required()
                            ->preload()
                            ->searchable(),
                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'stripe' => '💳 Stripe',
                                'cod' => '💵 Cash on Delivery',
                            ])
                            // ->relationship('paymenyMethod','name')
                            // ->preload()
                            // ->searchable()
                            ,

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
                        // Forms\Components\Select::make('shipping_method')
                        //     ->label('Shipping Method')
                        //     ->options([
                        //         'frdex' => 'FedEx',
                        //         'dhl' => 'DHL',
                        //         'ups' => 'UPS',
                        //         'usps' => 'USPS',
                        //     ])
                        //     ->default('frdex'),
                        Forms\Components\TextArea::make('notes')
                            ->label('Order Notes')
                            ->maxLength(500)
                            ->placeholder('Any special instructions or notes for the order')->columnSpanFull()
                            ,
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
                                    return Number::currency($items, 'USD', 2, true, true, true, true);
                                }),
                                Forms\Components\Hidden::make('grand_total')
                                ->default(0),
                        ])->columnSpanFull(),
                    ])->columnSpanFull(),
                ])  ;
    }
    public static function getRecordTitle($record): ?string
    {
        return $record->user?->name ?? 'Order #' . $record->id;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                ->label('Customer')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grand_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
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
                    ->searchable()
                    ->limit(15)
                    ->tooltip(fn($record) => $record->notes),
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    // This method is used to get the number of orders for the navigation badge
    // It returns the count of orders in the database.
    // It is used in the navigation menu to show the number of orders.
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    // This method is used to get the color of the navigation badge
    // It returns 'success' if the count of orders is greater than 10, otherwise
    // it returns 'danger'.
    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'success' : 'danger';
    }
    // This method is used to get the relations of the Order resource
    // It returns an array of relation managers that are used to manage the relations of the Order
    // resource. In this case, it returns the AddressRelationManager which is used to manage
    // the addresses related to the order.
    public static function getRelations(): array
    {
        return [
            AddressRelationManager::class,
            PaymentsRelationManager::class,
        ];
    }
    // This method is used to get the globally searchable attributes of the Order resource
    // It returns an array of attributes that can be searched globally in the Filament admin panel
    // These attributes are used in the search bar to filter the orders based on these attributes.
    // In this case, it returns the user name, payment method, payment status, status, currency,
    // shipping method, and notes as the globally searchable attributes.
    public static function getGloballySearchableAttributes(): array
    {
        return [
            'user.name',
            'payment_method',
            'payment_status',
            'status',
            'currency',
            'shipping_method',
            'notes',
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

}
