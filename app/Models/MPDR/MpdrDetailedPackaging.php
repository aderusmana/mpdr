<?php

namespace App\Models\MPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MPDR\MpdrForm;

class MpdrDetailedPackaging extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'weight',
        'category',
        'detail',
        'product_variation'
    ];
    
    public function form()
    {
        return $this->belongsTo(MpdrForm::class);
    }
}
