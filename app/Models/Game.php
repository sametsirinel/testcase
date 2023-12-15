<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "date",
        "ended_at"
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(GameScore::class, "game_id");
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->id = Str::uuid();
            $model->date = now();

            GameScore::create([
                "game_id" => $model->id,
                "user_id" => $model->user_id,
                "score" => 0,
                "date" => $model->date
            ]);
        });
    }
}
