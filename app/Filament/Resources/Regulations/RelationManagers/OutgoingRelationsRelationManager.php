<?php

namespace App\Filament\Resources\Regulations\RelationManagers;

use App\Models\Regulation;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OutgoingRelationsRelationManager extends RelationManager
{
    protected static string $relationship = 'outgoingRelations';
    protected static ?string $title = 'Relasi Peraturan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('target_regulation_id')
                    ->label('Peraturan Tujuan')
                    ->options(
                        Regulation::query()
                            ->where('id', '!=', $this->getOwnerRecord()->id)
                            ->get()
                            ->mapWithKeys(fn($r) => [$r->id => "{$r->number} — {$r->title}"])
                    )
                    ->placeholder('Pilih Peraturan Tujuan')
                    ->searchable()
                    ->required(),
                Select::make('relation_type')
                    ->label('Tipe Relasi')
                    ->options([
                        'mengubah'        => 'Mengubah',
                        'mencabut'        => 'Mencabut',
                        'dicabut_sebagian' => 'Mencabut Sebagian',
                    ])
                    ->placeholder('Pilih Tipe Relasi')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('targetRegulation.number')
                    ->label('Nomor Peraturan')
                    ->searchable(),
                TextColumn::make('targetRegulation.title')
                    ->label('Judul Peraturan')
                    ->formatStateUsing(fn($state) => ucwords(strtolower($state)))
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('relation_type')
                    ->label('Tipe Relasi')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'mengubah'         => 'Mengubah',
                        'mencabut'         => 'Mencabut',
                        'dicabut_sebagian' => 'Mencabut Sebagian',
                        default            => $state,
                    })
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'mengubah'         => 'warning',
                        'mencabut'         => 'danger',
                        'dicabut_sebagian' => 'info',
                        default            => 'gray',
                    }),
            ])
            ->emptyStateHeading('Belum ada relasi peraturan')
            ->emptyStateDescription('Tambahkan relasi peraturan untuk melihat daftar hubungan antar peraturan.')
            ->emptyStateIcon('heroicon-o-link')
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Relasi')
                    ->successNotificationTitle('Relasi berhasil ditambahkan'),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Relasi')
                    ->modalDescription('Apakah anda yakin ingin menghapus relasi ini?')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->successNotificationTitle('Relasi berhasil dihapus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
