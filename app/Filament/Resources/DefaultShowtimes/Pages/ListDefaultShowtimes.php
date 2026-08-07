<?php

namespace App\Filament\Resources\DefaultShowtimes\Pages;

use App\Filament\Resources\DefaultShowtimes\DefaultShowtimeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDefaultShowtimes extends ListRecords
{
    protected static string $resource = DefaultShowtimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
