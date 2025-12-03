<?php

namespace App\Models\PREMPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PREMPDR\PreMpdrForm;

class PreMpdrRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'no',
        'revision',
        'date'
    ];
    
    public function form()
    {
        return $this->hasMany(PreMpdrForm::class);
    }
}
