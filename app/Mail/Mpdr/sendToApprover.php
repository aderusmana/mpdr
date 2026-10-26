<?php

namespace App\Mail\Mpdr;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class sendToApprover extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $form;
    protected $approvalNotReviewLink;
    protected $approvalWithReviewLink;
    protected $notApproveLink;
    
    public function __construct($user, $form, $approvalNotReviewLink, $approvalWithReviewLink, $notApproveLink)
    {
        $this->user = $user;
        $this->form = $form;
        $this->approvalNotReviewLink = $approvalNotReviewLink;
        $this->approvalWithReviewLink = $approvalWithReviewLink;
        $this->notApproveLink = $notApproveLink;
    }

    
    public function build(){
        $this->form->loadMissing(['user.position', 'requester.position', 'preMpdr.user.position', 'approvedDetail.user.position', 'initiatorDetail']);

        $isGm = false;
        if ($this->user && method_exists($this->user, 'hasRole')) {
            $isGm = $this->user->hasRole(['gm', 'GM', 'General Manager']);
        }
        if (!$isGm && isset($this->user->position)) {
            $posName = strtolower($this->user->position->position_name ?? $this->user->position->name ?? '');
            $isGm = str_contains($posName, 'general manager') || $posName === 'gm';
        }

        return $this->from(config('mail.from.address'), 'MPDR SMII')
                    ->view('emails.mpdr.sendToApprover')
                    ->subject("Form ". $this->form->no ." needs your approval.")
                    ->with([
                        'user' => $this->user,
                        'form' => $this->form,
                        'isGm' => $isGm,
                        'approvalNotReviewLink' => $this->approvalNotReviewLink,
                        'approvalWithReviewLink' => $this->approvalWithReviewLink,
                        'notApproveLink' => $this->notApproveLink
                    ]);
    }
}
