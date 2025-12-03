<?php

namespace App\Models\Master;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Position extends Model
{
    use HasFactory;

    protected $table = 'positions';
    protected $guarded = [''];

    public function user()
    {
        return $this->hasMany(User::class, 'position_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    protected static function booted()
    {
        static::saving(function ($position) {
            $position->slug = Str::slug($position->name);
        });
    }
}
