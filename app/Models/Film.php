<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'tmdb_id',
    'title',
    'description',
    'poster_url',
    'release_date',
    'rating',
    'runtime',
    'genres',
    'trailer',
    'starring',
])]
class Film extends Model
{
    protected $table = 'films';

    public $incrementing = false;

    protected $primaryKey = 'tmdb_id';

    protected $keyType = 'int';

    protected function casts(): array
    {
        return [
            'release_date' => 'date',
            'rating' => 'float',
            'genres' => 'array',
        ];
    }

    public function showtimes(): HasMany
    {
        return $this->hasMany(Showtime::class, 'tmdb_movie_id', 'tmdb_id');
    }
}