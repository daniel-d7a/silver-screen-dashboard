<?php

namespace App\Filament\Resources\DefaultShowtimes\Pages;

use App\Filament\Resources\DefaultShowtimes\DefaultShowtimeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDefaultShowtime extends EditRecord
{
    protected static string $resource = DefaultShowtimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
