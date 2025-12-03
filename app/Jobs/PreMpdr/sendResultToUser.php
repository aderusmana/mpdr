<?php

namespace App\Jobs\PreMpdr;

use Illuminate\Support\Facades\Mail;
use App\Mail\PreMpdr\sendToUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        // dd($this->user, $this->form);
        $downloadLink = route('prempdr.print', ['no_reg' => $this->form->no]);
        Mail::to($this->user->email)
        ->send(new sendToUser($this->user, $this->form, $downloadLink));
    }
}
