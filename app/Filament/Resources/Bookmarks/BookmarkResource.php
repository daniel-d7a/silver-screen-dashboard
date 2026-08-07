<?php

namespace App\Filament\Resources\Bookmarks;

use App\Filament\Resources\Bookmarks\Pages\ListBookmarks;
use App\Filament\Resources\Bookmarks\Schemas\BookmarkForm;
use App\Filament\Resources\Bookmarks\Tables\BookmarksTable;
use App\Models\Bookmark;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BookmarkResource extends Resource
{
    protected static ?string $model = Bookmark::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookmark;

    protected static string | \UnitEnum | null $navigationGroup = 'Users';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return BookmarkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookmarksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookmarks::route('/'),
        ];
    }
}
