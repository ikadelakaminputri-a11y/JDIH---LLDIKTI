<?php

namespace App\Filament\Resources\Regulations\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class RegulationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Peraturan')
                    ->formatStateUsing(fn($state) => ucwords(strtolower($state)))
                    ->limit(18)
                    ->searchable(),
                TextColumn::make('number')
                    ->label('Nomor')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->formatStateUsing(fn($state) => ucwords(strtolower($state)))
                    ->limit(15)
                    ->searchable(),
                TextColumn::make('publish_date')
                    ->label('Tanggal Terbit')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'published'   => 'success',
                        'unpublished' => 'gray',
                        default       => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->emptyStateHeading('Belum ada peraturan')
            ->emptyStateIcon('heroicon-o-document-text')
            ->filters([
                TrashedFilter::make()
                ->label('Menampilkan Data Berdasarkan')
                ->placeholder('Semua Peraturan')
                ->trueLabel('Semua Peraturan Termasuk yang Dihapus')
                ->falseLabel('Hanya Peraturan yang Dihapus'),
            ])
            ->recordUrl(null)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->label('Detail')
                        ->modalHeading('')
                        ->modalWidth('2xl')
                        ->infolist(
                            fn(Schema $schema): Schema => $schema
                                ->components([
                                    Grid::make(1)
                                        ->schema([
                                            TextEntry::make('title')
                                                ->label('Judul Peraturan')
                                                ->formatStateUsing(fn($state) => strtoupper($state))
                                                ->size('xl')
                                                ->columnSpanFull(),
                                            TextEntry::make('status')
                                                ->label('')
                                                ->badge()
                                                ->color(fn(string $state): string => match ($state) {
                                                    'published'   => 'success',
                                                    'unpublished' => 'gray',
                                                    default       => 'gray',
                                                })
                                                ->formatStateUsing(fn(string $state): string => match ($state) {
                                                    'published'   => 'Dipublikasikan',
                                                    'unpublished' => 'Tidak Dipublikasikan',
                                                    default       => $state,
                                                }),
                                        ]),
                                    Grid::make(2)
                                        ->schema([
                                            TextEntry::make('number')
                                                ->label('Nomor Peraturan')
                                                ->iconColor('primary'),
                                            TextEntry::make('year')
                                                ->label('Tahun')
                                                ->iconColor('primary'),
                                            TextEntry::make('category.name')
                                                ->label('Kategori')
                                                ->badge()
                                                ->color('warning')
                                                ->icon('heroicon-o-tag'),

                                            TextEntry::make('publish_date')
                                                ->label('Tanggal Terbit')
                                                ->date('d M Y')
                                                ->iconColor('info'),
                                        ]),
                                    Grid::make(2)
                                        ->schema([
                                            TextEntry::make('activeVersion.file_path')
                                                ->label('Dokumen PDF')
                                                ->formatStateUsing(fn($state) => $state
                                                    ? '↗ Buka PDF di Tab Baru'
                                                    : 'Tidak ada file tersedia')
                                                ->url(fn($record) => $record->activeVersion?->file_path
                                                    ? asset('storage/' . $record->activeVersion->file_path)
                                                    : null)
                                                ->openUrlInNewTab()
                                                ->color(fn($record) => $record->activeVersion?->file_path
                                                    ? 'primary'
                                                    : 'gray'),

                                            TextEntry::make('activeVersion.file_size')
                                                ->label('Ukuran File')
                                                ->badge()
                                                ->color('gray')
                                                ->formatStateUsing(fn($state) => $state
                                                    ? ($state < 1048576
                                                        ? round($state / 1024, 1) . ' KB'
                                                        : round($state / 1048576, 2) . ' MB')
                                                    : '—'),

                                            TextEntry::make('activeVersion.download_count')
                                                ->label('Total Unduhan')
                                                ->badge()
                                                ->color('success')
                                                ->suffix(' kali'),

                                            TextEntry::make('activeVersion.created_at')
                                                ->label('Versi Diupload')
                                                ->dateTime('d M Y, H:i'),
                                        ]),

                                    Grid::make(1)
                                        ->schema([
                                            TextEntry::make('outgoing_display')
                                                ->label('Peraturan Ini')
                                                ->state(function ($record): string {
                                                    $record->loadMissing('outgoingRelations.targetRegulation');
                                                    if ($record->outgoingRelations->isEmpty()) {
                                                        return '—';
                                                    }
                                                    return $record->outgoingRelations->map(function ($rel) {
                                                        $label = match ($rel->relation_type) {
                                                            'mengubah'         => 'Mengubah',
                                                            'mencabut'         => 'Mencabut',
                                                            'dicabut_sebagian' => 'Mencabut Sebagian',
                                                            default            => $rel->relation_type,
                                                        };
                                                        return "• {$label}: {$rel->targetRegulation->number} — {$rel->targetRegulation->title}";
                                                    })->implode("\n");
                                                }),
                                            TextEntry::make('incoming_display')
                                                ->label('Peraturan Lain Terhadap Ini')
                                                ->state(function ($record): string {
                                                    $record->loadMissing('incomingRelations.sourceRegulation');
                                                    if ($record->incomingRelations->isEmpty()) {
                                                        return '—';
                                                    }
                                                    return $record->incomingRelations->map(function ($rel) {
                                                        $label = match ($rel->relation_type) {
                                                            'mengubah'         => 'Diubah dengan',
                                                            'mencabut'         => 'Dicabut oleh',
                                                            'dicabut_sebagian' => 'Dicabut Sebagian oleh',
                                                            default            => $rel->relation_type,
                                                        };

                                                        return "• {$label}: {$rel->sourceRegulation->number} — {$rel->sourceRegulation->title}";
                                                    })->implode("\n");
                                                }),
                                        ]),
                                ])
                        ),
                    EditAction::make()
                        ->label('Edit'),
                    DeleteAction::make()
                        ->label('Hapus'),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
