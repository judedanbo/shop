<?php

namespace App\Filament\Widgets;

use App\Models\Waste;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAdminWastesOrders extends BaseWidget
{
    protected static ?string $heading = 'Wastes product';
    protected static ?int $sort = 5;
    public function table(Table $table): Table
    {
        return $table
            ->query(Waste::query())
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('prices.price')
                    ->numeric(2)
                    ->alignRight(),
            ]);
    }
}
