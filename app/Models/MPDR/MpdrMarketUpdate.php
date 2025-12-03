<?php

namespace App\Models\MPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MPDR\MpdrForm;

class MpdrMarketUpdate extends Model
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
        return $this->belongsTo(MpdrForm::class);
    }
}
