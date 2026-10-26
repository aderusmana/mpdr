<?php

namespace App\Models\MPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MPDR\MpdrForm;

class MpdrInitiatorApprovedDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'initiator_nik',
        'initiator_name',
        'status',
        'comment',
        'token',
        'approved_date'
    ];

    protected $casts = [
        'approved_date' => 'datetime',
    ];
    
    public function form()
    {
        return $this->belongsTo(MpdrForm::class);
    }
}
