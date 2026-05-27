<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class RegulationCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Peraturan per Kategori';

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected ?string $maxHeight = '260px';

    protected function getData(): array
    {
        $categories = Category::query()
            ->whereHas('regulations')
            ->withCount('regulations')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Peraturan',
                    'data' => $categories
                        ->pluck('regulations_count')
                        ->toArray(),

                    'backgroundColor' => [
                        '#3B82F6',
                        '#10B981',
                        '#F59E0B',
                        '#EF4444',
                        '#8B5CF6',
                        '#06B6D4',
                        '#84CC16',
                        '#F97316',
                    ],
                ],
            ],

            'labels' => $categories
                ->pluck('name')
                ->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}