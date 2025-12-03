<?php

namespace App\Models\MPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MPDR\MpdrForm;

class MpdrRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'no',
        'revision',
        'date'
    ];
    
    public function form()
    {
        return $this->hasMany(MpdrForm::class);
    }
}
