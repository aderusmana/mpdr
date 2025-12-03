<?php

namespace App\Mail\PreMpdr;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class sendToUser extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $form;
    protected $downloadLink;

    public function __construct($user, $form, $downloadLink)
    {
        $this->user = $user;
        $this->form = $form;
        $this->downloadLink = $downloadLink;
    }

    public function build(){

        return $this->from(config('mail.from.address'), 'Pre-MPDR SMII')
                    ->view('emails.pre-mpdr.sendToUser')
                    ->subject($this->form->status == 'In Approval'
                                ? "Form ". $this->form->no ." has been approved by " . $this->form->approvedDetail->whereIn('status', ['approve', 'approve with review'])->last()->name
                                : ( $this->form->status == 'Approved'
                                ? "Final Notification: Form " . $this->form->no
                                : "Form ". $this->form->no ." has been " . $this->form->status))
                    ->with([
                        'user' => $this->user,
                        'form' => $this->form,
                        'downloadLink' => $this->downloadLink
                    ]);
    }
}
