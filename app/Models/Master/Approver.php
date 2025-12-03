<?php

namespace App\Models\Master;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approver extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'approver_nik', 'nik');
    }
}
