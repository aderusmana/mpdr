<?php

namespace App\Models\MPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MPDR\MpdrForm;

class MpdrCompetitorProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'name',
        'size',
        'packaging',
        'price'
    ];
    
    public function form()
    {
        return $this->belongsTo(MpdrForm::class);
    }
}
