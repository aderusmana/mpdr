<?php

namespace App\Models\PREMPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PreMpdrApprover extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_nik',
        'approver_nik',
        'approver_name',
        'approver_status',
        'level'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_nik', 'nik');
    }
}
