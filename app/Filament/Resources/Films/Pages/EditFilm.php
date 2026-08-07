<?php

namespace App\Filament\Resources\Films\Pages;

use App\Filament\Resources\Films\Actions\BulkCreateShowtimesAction;
use App\Filament\Resources\Films\FilmResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFilm extends EditRecord
{
    protected static string $resource = FilmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            BulkCreateShowtimesAction::make()
                ->record(fn (): ?\App\Models\Film => $this->getRecord()),
        ];
    }
}