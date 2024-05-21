<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PriceResource\Pages;
use App\Filament\Resources\PriceResource\RelationManagers;
use App\Models\Price;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PriceResource extends Resource
{
    protected static ?string $model = Price::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                Price::getForm()
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('waste.type')
                    ->description(fn (Price $record) => $record->waste->description)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->numeric()
                    ->searchable()
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->slideOver(),
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
                Section::make('Waste Information')
                    // ->columns(2)
                    ->schema([
                        TextEntry::make('waste.type')
                            ->label('Waste Type'),
                        TextEntry::make('waste.description')
                            ->label(''),
                    ]),
                Section::make('Price Information')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('unit')
                            ->label('Unit of Sale'),
                        TextEntry::make('price')
                            ->money(fn (Price $record) => $record->currency)
                            ->label('Unit price'),
                        TextEntry::make('created_at')
                            ->since(),
                    ]),
            ]);

        // ->actions([
        //     Infolists\Actions\EditAction::make(),
        //     Infolists\Actions\DeleteAction::make(),
        //     Infolists\Actions\ForceDeleteAction::make(),
        //     Infolists\Actions\RestoreAction::make(),
        // ]);
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
            'index' => Pages\ListPrices::route('/'),
            // 'create' => Pages\CreatePrice::route('/create'),
            'view' => Pages\ViewPrice::route('/{record}'),
            // 'edit' => Pages\EditPrice::route('/{record}/edit'),
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
