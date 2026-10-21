<?php

namespace App\Actions\PreMpdr;

use App\Models\PREMPDR\PreMpdrForm;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeletePreMpdrAction
{
    /**
     * Delete a draft Pre-MPDR form.
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
            $form = PreMpdrForm::where('no', $no)
                ->where('status', 'Draft')
                ->firstOrFail();

            $form->delete();

            activity()
                ->performedOn($form)
                ->inLog('prempdr')
                ->event('Delete')
                ->causedBy($user)
                ->withProperties(['no' => $no, 'action' => 'deleted'])
                ->log('Delete PreMpdr Form ' . $no . ' by ' . $user->name . ' at ' . now());

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
