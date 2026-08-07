<?php

namespace App\Filament\Resources\Showtimes\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ShowtimeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        Select::make('tmdb_movie_id')
                            ->label('Film')
                            ->relationship('film', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('screen')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Screen 1'),
                        DateTimePicker::make('starts_at')
                            ->required(),
                        Select::make('tier')
                            ->options(['Gold' => 'Gold', 'Standard' => 'Standard'])
                            ->default('Standard')
                            ->required(),
                        TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01),
                    ]),
            ]);
    }
}