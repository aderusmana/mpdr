<?php

namespace App\Models\MPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MPDR extends Model
{
    use HasFactory;

    protected $table = 'mpdr';
    protected $guarded = ['id'];
}
