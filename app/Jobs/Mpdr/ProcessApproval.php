<?php

namespace App\Jobs\Mpdr;

use App\Mail\Mpdr\sendToApprover;
use App\Models\MPDR\MpdrForm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessApproval implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $approver;
    protected $form;
    protected $approved_detail;

    public function __construct($approver, $form, $approved_detail)
    {
        $this->approver = $approver;
        $this->form = $form;
        $this->approved_detail = $approved_detail;
    }
    
    public function handle(): void
    {
        $form = MpdrForm::with([
            'revision',
            'detail',
            'category',
            'channel',
            'description',
            'certification',
            'competitor',
            'packaging',
            'market',
            'approvedDetail',
            'initiatorDetail',
            'preMpdr',
            'requester',
        ])->findOrFail($this->form->getKey());
        $approver = $this->approver;
        $token = $this->approved_detail->token;
        $approvalNotReviewLink = route('mpdr.approveNotReview', [
            'form_no' => $form->no,
            'approver_nik' => $approver->nik,
            'status' => 'approve',
            'token' =>  $token
        ]);
        $approvalWithReviewLink = route('mpdr.approveWithReview', [
            'form_no' => $form->no,
            'approver_nik' => $approver->nik,
            'status' => 'approve with review',
            'token' =>  $token
        ]);
        $notApproveLink = route('mpdr.notApprove', [
            'form_no' => $form->no,
            'approver_nik' => $approver->nik,
            'status' => 'not approve',
            'token' =>  $token
        ]);
        Mail::to($approver->email)
        ->send(new sendToApprover($approver, $form, $approvalNotReviewLink, $approvalWithReviewLink, $notApproveLink));
    }
}
