<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class PopularCategoriesChart extends ChartWidget
{
    protected ?string $heading = 'Kategori Paling Banyak Diunduh';
    protected int|string|array $columnSpan = 1;
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $categories = Category::query()
            ->whereHas('regulations.activeVersion')
            ->with(['regulations.activeVersion'])
            ->get()
            ->map(function ($category) {
                $totalDownloads = $category->regulations
                    ->sum(
                        fn($regulation) =>
                        $regulation->activeVersion?->download_count ?? 0
                    );

                return [
                    'name' => $category->name,
                    'downloads' => $totalDownloads,
                ];
            })
            ->sortByDesc('downloads')
            ->take(5)
            ->values();

        return [
            'datasets' => [
                [
                    'label' => 'Total Download',
                    'data' => $categories->pluck('downloads')->toArray(),
                    'backgroundColor' => [
                        '#3B82F6',
                        '#10B981',
                        '#F59E0B',
                        '#8B5CF6',
                        '#EF4444',
                    ],
                    'borderRadius' => 8,
                ],
            ],

            'labels' => $categories->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
                'x' => [
                    'ticks' => [
                        'autoSkip' => false,
                        'maxRotation' => 0,
                        'minRotation' => 0,
                    ],
                ],
            ],
        ];
    }
}
