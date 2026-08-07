<?php

namespace App\Filament\Resources\DefaultShowtimes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DefaultShowtimeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('screen')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Screen 1'),
                        TimePicker::make('starts_at')
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