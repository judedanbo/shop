<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ClientAdminChart extends ChartWidget
{
    protected static ?string $heading = 'Client Chart';
    protected static string $color = 'success';
    protected static ?int $sort = 2;
    protected function getData(): array
    {
        $data = Trend::model(Client::class)
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Clients posts',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
