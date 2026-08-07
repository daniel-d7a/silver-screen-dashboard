<?php

namespace App\Filament\Resources\Showtimes\Tables;

use App\Models\Showtime;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShowtimesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('film.title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('screen')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->dateTime()
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
                TextColumn::make('seats_count')
                    ->label('Seats')
                    ->counts('seats')
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('tier')
                    ->options(['Gold' => 'Gold', 'Standard' => 'Standard']),
                \Filament\Tables\Filters\SelectFilter::make('screen')
                    ->options(fn () => Showtime::query()->distinct()->orderBy('screen')->pluck('screen', 'screen')),
            ])
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