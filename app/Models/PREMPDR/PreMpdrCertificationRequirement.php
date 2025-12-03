<?php

namespace App\Models\PREMPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PREMPDR\PreMpdrForm;

class PreMpdrCertificationRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'category',
        'other'
    ];
    
    public function form()
    {
        return $this->belongsTo(PreMpdrForm::class);
    }
}
