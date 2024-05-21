<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Waste;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsAdminOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Clients', Client::query()->count())
                ->description('Number of clients')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Orders', Order::query()->count())
                ->description('Number of waste items')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
            Stat::make('Waste Items', Waste::query()->count())
                ->description('Number of waste items')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
            // Stat::make('Order Amount', Order::all()->sum('total_cost'))
            //     ->description('3% increase')
            //     ->descriptionIcon('heroicon-m-arrow-trending-up')
            //     ->color('success'),
            Stat::make('Total Payment', Payment::query()->sum('amount'))
                ->description('3% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
