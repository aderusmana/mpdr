<?php

namespace App\Models\PREMPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreMpdr extends Model
{
    use HasFactory;

    protected $table = 'pre_mpdr';
    protected $guarded =['id'];

}
