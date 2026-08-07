<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['tmdb_movie_id', 'screen', 'starts_at', 'tier', 'price'])]
class Showtime extends Model
{
    use HasUuid;

    protected $table = 'showtimes';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'price' => 'float',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (Showtime $showtime) {
            $showtime->generateSeats();
        });
    }

    public function generateSeats(): void
    {
        // ponytail: fixed 48-seat layout (A-F, 1-8). Configurable layout only if a cinema ever differs.
        $seats = [];
        foreach (range('A', 'F') as $row) {
            foreach (range(1, 8) as $col) {
                $seats[] = [
                    'showtime_id' => $this->id,
                    'seat_label' => $row.$col,
                    'status' => 'available',
                ];
            }
        }
        Seat::query()->insert($seats);
    }

    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class, 'tmdb_movie_id', 'tmdb_id');
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }
}