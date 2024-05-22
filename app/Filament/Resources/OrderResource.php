<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Waste;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Order::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('client.full_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->numeric()
                    ->alignRight(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric(2)
                    ->alignRight(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    // Action::make('Add Item')
                    //     ->icon('heroicon-o-plus')
                    //     ->slideOver()
                    //     ->form([
                    //         Group::make()
                    //             ->schema(
                    //                 [
                    //                     // Select::make('waste_id')
                    //                     //     ->relationship('waste', 'type')
                    //                     //     ->createOptionForm(Waste::getForm())
                    //                     //     ->editOptionForm(Waste::getForm())
                    //                     //     ->preload()
                    //                     //     ->searchable()
                    //                     //     ->live()
                    //                     //     ->required(),
                    //                     TextInput::make('origin')
                    //                         ->maxLength(255),
                    //                     TextInput::make('source')
                    //                         ->maxLength(255),
                    //                     Group::make()
                    //                         ->columns(2)
                    //                         ->schema([
                    //                             TextInput::make('weight')
                    //                                 ->required()
                    //                                 ->numeric(),
                    //                             Select::make('price_id')
                    //                                 ->relationship('price', 'price')
                    //                                 // ->disabled()
                    //                                 ->required(),
                    //                         ]),
                    //                 ]
                    //                 // OrderItem::getForm()
                    //             )
                    //     ]),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()->slideOver(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Order Information')
                    ->columns(['xl' => 5])
                    ->schema([
                        TextEntry::make('id'),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('client.full_name'),
                        TextEntry::make('client.phone'),
                        TextEntry::make('date')
                            ->date(),
                        TextEntry::make('total')
                            ->money('GHS')
                            ->weight(FontWeight::Bold),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            // 'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            // 'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
