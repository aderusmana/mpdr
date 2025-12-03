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
        // dd("ini di mail: ", $this->user, $this->form, $this->approvalNotReviewLink, $this->approvalWithReviewLink, $this->notApproveLink);
        
        return $this->from(config('mail.from.address'), 'MPDR SMII')
                    ->view('emails.mpdr.sendToApprover')
                    ->subject("Form ". $this->form->no ." needs your approval.")
                    ->with([
                        'user' => $this->user,
                        'form' => $this->form,
                        'approvalNotReviewLink' => $this->approvalNotReviewLink,
                        'approvalWithReviewLink' => $this->approvalWithReviewLink,
                        'notApproveLink' => $this->notApproveLink
                    ]);
    }
}
