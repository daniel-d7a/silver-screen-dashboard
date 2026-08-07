<?php

namespace App\Filament\Resources\Bookmarks\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BookmarksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('tmdb_id')
                    ->label('Film')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([])
            ->toolbarActions([])
            ->paginated([10, 25, 50]);
    }
}