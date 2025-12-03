<?php

namespace App\Jobs\Mpdr;

use App\Mail\Mpdr\sendToAdmin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class sendUpdateToAdmin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $form;
    protected $approver;
    
    public function __construct($user, $form, $approver)
    {
        $this->user = $user;
        $this->form = $form;
        $this->approver = $approver;
    }

    public function handle(): void
    {
        Mail::to($this->user->email)
        ->send(new sendToAdmin($this->user, $this->form, $this->approver));
    }
}
