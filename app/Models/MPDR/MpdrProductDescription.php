<?php

namespace App\Models\MPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MPDR\MpdrForm;

class MpdrProductDescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'product_description',
        'usage_description',
        'storage_temperature',
        'delivery_temperature'
    ];
    
    public function form()
    {
        return $this->belongsTo(MpdrForm::class);
    }
}
