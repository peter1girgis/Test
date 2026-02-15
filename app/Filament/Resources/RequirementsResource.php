<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequirementsResource\Pages;
use App\Filament\Resources\RequirementsResource\RelationManagers;
use App\Models\Requirements;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequirementsResource extends Resource
{
    protected static ?string $model = Requirements::class;
    protected static ?string $navigationGroup = 'Gateways';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('label')
                    ->required()
                    ->maxLength(255),
                Forms\Components\ToggleButtons::make('type')
                    ->inline()->default('text')
                    ->required()
                    ->options([
                        'phone' => 'Phone',
                        'file' => 'File',
                        'text' => 'TEXT',
                        'textarea' => 'Textarea',
                        'date' => 'Date',
                        'number' => 'Number',
                    ])->colors([
                        'phone' => 'primary',
                        'file' => 'info',
                        'text' => 'success',
                        'textarea' => 'warning',
                        'date' => 'info',
                        'number' => 'danger',
                    ])->icons([
                        'phone' => 'heroicon-o-phone',
                        'file' => 'heroicon-o-document',
                        'text' => 'heroicon-o-document-text',
                        'textarea' => 'heroicon-s-document-text',
                        'date' => 'heroicon-o-calendar',
                        'number' => 'heroicon-o-calculator',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable(),
                Tables\Columns\TextColumn::make('label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
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
                Tables\Actions\EditAction::make(),
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
        return Requirements::count() > 10 ? 'success' : 'danger';
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRequirements::route('/'),
            //'create' => Pages\CreateRequirements::route('/create'),
            //'edit' => Pages\EditRequirements::route('/{record}/edit'),
        ];
    }
}
