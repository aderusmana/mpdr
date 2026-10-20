<?php

namespace App\Models\MPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class MpdrApprover extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_nik',
        'approver_nik',
        'approver_name',
        'approver_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_nik', 'nik');
    }

    public function approverUser()
    {
        return $this->belongsTo(User::class, 'approver_nik', 'nik');
    }
}
