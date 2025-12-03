<?php

namespace App\Models\PREMPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PREMPDR\PreMpdrForm;

class PreMpdrDetailedPackaging extends Model
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
        return $this->belongsTo(PreMpdrForm::class);
    }
}
