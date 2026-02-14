<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodsResource\Pages;
use App\Filament\Resources\PaymentMethodsResource\RelationManagers;
use App\Filament\Resources\PaymentMethodsResource\RelationManagers\GetewayCredentialsRelationManager;
use App\Filament\Resources\PaymentMethodsResource\RelationManagers\PaymentsRelationManager;
use App\Filament\Resources\PaymentMethodsResource\RelationManagers\RequirementsRelationManager;
use App\Models\payment_methods;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentMethodsResource extends Resource
{
    protected static ?string $model = payment_methods::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Gateways';

    public static function getPluralLabel(): string
{
    return 'Payment Methods'; // الجمع (اللي بيظهر في الـ aside)
}

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),

            Forms\Components\Select::make('type')
                ->options(['automatic'=>'Automatic','manual'=>'Manual'])
                ->required(),

            Forms\Components\MarkdownEditor::make('description')
                ->label('Payment Instructions (shown to users)')
                ->required()
                ->columnSpanFull(),
        ]);
    }



    public static function table(Table $table): Table
    {
        return $table->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\BadgeColumn::make('type')->colors([
                    'success' => 'automatic',
                    'warning' => 'manual',
                ]),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RequirementsRelationManager::class,
            GetewayCredentialsRelationManager::class,
            PaymentsRelationManager::class,
        ];
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethods::route('/create'),
            'edit' => Pages\EditPaymentMethods::route('/{record}/edit'),
        ];
    }
}
