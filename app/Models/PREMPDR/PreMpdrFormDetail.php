<?php

namespace App\Models\PREMPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PREMPDR\PreMpdrForm;

class PreMpdrFormDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'form_id',
        'rational_for_development',
        'target_launch'
    ];
    
    public function form()
    {
        return $this->belongsTo(PreMpdrForm::class);
    }
}
