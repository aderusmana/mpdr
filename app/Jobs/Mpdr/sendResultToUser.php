<?php

namespace App\Jobs\Mpdr;

use App\Mail\Mpdr\sendToUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class sendResultToUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $form;

    public function __construct($user, $form)
    {
        $this->user = $user;
        $this->form = $form;
    }

    public function handle(): void
    {
        $this->form->loadMissing(['revision', 'detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market', 'approvedDetail', 'initiatorDetail', 'preMpdr', 'requester', 'user']);
        $downloadLink = route('mpdr.print', ['no_reg' => $this->form->no]);
        Mail::to($this->user->email)
        ->send(new sendToUser($this->user, $this->form, $downloadLink));
    }
}
