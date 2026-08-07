<?php

namespace App\Filament\Resources\Profiles\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProfilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('role')
                    ->badge()
                    ->colors([
                        'warning' => 'admin',
                        'primary' => 'user',
                    ]),
                TextColumn::make('last_login')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options(['user' => 'User', 'admin' => 'Admin']),
            ])
            ->recordActions([])
            ->toolbarActions([])
            ->paginated([10, 25, 50]);
    }
}