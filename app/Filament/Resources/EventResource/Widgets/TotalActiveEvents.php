<?php

namespace App\Filament\Resources\EventResource\Widgets;

use App\Models\Event;
use Filament\Widgets\ChartWidget;

class TotalActiveEvents extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected static ?string $description = 'All Events';

    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $data = Event::query()
            ->published()
            ->get()
            ->groupBy('status')
            ->map(fn ($events) => $events->count())
            ->toArray();

        return [
            'labels' => array_keys($data),
            'datasets' => [
                [
                    'label' => 'Events',
                    'data' => array_values($data),
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
