<?php

namespace App\Models\MPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MPDR\MpdrForm;

class MpdrFormDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'form_id',
        'rational_for_development',
        'target_launch'
    ];
    
    public function form()
    {
        return $this->belongsTo(MpdrForm::class);
    }
}
