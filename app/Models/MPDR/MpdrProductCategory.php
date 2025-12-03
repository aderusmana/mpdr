<?php

namespace App\Models\MPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MPDR\MpdrForm;

class MpdrProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'name',
        'category',
        'other'
    ];
    
    public function form()
    {
        return $this->belongsTo(MpdrForm::class);
    }
}
