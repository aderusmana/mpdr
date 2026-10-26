<?php

namespace App\Models\PREMPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\PREMPDR\PreMpdrForm;

class PreMpdrApprovedDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'approver',
        'name',
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

        if ($this->approver) {
            return User::with('position')->where('nik', $this->approver)->first()?->position?->name;
        }

        return null;
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'approver', 'nik');
    }

    public function form()
    {
        return $this->belongsTo(PreMpdrForm::class);
    }
}
