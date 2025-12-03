<?php

namespace App\Models\MPDR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MPDR\MpdrFormDetail;
use App\Models\MPDR\MpdrProductCategory;
use App\Models\MPDR\MpdrChannel;
use App\Models\MPDR\MpdrProductDescription;
use App\Models\MPDR\MpdrCertificationRequirement;
use App\Models\MPDR\MpdrCompetitorProduct;
use App\Models\MPDR\MpdrDetailedPackaging;
use App\Models\MPDR\MpdrMarketUpdate;
use App\Models\MPDR\MpdrApprovedDetail;
use App\Models\MPDR\MpdrRevision;
use App\Models\MPDR\MpdrInitiatorApprovedDetail;
use App\Models\PREMPDR\PreMpdrForm;
use App\Models\User;

class MpdrForm extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'no',
        'user_id',
        'revision_id',
        'product_name',
        'type_of_request',
        'requester_id',
        'level_priority',
        'initiator',
        'status',
        'prempdr_no',
    ];
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function revision()
    {
        return $this->belongsTo(MpdrRevision::class);
    }
    
    public function initiatorDetail()
    {
        return $this->hasOne(MpdrInitiatorApprovedDetail::class, 'form_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function detail()
    {
        return $this->hasOne(MpdrFormDetail::class, 'form_id');
    }
    
    public function category()
    {
        return $this->hasOne(MpdrProductCategory::class, 'form_id');
    }
    
    public function channel()
    {
        return $this->hasOne(MpdrChannel::class, 'form_id');
    }
    
    public function description()
    {
        return $this->hasOne(MpdrProductDescription::class, 'form_id');
    }

    public function certification()
    {
        return $this->hasOne(MpdrCertificationRequirement::class, 'form_id');
    }
    
    public function competitor()
    {
        return $this->hasMany(MpdrCompetitorProduct::class, 'form_id');
    }
    
    public function packaging()
    {
        return $this->hasOne(MpdrDetailedPackaging::class, 'form_id');
    }
    
    public function market()
    {
        return $this->hasOne(MpdrMarketUpdate::class, 'form_id');
    }

    public function approvedDetail()
    {
        return $this->hasMany(MpdrApprovedDetail::class, 'form_id');
    }

}
