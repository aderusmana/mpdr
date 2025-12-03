<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Level extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function position()
    {
        return $this->hasMany(Position::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($level) {
            $level->slug = Str::slug($level->name);
        });
    }
}
