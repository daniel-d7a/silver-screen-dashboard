<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(),
                TextColumn::make('user_id')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('showtime_id')
                    ->label('Showtime')
                    ->searchable(),
                TextColumn::make('seat_ids')
                    ->label('Seats')
                    ->placeholder('—'),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'danger' => 'cancelled',
                    ]),
                TextColumn::make('idempotency_key')
                    ->toggleable(),
                TextColumn::make('stripe_payment_intent_id')
                    ->label('Payment')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(['pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled']),
            ])
            ->recordActions([])
            ->toolbarActions([])
            ->bulkActions([])
            ->paginated([10, 25, 50]);
    }
}