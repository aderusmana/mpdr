<?php

namespace App\Http\Controllers\Mpdr;

use App\Http\Controllers\Controller;
use App\Jobs\Mpdr\ProcessApproval;
use App\Models\MPDR\MpdrForm;
use App\Models\User;
use App\Notifications\MpdrNotification;

class MpdrInitiatorController extends Controller
{
    public function getInitiatorList()
    {
        $initiatorList = User::where('status', 'Active')->get(); //role('approver')->
        if($initiatorList){
            return response()->json($initiatorList);
        }
        return response()->json("No data available.");
    }

    public function sendMailToInitiator($no_reg)
    {
        $form = MpdrForm::with('revision', 'detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market', 'approvedDetail', 'initiatorDetail')
        ->where('no', $no_reg)->first();
        if($form->status == 'In Approval')
        {
            if($form->initiatorDetail->status == 'pending' && $form->initiatorDetail->token !== null)
            {
                $initiator = User::where('nik', $form->initiatorDetail->initiator_nik)->first();
                ProcessApproval::dispatch($initiator, $form, $form->initiatorDetail)->delay(10); // send email to initiator

                // kirim notif app
                $data = [
                    'title' => 'New MPDR for Approval',
                    'message' => $form->no . ' needs your approval.',
                    'user_id' => $form->user_id,
                ];
                $notificationType = 'approval_request'; // Tipe notifikasi yang sesuai
                $initiator->notify(new MpdrNotification($data, $notificationType));

                return;
            }
        }
    }
}
