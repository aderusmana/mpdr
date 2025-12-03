<?php

namespace App\Models\PREMPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PREMPDR\PreMpdrForm;

class PreMpdrProductDescription extends Model
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
        return $this->belongsTo(PreMpdrForm::class);
    }
}
