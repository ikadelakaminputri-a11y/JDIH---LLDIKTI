<?php

namespace App\Filament\Resources\Regulations\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\IconColumn;


class VersionsRelationManager extends RelationManager
{
    protected static string $relationship = 'versions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('file_path')
                    ->label('File PDF')
                    ->disk('public')
                    ->directory('regulations')
                    ->acceptedFileTypes(['application/pdf'])
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->label('Catatan (opsional)')
                    ->columnSpanFull(),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('version_number')
                    ->label('Versi'),
                TextEntry::make('file_size')
                    ->label('Ukuran File')
                    ->formatStateUsing(fn($state) => number_format($state / 1024, 1) . ' KB'),
                TextEntry::make('uploader.name')
                    ->label('Diupload Oleh'),
                TextEntry::make('download_count')
                    ->label('Jumlah Unduhan'),
                TextEntry::make('created_at')
                    ->label('Tanggal Upload')
                    ->dateTime('d M Y, H:i'),
                TextEntry::make('notes')
                    ->label('Catatan')
                    ->columnSpanFull()
                    ->placeholder('Tidak ada catatan'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('version_number')
            ->columns([
                TextColumn::make('version_number')
                    ->label('Versi')
                    ->sortable(),
                TextColumn::make('uploader.name')
                    ->label('Diupload Oleh'),
                TextColumn::make('download_count')
                    ->label('Diunduh')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Upload')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Upload Versi Baru')
                    ->mutateFormDataUsing(function (array $data): array {
                        $regulation = $this->getOwnerRecord();
                        $lastVersion = $regulation->versions()->max('version_number') ?? 0;
                        $data['version_number'] = $lastVersion + 1;
                        $data['uploaded_by']    = Auth::id();
                        $data['download_count'] = 0;
                        $data['is_active']      = true;
                        $data['file_size']      = Storage::disk('public')->size($data['file_path']);
                        return $data;
                    })
                    ->successNotificationTitle('Versi berhasil diunggah'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->label('Detail'),
                    Action::make('view_pdf')
                        ->label('Lihat PDF')
                        ->icon('heroicon-o-eye')
                        ->url(fn($record) => asset('storage/' . $record->file_path))
                        ->openUrlInNewTab(),
                    DeleteAction::make()
                        ->label('Hapus')
                        ->modalHeading('Hapus Versi')
                        ->modalDescription('Apakah anda yakin ingin menghapus versi ini?')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->successNotificationTitle('Versi berhasil dihapus'),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
