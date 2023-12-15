<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameScore extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "game_id",
        "score",
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->id = Str::uuid();
            $model->date = now();
        });
    }
}
