<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAdminClientsOrders extends BaseWidget

{
    protected static ?string $heading = 'Latest Clients';
    protected static ?int $sort = 4;
    public function table(Table $table): Table
    {
        return $table
            ->query(Client::query())
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('full_name'),
                Tables\Columns\TextColumn::make('phone'),
            ]);
    }
}
