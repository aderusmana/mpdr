<?php

namespace App\Models\MPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MPDR\MpdrForm;
use App\Models\User;

class MpdrApprovedDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'approver_nik',
        'approver_name',
        'level',
        'status',
        'comment',
        'token',
        'approved_date'
    ];
    
    public function form()
    {
        return $this->belongsTo(MpdrForm::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'approver_nik', 'nik');
    }
}
