<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\PaymentsRelationManager;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Price;
use App\Models\Waste;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
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
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\RawJs;

class OrderResource extends Resource
{
    protected $listeners = [
        'refreshOrders' => '$refresh',
    ];

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
                Tables\Columns\TextColumn::make('status')
                    ->label('Order Status')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client.full_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->numeric()
                    ->alignCenter(),
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
                    Action::make('Add Item')
                        ->icon('heroicon-o-plus')
                        ->slideOver()
                        ->form([
                            Repeater::make('items')
                                ->collapsible()
                                ->columns(3)
                                ->schema([
                                    Select::make('waste_id')
                                        ->label('Waste Type')
                                        ->options(Waste::whereHas('prices')->pluck('type', 'id')->toArray())
                                        ->preload()
                                        ->searchable()
                                        ->live()
                                        ->afterStateUpdated(function (Set $set, Get $get) {
                                            $set('price', Price::where('waste_id', $get('waste_id'))->latest()->first()->price);
                                            $set('price_id', Price::where('waste_id', $get('waste_id'))->latest()->first()->id);
                                        })
                                        ->columnSpanFull()
                                        ->required(),
                                    TextInput::make('origin')
                                        ->maxLength(255)
                                        ->columnSpanFull(),
                                    TextInput::make('source')
                                        ->maxLength(255)
                                        ->columnSpanFull(),
                                    TextInput::make('weight')
                                        ->type('number')
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (Set $set, Get $get) {
                                            if ($get('weight') && $get('waste_id')) {
                                                $set('total', $get('weight') * Price::where('waste_id', $get('waste_id'))->first()->price);
                                            }
                                        })
                                        ->required(),
                                    TextInput::make('price')
                                        ->numeric()

                                        ->prefix('GHS')
                                        // ->inputMode('decimal')
                                        ->disabled(),

                                    Hidden::make('price_id'),

                                    TextInput::make('total')
                                        ->numeric()
                                        ->mask(RawJs::make('$money($input)'))
                                        // ->stripCharacters(',')
                                        ->prefix('GHS')
                                        ->disabled(),
                                ])
                                ->itemLabel(function (array $state): ?string {
                                    return Waste::find($state['waste_id'])->type ?? null;
                                }),

                        ]),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->slideOver(),
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
            ItemsRelationManager::class,
            PaymentsRelationManager::class,
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
