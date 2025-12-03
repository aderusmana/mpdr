<?php

namespace App\Models\Master;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';
    protected $guarded = ['id'];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function departmentHead()
   {
       return $this->belongsTo(User::class, 'dep_head_id');
   }
}
