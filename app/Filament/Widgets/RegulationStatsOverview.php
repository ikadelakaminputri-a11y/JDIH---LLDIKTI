<?php

namespace App\Filament\Widgets;

use App\Models\Regulation;
use App\Models\RegulationVersion;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RegulationStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // =========================
        // TOTAL REGULASI
        // =========================
        $totalRegulations = Regulation::count();

        $lastMonthRegulations = Regulation::whereMonth(
            'created_at',
            now()->subMonth()->month
        )->count();

        $currentMonthRegulations = Regulation::whereMonth(
            'created_at',
            now()->month
        )->count();

        $regulationDifference =
            $currentMonthRegulations - $lastMonthRegulations;

        $regulationTrend = match (true) {
            $regulationDifference > 0 => 'up',
            $regulationDifference < 0 => 'down',
            default => 'stable',
        };

        $regulationDescription = match ($regulationTrend) {
            'up' => "+{$regulationDifference} peraturan dibanding bulan lalu",
            'down' => abs($regulationDifference) . " peraturan lebih sedikit",
            default => 'Tidak ada perubahan bulan ini',
        };

        $regulationIcon = match ($regulationTrend) {
            'up' => 'heroicon-m-arrow-trending-up',
            'down' => 'heroicon-m-arrow-trending-down',
            default => 'heroicon-m-minus',
        };

        $regulationColor = match ($regulationTrend) {
            'up' => 'success',
            'down' => 'danger',
            default => 'gray',
        };

        // =========================
        // PUBLISHED
        // =========================
        $publishedCount = Regulation::where('status', 'published')->count();
        $publishPercentage = $totalRegulations > 0
            ? round(($publishedCount / $totalRegulations) * 100)
            : 0;

        // =========================
        // UNPUBLISHED
        // =========================
        $unpublishedCount = Regulation::where('status', 'unpublished')->count();
        $unpublishPercentage = $totalRegulations > 0
            ? round(($unpublishedCount / $totalRegulations) * 100)
            : 0;

        // =========================
        // DOWNLOAD
        // =========================
        $totalDownloads = RegulationVersion::sum('download_count');

        $currentMonthDownloads = RegulationVersion::whereMonth(
            'created_at',
            now()->month
        )->sum('download_count');

        $lastMonthDownloads = RegulationVersion::whereMonth(
            'created_at',
            now()->subMonth()->month
        )->sum('download_count');

        // Hindari division by zero
        if ($lastMonthDownloads > 0) {
            $downloadPercentage = round(
                (($currentMonthDownloads - $lastMonthDownloads) / $lastMonthDownloads) * 100
            );
        } else {
            $downloadPercentage = 100;
        }

        // Tentukan trend
        $downloadTrend = match (true) {
            $currentMonthDownloads > $lastMonthDownloads => 'up',
            $currentMonthDownloads < $lastMonthDownloads => 'down',
            default => 'stable',
        };

        // Text description
        $downloadDescription = match ($downloadTrend) {
            'up' => "{$downloadPercentage}% naik dari bulan lalu",
            'down' => abs($downloadPercentage) . "% turun dari bulan lalu",
            default => 'Stabil dari bulan lalu',
        };

        // Icon
        $downloadIcon = match ($downloadTrend) {
            'up' => 'heroicon-m-arrow-trending-up',
            'down' => 'heroicon-m-arrow-trending-down',
            default => 'heroicon-m-minus',
        };

        // Color
        $downloadColor = match ($downloadTrend) {
            'up' => 'success',
            'down' => 'danger',
            default => 'gray',
        };

        // =========================
        // SPARKLINE DATA
        // =========================
        $sparklineData = collect(range(6, 0))
            ->map(function ($day) {
                return Regulation::whereDate(
                    'created_at',
                    Carbon::today()->subDays($day)
                )->count();
            })
            ->toArray();

        return [
            Stat::make('Total Peraturan', number_format($totalRegulations))
                ->description($regulationDescription)
                ->descriptionIcon(
                    $regulationIcon,
                    IconPosition::Before
                )
                ->chart($sparklineData)
                ->color($regulationColor)
                ->icon('heroicon-o-document-text'),

            Stat::make('Dipublikasikan', number_format($publishedCount))
                ->description("{$publishPercentage}% total peraturan")
                ->descriptionIcon(
                    'heroicon-m-check-circle',
                    IconPosition::Before
                )
                ->chart($sparklineData)
                ->color('success')
                ->icon('heroicon-o-check-badge'),

            Stat::make('Unpublished', number_format($unpublishedCount))
                ->description("{$unpublishPercentage}% masih draft")
                ->descriptionIcon(
                    'heroicon-m-clock',
                    IconPosition::Before
                )
                ->chart($sparklineData)
                ->color('warning')
                ->icon('heroicon-o-clock'),

            Stat::make('Total Download', number_format($totalDownloads))
                ->description($downloadDescription)
                ->descriptionIcon(
                    $downloadIcon,
                    IconPosition::Before
                )
                ->chart($sparklineData)
                ->color($downloadColor)
                ->icon('heroicon-o-arrow-down-tray'),   
        ];
    }
}
