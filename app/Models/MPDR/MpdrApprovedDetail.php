<?php

namespace App\Models\MPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MPDR\MpdrForm;
use App\Models\User;

class MpdrApprovedDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'approver_nik',
        'approver_name',
        'approver_position',
        'level',
        'status',
        'comment',
        'token',
        'approved_date'
    ];

    protected $casts = [
        'approved_date' => 'datetime',
    ];

    public function getApproverPositionAttribute($value = null)
    {
        if (!empty($value)) {
            return $value;
        }

        if ($this->relationLoaded('user') && $this->user) {
            return $this->user->position?->name;
        }

        if ($this->approver_nik) {
            return User::with('position')->where('nik', $this->approver_nik)->first()?->position?->name;
        }

        return null;
    }
    
    public function form()
    {
        return $this->belongsTo(MpdrForm::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'approver_nik', 'nik');
    }
}
