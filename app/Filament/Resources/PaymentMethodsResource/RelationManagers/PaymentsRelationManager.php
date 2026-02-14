<?php

namespace App\Filament\Resources\PaymentMethodsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'Payments';
    protected static string $sort = "2" ;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->label('Order number')
                    ->relationship('order', 'id') // أو ممكن تختار حقل أوضح زي رقم الطلب أو اسم العميل
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('order.name')
                    ->label('Order')
                    ->relationship(
                        name: 'order',
                        titleAttribute: 'id', // ده الأساس
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) =>
                        'Order #' . $record->id . ' - ' . optional($record->user)->name
                    )
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('payment_method_id')
                    ->label('Payment Method')
                    ->relationship('paymentMethod', 'name') // خلي عندك علاقة في الـ model
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->prefix('$') // أو أي عملة
                    ->required(),

                Forms\Components\MarkdownEditor::make('data')
                    ->label('Payment Data (Extra Info)')
                    ->fileAttachmentsDirectory('payments')
                    ->columnSpanFull(),
            ]);

    }
    public static function getRecordTitle(Model $record): string
{
    // مثلاً تخليه Order number
    return 'Order #' . $record->order_id . ' - ' . optional($record->order->user)->name;
}
// protected static function getEloquentQuery(): Builder
// {
//     return parent::getEloquentQuery()
//         ->select('id', 'order_id', 'payment_method_id'); // تأكد إن فيه id
// }
    public function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('data')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('order.id')
                ->label('Order #')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('order.user.name')
                ->label('Customer')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('paymentMethod.name')
                ->label('Payment Method')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('amount')
                ->label('Amount')
                ->money('usd') // ممكن تغيرها حسب العملة
                ->sortable(),

            Tables\Columns\TextColumn::make('data')
                ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state) : $state)
                ->limit(50)
                ->toggleable(isToggledHiddenByDefault: true)
                ->tooltip(fn ($state) => $state), // يظهر كامل الـ data في tooltip عند الـ hover

            Tables\Columns\TextColumn::make('created_at')
                ->label('Created At')
                ->dateTime('d M Y H:i')
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Updated At')
                ->dateTime('d M Y H:i')
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
