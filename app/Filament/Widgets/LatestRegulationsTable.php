<?php

namespace App\Filament\Widgets;

use App\Models\Regulation;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Str;

class LatestRegulationsTable extends BaseWidget
{
    protected static ?string $heading = 'Peraturan Terbaru';

    protected int|string|array $columnSpan = 2;

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                Regulation::query()
                    ->with(['category', 'activeVersion'])
                    ->latest()
                    ->limit(8)
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('Nomor')
                    ->searchable()
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->limit(29)
                    ->tooltip(fn($record) => $record->title)
                    ->wrap(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Peraturan Menteri' => 'info',
                        'SK Rektor' => 'success',
                        'Surat Edaran' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'published' => 'Published',
                        'unpublished' => 'Unpublished',
                        default => Str::headline($state),
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'published' => 'success',
                        'unpublished' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('activeVersion.download_count')
                    ->label('Download')
                    ->numeric()
                    ->default(0),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ditambahkan')
                    ->since(),
            ]);
    }
}
