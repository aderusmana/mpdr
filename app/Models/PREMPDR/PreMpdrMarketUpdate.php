<?php

namespace App\Models\PREMPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PREMPDR\PreMpdrForm;

class PreMpdrMarketUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'potential_volume',
        'expected_margin',
        'price_estimate'
    ];
    
    public function form()
    {
        return $this->belongsTo(PreMpdrForm::class);
    }
}
