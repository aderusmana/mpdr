<?php

namespace App\Models\MPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MPDR\MpdrForm;

class MpdrCertificationRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'category',
        'other'
    ];
    
    public function form()
    {
        return $this->belongsTo(MpdrForm::class);
    }
}
