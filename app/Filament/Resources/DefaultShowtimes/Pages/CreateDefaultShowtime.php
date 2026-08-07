<?php

namespace App\Filament\Resources\DefaultShowtimes\Pages;

use App\Filament\Resources\DefaultShowtimes\DefaultShowtimeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDefaultShowtime extends CreateRecord
{
    protected static string $resource = DefaultShowtimeResource::class;
}
