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

    public function build()
    {
        $this->form->loadMissing(['user.position', 'requester.position', 'preMpdr.user.position', 'approvedDetail.user.position', 'initiatorDetail']);

        $decidedStatuses = ['approve', 'approved', 'approve with review', 'approved with review', 'not approve', 'not approved', 'rejected'];

        $decidedApprovals = $this->form->approvedDetail
            ? $this->form->approvedDetail->filter(function ($d) use ($decidedStatuses) {
                return in_array(strtolower(trim($d->status ?? '')), $decidedStatuses, true);
            })
            : collect();

        $latestApprover = $decidedApprovals->sortByDesc(function ($d) {
            $timestamp = $d->updated_at ? $d->updated_at->timestamp : ($d->approved_date ? strtotime($d->approved_date) : 0);
            return sprintf('%012d_%04d_%08d', $timestamp, $d->level ?? 0, $d->id ?? 0);
        })->first();

        $approverName = $latestApprover ? ($latestApprover->approver_name ?? $latestApprover->name ?? 'Approver') : 'Approver';

        return $this->from(config('mail.from.address'), 'MPDR SMII')
                    ->view('emails.mpdr.sendToUser')
                    ->subject($this->form->status == 'In Approval'
                                ? "Form " . $this->form->no . " has been approved by " . $approverName
                                : ( $this->form->status == 'Approved'
                                ? "Final Notification: Form " . $this->form->no . " has been Approved"
                                : "Form " . $this->form->no . " has been " . $this->form->status))
                    ->with([
                        'user' => $this->user,
                        'form' => $this->form,
                        'downloadLink' => $this->downloadLink,
                        'latestApproval' => $latestApprover,
                    ]);
    }
}
