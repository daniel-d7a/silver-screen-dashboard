<?php

namespace App\Filament\Resources\Profiles\Schemas;

use Filament\Schemas\Schema;

class ProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        // ponytail: read-only resource, no editable form needed.
        return $schema->components([]);
    }
}