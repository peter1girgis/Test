<?php

namespace App\Filament\Resources\PaymentMethodsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GetewayCredentialsRelationManager extends RelationManager
{
    protected static string $relationship = 'getewayCredentials';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Add Credential')->schema([
                    Forms\Components\TextInput::make('key')
                    ->label('Credential Key')
                    ->required()
                    ->columnSpanFull(),

                    Forms\Components\Textarea::make('value')
                        ->label('Credential Value')
                        ->required()
                        ->columnSpanFull(),
                ]),
            ]);
    }
    // public static function getTitle(Model $ownerRecord, string $pageClass): string
    // {
    //     return 'Add New Credential';
    // }

    // public static function getCreateTitle(Model $ownerRecord): string
    // {
    //     return 'Create Credential For ' . $ownerRecord->name;
    // }

    // public static function getEditTitle(Model $ownerRecord, Model $record): string
    // {
    //     return 'Edit Credential: ' . $record->key;
    // }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('key')
            ->columns([
                Tables\Columns\TextColumn::make('key'),
                Tables\Columns\TextColumn::make('value')->limit(50)->tooltip(fn($record) => $record->value),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Add Credential'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->type === 'automatic';
    }
}
