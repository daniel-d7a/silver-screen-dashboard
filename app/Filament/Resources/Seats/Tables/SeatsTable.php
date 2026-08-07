<?php

namespace App\Filament\Resources\Seats\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SeatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('showtime_id')
                    ->label('Showtime')
                    ->toggleable(),
                TextColumn::make('seat_label')
                    ->label('Seat')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'available',
                        'warning' => 'held',
                        'danger' => 'booked',
                    ]),
                TextColumn::make('held_by')
                    ->label('Held by')
                    ->searchable(),
                TextColumn::make('held_until')
                    ->label('Held until')
                    ->dateTime()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(['available' => 'Available', 'held' => 'Held', 'booked' => 'Booked']),
                SelectFilter::make('showtime_id')
                    ->label('Showtime')
                    ->relationship('showtime', 'id'),
            ])
            ->recordActions([])
            ->toolbarActions([])
            ->paginated([25, 50, 100]);
    }
}