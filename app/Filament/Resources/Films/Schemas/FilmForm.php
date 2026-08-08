<?php

namespace App\Filament\Resources\Films\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FilmForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('tmdb_id')
                            ->label('TMDB ID')
                            ->numeric()
                            ->required()
                            ->disabled(fn (string $operation): bool => $operation === 'edit'),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),
                            TextInput::make('poster_url')
                                ->label('Poster URL')
                                ->url()
                                ->placeholder('https://image.tmdb.org/...'),
                            TextInput::make('trailer')
                                ->url()
                                ->placeholder('https://www.youtube.com/watch?v=...'),
                                DatePicker::make('release_date'),
                                TextInput::make('rating')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10)
                                    ->step(0.1),
                                TextInput::make('runtime')
                                    ->numeric()
                                    ->label('Runtime (min)'),
                                TextInput::make('starring'),
                        TagsInput::make('genres')
                            ->label('Genres')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}