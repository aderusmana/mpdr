<?php

namespace App\Mail\PreMpdr;

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
        $this->form->loadMissing(['user.position', 'approvedDetail.user.position']);

        $view = $this->from(config('mail.from.address'), 'Pre-MPDR SMII')
                    ->view('emails.pre-mpdr.sendToApprover')
                    ->subject("Form ". $this->form->no ." needs your approval.")
                    ->with([
                        'user' => $this->user,
                        'form' => $this->form,
                        'approvalNotReviewLink' => $this->approvalNotReviewLink,
                        'approvalWithReviewLink' => $this->approvalWithReviewLink,
                        'notApproveLink' => $this->notApproveLink
                    ]);
        return $view;
    }
}
