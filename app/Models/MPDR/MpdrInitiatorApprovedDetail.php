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
    
    public function form()
    {
        return $this->belongsTo(MpdrForm::class);
    }
}
