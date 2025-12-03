<?php

namespace App\Models\MPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MpdrApprover extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_nik',
        'approver_nik',
        'approver_name',
        'approver_status'
    ];
}
