<?php

namespace App\Filament\Resources\Films\Actions;

use App\Models\DefaultShowtime;
use App\Models\Showtime;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;

class BulkCreateShowtimesAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Add showtimes')
            ->icon('heroicon-o-plus')
            ->modalHeading('Add showtimes for a day')
            ->form([
                DatePicker::make('date')
                    ->label('Date')
                    ->required(),
                CheckboxList::make('default_showtime_ids')
                    ->label('Default showtimes')
                    ->options(
                        fn () => DefaultShowtime::query()
                            ->orderBy('screen')
                            ->orderBy('starts_at')
                            ->get()
                            ->mapWithKeys(fn (DefaultShowtime $d) => [
                                $d->id => $d->screen.' @ '.$d->starts_at->format('H:i').' ('.$d->tier.')',
                            ]),
                    )
                    ->columns(1)
                    ->required(),
            ])
            ->action(function (array $data): void {
                $film = $this->getRecord();
                $date = Carbon::parse($data['date']);

                $selectedDefaults = DefaultShowtime::query()
                    ->whereIn('id', $data['default_showtime_ids'])
                    ->get();

                foreach ($selectedDefaults as $default) {
                    Showtime::create([
                        'tmdb_movie_id' => $film->tmdb_id,
                        'screen' => $default->screen,
                        'starts_at' => $date->copy()->setTimeFromTimeString($default->starts_at->format('H:i')),
                        'tier' => $default->tier,
                        'price' => $default->price,
                    ]);
                }

                Notification::make()
                    ->title(count($selectedDefaults).' showtime(s) created')
                    ->success()
                    ->send();
            });
    }
}