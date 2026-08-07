<?php

namespace App\Filament\Resources\DefaultShowtimes;

use App\Filament\Resources\DefaultShowtimes\Pages\CreateDefaultShowtime;
use App\Filament\Resources\DefaultShowtimes\Pages\EditDefaultShowtime;
use App\Filament\Resources\DefaultShowtimes\Pages\ListDefaultShowtimes;
use App\Filament\Resources\DefaultShowtimes\Schemas\DefaultShowtimeForm;
use App\Filament\Resources\DefaultShowtimes\Tables\DefaultShowtimesTable;
use App\Models\DefaultShowtime;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DefaultShowtimeResource extends Resource
{
    protected static ?string $model = DefaultShowtime::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string | \UnitEnum | null $navigationGroup = 'Catalog';

    public static function form(Schema $schema): Schema
    {
        return DefaultShowtimeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DefaultShowtimesTable::configure($table);
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
            'index' => ListDefaultShowtimes::route('/'),
            'create' => CreateDefaultShowtime::route('/create'),
            'edit' => EditDefaultShowtime::route('/{record}/edit'),
        ];
    }
}
