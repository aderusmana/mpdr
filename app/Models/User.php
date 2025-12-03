<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Master\Department;
use App\Models\Master\Position;
use App\Models\Master\Section;
use App\Models\PREMPDR\PreMpdrForm;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nik',
        'email',
        'password',
        'avatar',
        'department_id',
        'position_id',
        'manager_id',
        'section_id',
        'status',
        'atasan_nik',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function forms()
    {
        return $this->hasMany(PreMpdrForm::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function atasan()
    {
        return $this->belongsTo(User::class, 'atasan_nik', 'nik');
    }
}
