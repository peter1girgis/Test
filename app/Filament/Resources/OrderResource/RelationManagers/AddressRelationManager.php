<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'address';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->maxLength(20)
                    ->tel(),
                Forms\Components\TextInput::make('city')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('state')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('zip_code')
                    ->required()
                    ->numeric()
                    ->maxLength(10),
                Forms\Components\TextArea::make('street_address')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('street_address')
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable()
                    ->limit(15)
                    ->tooltip(fn($record) => $record->first_name),
                // Tables\Columns\TextColumn::make('last_name')
                //     ->searchable()
                //     ->limit(15)
                //     ->tooltip(fn($record) => $record->last_name),
                Tables\Columns\TextColumn::make('fullname')

                    ->limit(15)
                    ->tooltip(fn($record) => $record->fullname),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->limit(15)
                    ->tooltip(fn($record) => $record->phone),
                Tables\Columns\TextColumn::make('city')
                    ->limit(12)
                    ->tooltip(fn($record) => $record->city),
                Tables\Columns\TextColumn::make('state')
                    ->limit(12)
                    ->tooltip(fn($record) => $record->state),
                Tables\Columns\TextColumn::make('zip_code')
                    ->limit(10)
                    ->tooltip(fn($record) => $record->zip_code),
                Tables\Columns\TextColumn::make('street_address')
                    ->limit(25)
                    ->tooltip(fn($record) => $record->street_address),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
