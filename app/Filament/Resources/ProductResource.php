<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 4;

    // Products form
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('product information')->schema([
                        Forms\Components\TextInput::make('name')
                        ->required()
                        ->live(onBlur:true)
                        ->afterStateUpdated(fn(string $operation ,$state,Set $set)=>$operation === 'create' || 'edit' ? $set('slug', Str::slug($state)):null)
                        ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                        ->required()
                        ->disabled()
                        ->dehydrated()
                        ->unique(Product::class,'slug',ignoreRecord:true)
                        ->maxLength(255),
                        Forms\Components\MarkdownEditor::make('description')
                        ->fileAttachmentsDirectory('products')
                        ->columnSpanFull(),

                    ])->columns(2),
                    Forms\Components\Section::make('Images')
                    ->schema([
                        FileUpload::make('images')
                        ->multiple()
                        ->directory('products')
                        ->maxFiles(5)
                        ->reorderable()
                    ]),

                ])->columnSpan(2),
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('proce')->schema([
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('$'),
                    ]),
                    Forms\Components\Section::make('Associations')->schema([
                        Forms\Components\Select::make('category_id')
                        ->required()->searchable()->preload()->relationship('category','name'),
                        Forms\Components\Select::make('brand_id')
                        ->required()->searchable()->preload()->relationship('brand','name')
                    ]),
                    Forms\Components\Section::make('status')->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->default(true),
                        Forms\Components\Toggle::make('is_featured')
                            ->required()
                            ->default(false),
                        Forms\Components\Toggle::make('in_stock')
                            ->default(true)
                            ->required(),
                        Forms\Components\Toggle::make('on_sale')
                            ->required()
                            ->default(false),
                    ]),

                ])->columnSpan(1)
            ])->columns(3);
    }

    // Products table
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->action(function($record,$column){
                        $name = $column->getName();
                        $record->update([
                            $name => !$record->$name
                        ]);
                    }),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->action(function($record,$column){
                        $name = $column->getName();
                        $record->update([
                            $name => !$record->$name
                        ]);
                    }),
                Tables\Columns\IconColumn::make('in_stock')
                    ->boolean()
                    ->action(function($record,$column){
                        $name = $column->getName();
                        $record->update([
                            $name => !$record->$name
                        ]);
                    }),
                Tables\Columns\IconColumn::make('on_sale')
                    ->boolean()
                    ->action(function($record,$column){
                        $name = $column->getName();
                        $record->update([
                            $name => !$record->$name
                        ]);
                    }),
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
                SelectFilter::make('category')->relationship('category','name')->preload()
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
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'success' : 'danger';
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
            'slug',
            'description',
            'category.name',
            'brand.name',
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
