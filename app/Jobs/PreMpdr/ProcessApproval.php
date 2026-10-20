<?php

namespace App\Jobs\PreMpdr;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\PreMpdr\sendToApprover;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

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
        $form = $this->form;
        $form->loadMissing(['revision', 'detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market', 'approvedDetail', 'user']);
        $approver = $this->approver;
        $token = $this->approved_detail->token;
        $approvalNotReviewLink = route('prempdr.approveNotReview', [
            'form_no' => $form->no,
            'approver_nik' => $approver->nik,
            'status' => 'approve',
            'token' =>  $token
        ]);
        $approvalWithReviewLink = route('prempdr.approveWithReview', [
            'form_no' => $form->no,
            'approver_nik' => $approver->nik,
            'status' => 'approve with review',
            'token' =>  $token
        ]);
        $notApproveLink = route('prempdr.notApprove', [
            'form_no' => $form->no,
            'approver_nik' => $approver->nik,
            'status' => 'not approve',
            'token' =>  $token
        ]);
        $mailable = new sendToApprover($approver, $form, $approvalNotReviewLink, $approvalWithReviewLink, $notApproveLink);

        // Baris pengiriman email yang sebenarnya
        Mail::to($approver->email)->send($mailable);
    }
}
