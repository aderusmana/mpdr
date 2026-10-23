<?php

namespace App\Models\PREMPDR;

use App\Models\MPDR\MpdrForm;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PREMPDR\PreMpdrFormDetail;
use App\Models\PREMPDR\PreMpdrProductCategory;
use App\Models\PREMPDR\PreMpdrChannel;
use App\Models\PREMPDR\PreMpdrProductDescription;
use App\Models\PREMPDR\PreMpdrCertificationRequirement;
use App\Models\PREMPDR\PreMpdrCompetitorProduct;
use App\Models\PREMPDR\PreMpdrDetailedPackaging;
use App\Models\PREMPDR\PreMpdrMarketUpdate;
use App\Models\PREMPDR\PreMpdrApprover;
use App\Models\PREMPDR\PreMpdrApprovedDetail;
use App\Models\PREMPDR\PreMpdrRevision;
use App\Models\User;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class PreMpdrForm extends Model
{
    use HasFactory;

    protected $table = 'pre_mpdr_forms';

    protected $fillable = [
        'no',
        'user_id',
        'revision_id',
        'project_name',
        'brand_name',
        'level_priority',
        'status',
        'route_to',
        'last_approver_direct_approval',
    ];

    protected $casts = [
        'last_approver_direct_approval' => 'boolean',
    ];

    public function lastApproverDetail()
    {
        return $this->approvedDetail()->orderBy('level', 'desc')->first();
    }

    public function isLastApproverEligibleForApproval(): bool
    {
        $approvedDetails = $this->approvedDetail;
        if (!$approvedDetails || $approvedDetails->isEmpty()) {
            return false;
        }

        $lastDetail = $approvedDetails->sortByDesc('level')->first();
        if (!$lastDetail) {
            return false;
        }

        if ($approvedDetails->contains(function ($d) {
            return strtolower(trim($d->status ?? '')) === 'not approve';
        })) {
            return false;
        }

        if ($this->last_approver_direct_approval) {
            return true;
        }

        $priorDetails = $approvedDetails->where('level', '<', $lastDetail->level);
        foreach ($priorDetails as $detail) {
            $st = strtolower(trim($detail->status ?? ''));
            if ($st === 'vacant') {
                continue;
            }
            if (!in_array($st, ['approve', 'approved', 'approve with review', 'approved with review'], true)) {
                return false;
            }
        }

        return true;
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function revision()
    {
        return $this->belongsTo(PreMpdrRevision::class);
    }

    public function detail()
    {
        return $this->hasOne(PreMpdrFormDetail::class, 'form_id');
    }

    public function category()
    {
        return $this->hasOne(PreMpdrProductCategory::class, 'form_id');
    }

    public function channel()
    {
        return $this->hasOne(PreMpdrChannel::class, 'form_id');
    }

    public function description()
    {
        return $this->hasOne(PreMpdrProductDescription::class, 'form_id');
    }

    public function certification()
    {
        return $this->hasOne(PreMpdrCertificationRequirement::class, 'form_id');
    }

    public function competitor()
    {
        return $this->hasMany(PreMpdrCompetitorProduct::class, 'form_id', 'id');
    }

    public function packaging()
    {
        return $this->hasOne(PreMpdrDetailedPackaging::class, 'form_id');
    }

    public function market()
    {
        return $this->hasOne(PreMpdrMarketUpdate::class, 'form_id');
    }

    public function approvedDetail()
    {
        return $this->hasMany(PreMpdrApprovedDetail::class, 'form_id');
    }

    public function Mpdr()
    {
        return $this->hasOne(MpdrForm::class, 'prempdr_no', 'no');
    }
    
}
