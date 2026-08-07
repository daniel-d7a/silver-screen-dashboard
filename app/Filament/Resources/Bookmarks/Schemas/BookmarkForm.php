<?php

namespace App\Filament\Resources\Bookmarks\Schemas;

use Filament\Schemas\Schema;

class BookmarkForm
{
    public static function configure(Schema $schema): Schema
    {
        // ponytail: read-only resource, no editable form needed.
        return $schema->components([]);
    }
}