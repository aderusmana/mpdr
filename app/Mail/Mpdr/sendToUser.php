<?php

namespace App\Mail\Mpdr;

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
        return $this->from(config('mail.from.address'), 'MPDR SMII')
                    ->view('emails.mpdr.sendToUser')
                    ->subject($this->form->status == 'In Approval'
                                ? "Form " . $this->form->no . " has been approved"
                                : ( $this->form->status == 'Approved'
                                ? "Final Notification: Form " . $this->form->no
                                : "Form " . $this->form->no . " has been " . $this->form->status))
                    ->with([
                        'user' => $this->user,
                        'form' => $this->form,
                        'downloadLink' => $this->downloadLink
                    ]);
    }
}
