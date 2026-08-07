<?php

namespace App\Filament\Resources\Films\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FilmsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('poster_url')
                    ->label('Poster')
                    ->height(80)
                    ->circular(),
                TextColumn::make('tmdb_id')
                    ->label('TMDB ID')
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rating')
                    ->sortable(),
                TextColumn::make('runtime')
                    ->label('Runtime')
                    ->suffix(' min')
                    ->sortable(),
                TextColumn::make('release_date')
                    ->date()
                    ->sortable(),
                TagsColumn::make('genres'),
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