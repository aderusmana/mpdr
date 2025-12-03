<?php

namespace App\Models\PREMPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\PREMPDR\PreMpdrForm;

class PreMpdrApprovedDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'approver',
        'name',
        'level',
        'status',
        'comment',
        'token',
        'approved_date'
    ];
    
    
    public function user()
    {
        return $this->belongsTo(User::class, 'approver', 'nik');
    }

    public function form()
    {
        return $this->belongsTo(PreMpdrForm::class);
    }
}
