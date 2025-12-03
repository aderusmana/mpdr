<?php

namespace App\Mail\Mpdr;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class sendToAdmin extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $form;
    protected $approver;

    public function __construct($user, $form, $approver)
    {
        $this->user = $user;
        $this->form = $form;
        $this->approver = $approver;
    }
    
    public function build(){
        return $this->from(config('mail.from.address'), 'MPDR SMII')
                    ->view('emails.mpdr.sendToUser')
                    ->subject("Form ". $this->form->no ." has been ". ($this->form->status == 'In Approval' ? 'approved' : $this->form->status) . " by " . $this->approver->name)
                    ->with([
                        'user' => $this->user,
                        'form' => $this->form,
                        'approver' => $this->approver
                    ]);
    }
}
