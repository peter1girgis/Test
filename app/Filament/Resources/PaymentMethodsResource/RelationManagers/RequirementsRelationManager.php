<?php

namespace App\Filament\Resources\PaymentMethodsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Components\Tab;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequirementsRelationManager extends RelationManager
{
    protected static string $relationship = 'requirements'; // من الموديل
    protected static ?string $recordTitleAttribute = 'label';

    public function form(Form $form): Form
    {
        return $form->schema([
            // Pivot fields:
            Forms\Components\Select::make('requirement_id')
                ->label('Requirement')
                ->options(\App\Models\requirements::query()->pluck('label', 'id'))
                ->searchable()
                ->disabled()
                ->dehydrated()
                ->required(),

            // Pivot fields:
            Forms\Components\Toggle::make('is_required')
                ->label('Required')
                ->default(true),

            Forms\Components\Select::make('width')
                ->label('Width')
                ->options([
                    'full' => 'Full',
                    '1/2'  => 'Half',
                    '1/3'  => 'One Third',
                ])
                ->default('full'),

            Forms\Components\MarkdownEditor::make('description')
                ->label('Description')
                ->columnSpanFull(),
                ]);
    }
    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'required' => Tab::make()->query(fn($query) => $query->where('is_required' , 1)),
            'nullable' => Tab::make()->query(fn($query) => $query->where('is_required' , 0)),

        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                Tables\Columns\TextColumn::make('label')->label('Requirement')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('key')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('pivot.is_required')->boolean()->label('Required'),
                Tables\Columns\TextColumn::make('pivot.width')->label('Width'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // ->action(function ($record, $column) {
                //     $name = str_replace('pivot.', '', $column->getName());

                //     $record->paymentMethods()->updateExistingPivot(
                //         $record->pivot->requirement_id,
                //         [$name => !$record->pivot->$name]
                //     );
                // })

            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Requirement')
                    ->form([
                        Forms\Components\Select::make('requirement_id')
                            ->label('Requirement')
                            ->options(\App\Models\Requirements::pluck('label', 'id'))
                            ->searchable()
                            ->required(),

                        Forms\Components\Toggle::make('is_required')
                            ->label('Required')
                            ->default(true),

                        Forms\Components\Select::make('width')
                            ->label('Width')
                            ->options([
                                '1/2'  => 'Half',
                                '1/3'  => 'One Third',
                                'full' => 'Full Width',
                            ])
                            ->default('full'),

                        Forms\Components\MarkdownEditor::make('description')
                            ->label('Description')
                            ->fileAttachmentsDirectory('products')
                            ->columnSpanFull(),
                    ])
                    ->action(function (array $data) {
                        $paymentMethod = $this->getOwnerRecord(); // الـ owner = PaymentMethod

                        $paymentMethod->requirements()->syncWithoutDetaching([
                            $data['requirement_id'] => [
                                'is_required' => $data['is_required'] ?? false,
                                'width'       => $data['width'] ?? null,
                                'description' => $data['description'] ?? null,
                            ],
                        ]);
                    }),
            ])


            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),   // يعدّل pivot fields
                    Tables\Actions\DetachAction::make(),
                ]),

            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
    public function getpropertykey(): string
    {
        return 'requirements';
    }
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
        {
            // يظهر فقط لو type = manual
            return $ownerRecord->type === 'manual';
        }

}
