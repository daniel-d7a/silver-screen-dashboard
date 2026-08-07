<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['screen', 'starts_at', 'tier', 'price'])]
class DefaultShowtime extends Model
{
    use HasUuid;

    protected $table = 'default_showtimes';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime:H:i',
            'price' => 'float',
        ];
    }
}