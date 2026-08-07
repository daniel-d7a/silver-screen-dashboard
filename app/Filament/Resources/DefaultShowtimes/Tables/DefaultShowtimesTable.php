<?php

namespace App\Filament\Resources\DefaultShowtimes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DefaultShowtimesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('screen')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('tier')
                    ->badge()
                    ->colors([
                        'success' => 'Standard',
                        'warning' => 'Gold',
                    ]),
                TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}