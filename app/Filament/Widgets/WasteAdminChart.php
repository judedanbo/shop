<?php

namespace App\Filament\Widgets;

use App\Models\Waste;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class WasteAdminChart extends ChartWidget
{
    protected static ?string $heading = 'Number of products';
    protected static ?int $sort = 3;
    protected function getData(): array
    {
        $data = Trend::model(Waste::class)
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
        return 'bar';
    }
}
