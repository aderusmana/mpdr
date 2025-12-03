<?php

namespace App\Models\RS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RS extends Model
{
    use HasFactory;

    protected $table = 'rs';
    protected $guarded =['id'];

}
