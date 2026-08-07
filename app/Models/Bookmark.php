<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'tmdb_id', 'created_at'])]
class Bookmark extends Model
{
    protected $table = 'bookmarks';

    protected $primaryKey = 'tmdb_id';

    public $incrementing = false;

    public $keyType = 'string';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }
}