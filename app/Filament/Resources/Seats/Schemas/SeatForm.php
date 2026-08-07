<?php

namespace App\Filament\Resources\Seats\Schemas;

use Filament\Schemas\Schema;

class SeatForm
{
    public static function configure(Schema $schema): Schema
    {
        // ponytail: read-only resource, no editable form needed.
        return $schema->components([]);
    }
}