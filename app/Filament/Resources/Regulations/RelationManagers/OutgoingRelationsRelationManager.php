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

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('target_regulation_id')
                    ->label('Regulasi Tujuan')
                    ->options(
                        Regulation::query()
                            ->where('id', '!=', $this->getOwnerRecord()->id)
                            ->get()
                            ->mapWithKeys(fn($r) => [$r->id => "{$r->number} — {$r->title}"])
                    )
                    ->searchable()
                    ->required(),
                Select::make('relation_type')
                    ->label('Tipe Relasi')
                    ->options([
                        'mengubah'        => 'Mengubah',
                        'mencabut'        => 'Mencabut',
                        'dicabut_sebagian' => 'Mencabut Sebagian',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('targetRegulation.number')
                    ->label('Nomor Regulasi')
                    ->searchable(),
                TextColumn::make('targetRegulation.title')
                    ->label('Judul Regulasi')
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
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
