<?php

namespace App\Actions\Mpdr;

use App\Models\MPDR\MpdrForm;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeleteMpdrAction
{
    /**
     * Delete a draft MPDR form.
     *
     * @param string $no Form registration number
     * @param User $user Current authenticated user
     * @return void
     * @throws \Exception
     */
    public function execute(string $no, User $user): void
    {
        DB::beginTransaction();

        try {
            $query = MpdrForm::where('no', $no)
                ->where('status', 'Draft');

            if (!$user->hasRole(['super-admin', 'Super-Admin'])) {
                $query->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('requester_id', $user->id)
                      ->orWhereHas('preMpdr', function ($sub) use ($user) {
                          $sub->where('user_id', $user->id);
                      });
                });
            }

            $form = $query->firstOrFail();

            $form->delete();

            activity()
                ->performedOn($form)
                ->inLog('mpdr')
                ->event('Delete')
                ->causedBy($user)
                ->withProperties(['no' => $no, 'action' => 'deleted'])
                ->log('Delete Mpdr Form ' . $no . ' by ' . $user->name . ' at ' . now());

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
