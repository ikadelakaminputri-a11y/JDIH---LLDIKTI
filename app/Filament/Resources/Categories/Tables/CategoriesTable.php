<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable(),
                TextColumn::make('regulations_count')
                    ->counts('regulations')
                    ->label('Jumlah Peraturan')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->emptyStateHeading('Belum ada kategori yang ditambahkan')
            ->emptyStateIcon('heroicon-o-document-text')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Kategori')
                    ->modalDescription('Apakah anda yakin ingin menghapus kategori ini?')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->successNotificationTitle('Kategori berhasil dihapus')
                    ->action(function ($record, DeleteAction $action) {
                        if ($record->regulations()->exists()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Kategori tidak dapat dihapus')
                                ->body('Kategori ini masih digunakan oleh peraturan lain.')
                                ->danger()
                                ->send();
                            $action->cancel();
                            return;
                        }
                        $record->delete();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
