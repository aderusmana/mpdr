<?php

namespace App\Http\Controllers\Mpdr;

use App\Actions\Mpdr\ApproveMpdrAction;
use App\Actions\Mpdr\ProcessEmailApprovalMpdrAction;
use App\Actions\Mpdr\ResendEmailMpdrAction;
use App\Actions\Mpdr\SendUrgentDirectToGmAction;
use App\Http\Controllers\Controller;
use App\Models\MPDR\MpdrForm;
use App\Models\User;
use App\Services\Mpdr\MpdrApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MpdrApprovalController extends Controller
{
    public function approval()
    {
        return view('page.mpdr.approval-mpdr');
    }

    public function approver()
    {
        User::role('approver')->get();
        confirmDelete();
        return view('page.mpdr.approver-mpdr');
    }

    public function viewApprovalForm($no_reg)
    {
        confirmDelete();
        return view('page.mpdr.form-approval-mpdr')->with('no_reg', $no_reg);
    }

    public function getApproverListData(MpdrApprovalService $service)
    {
        return response()->json($service->getApproverListData());
    }

    public function getSelectedApproverList(MpdrApprovalService $service)
    {
        return response()->json($service->getSelectedApproverList(Auth::user()->nik));
    }

    public function updateApproverOrder(Request $request, MpdrApprovalService $service)
    {
        $validated = $request->validate([
            'approvers'          => 'present|array',
            'approvers.*.nik'    => 'required|string',
            'approvers.*.name'   => 'required|string',
            'approvers.*.status' => 'required|string',
        ]);

        try {
            $service->updateApproverOrder(Auth::user()->nik, $validated['approvers']);

            return response()->json([
                'status'  => 'success',
                'message' => 'Approver list has been saved successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while saving the approver list: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getApprovalListData(MpdrApprovalService $service)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([]);
        }

        return response()->json($service->getApprovalListData($user));
    }

    public function approveForm(Request $request, $no_reg, ApproveMpdrAction $action)
    {
        $validator = Validator::make($request->all(), [
            'action'              => 'required|string|in:approve,approve with review,not approve',
            'comment'             => 'nullable|string',
            'target_approver_nik' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $result = $action->execute($no_reg, $validator->validated(), Auth::user());

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function sendMailToGMUrgent($no_reg, SendUrgentDirectToGmAction $action)
    {
        if (!Auth::user()->can('view direct gm mpdr') && !Auth::user()->hasRole(['super-admin', 'Super-Admin'])) {
            abort(403);
        }

        try {
            $action->execute($no_reg, Auth::user());

            return response()->json([
                'success' => true,
                'message' => 'The direct-to-GM email has been scheduled. Pending or vacant approvals will be marked as approved by the GM only after the GM approves the form.',
            ]);
        } catch (\Exception $e) {
            $code = in_array($e->getMessage(), [
                'Email can only be sent for forms that are "In Approval".',
                'Direct to GM cannot be sent because an approval has been rejected.',
                'The General Manager has already processed this form.',
            ], true) ? 400 : (str_contains($e->getMessage(), 'not found') ? 404 : 500);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $code);
        }
    }

    public function approveNotReview(Request $request, ProcessEmailApprovalMpdrAction $action)
    {
        $form_no = $request->query('form_no');
        $approver_nik = $request->query('approver_nik');
        $status = $request->query('status');
        $token = $request->query('token');

        $data = $action->execute($form_no, $approver_nik, $status, $token, null);

        return view('emails.mpdr.resultView', $data);
    }

    protected function getAlreadyProcessedData(MpdrForm $form, User $approver, ?string $token, string $status): ?array
    {
        $initiatorDetail = $form->initiatorDetail()->first();
        $approverDetail = null;
        $isAlreadyProcessed = false;
        $alreadyMessage = 'You have already approved this form previously.';

        if ($form->status !== 'In Approval') {
            $isAlreadyProcessed = true;
            $alreadyMessage = 'This form has already been processed or finalized.';
            $approverDetail = $form->approvedDetail()->where('approver_nik', $approver->nik)->first() ?? $initiatorDetail;
        } elseif ($initiatorDetail && $initiatorDetail->initiator_nik === $approver->nik) {
            $approverDetail = $initiatorDetail;
            if ($initiatorDetail->status !== 'pending' || $initiatorDetail->token === null) {
                $isAlreadyProcessed = true;
                $status = $initiatorDetail->status ?? $status;
            } elseif ($token && !hash_equals((string) $initiatorDetail->token, (string) $token)) {
                $isAlreadyProcessed = true;
                $alreadyMessage = 'This approval link is invalid or has expired.';
            }
        } else {
            $approverDetail = $form->approvedDetail()->where('approver_nik', $approver->nik)->first();
            if ($approverDetail) {
                if ($approverDetail->status !== 'pending' || $approverDetail->token === null) {
                    $isAlreadyProcessed = true;
                    $status = $approverDetail->status ?? $status;
                } elseif ($token && !hash_equals((string) $approverDetail->token, (string) $token)) {
                    $isAlreadyProcessed = true;
                    $alreadyMessage = 'This approval link is invalid or has expired.';
                }
            } else {
                $isAlreadyProcessed = true;
                $alreadyMessage = 'This form is currently not pending your approval.';
            }
        }

        if ($isAlreadyProcessed) {
            return [
                'form_no' => $form->no,
                'result' => $alreadyMessage,
                'status' => $approverDetail?->status ?? $status,
                'form' => $form,
                'approver' => $approver,
                'approverDetail' => $approverDetail,
                'comment' => $approverDetail?->comment,
            ];
        }

        return null;
    }

    public function approveWithReview(Request $request)
    {
        $form_no = $request->query('form_no');
        $approver_nik = $request->query('approver_nik');
        $status = $request->query('status');
        $token = $request->query('token');
        $route_name = 'approveWithReview';

        $form = MpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market',
            'approvedDetail', 'initiatorDetail', 'user', 'requester'
        ])->where('no', $form_no)->firstOrFail();

        $approver = User::where('nik', $approver_nik)->firstOrFail();

        if ($alreadyData = $this->getAlreadyProcessedData($form, $approver, $token, $status)) {
            return view('emails.mpdr.resultView', $alreadyData);
        }

        return view('emails.mpdr.commentForm', compact('form', 'form_no', 'approver', 'approver_nik', 'status', 'token', 'route_name'));
    }

    public function notApprove(Request $request)
    {
        $form_no = $request->query('form_no');
        $approver_nik = $request->query('approver_nik');
        $status = $request->query('status');
        $token = $request->query('token');
        $route_name = 'notApprove';

        $form = MpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market',
            'approvedDetail', 'initiatorDetail', 'user', 'requester'
        ])->where('no', $form_no)->firstOrFail();

        $approver = User::where('nik', $approver_nik)->firstOrFail();

        if ($alreadyData = $this->getAlreadyProcessedData($form, $approver, $token, $status)) {
            return view('emails.mpdr.resultView', $alreadyData);
        }

        return view('emails.mpdr.commentForm', compact('form', 'form_no', 'approver', 'approver_nik', 'status', 'token', 'route_name'));
    }

    public function mailComment(Request $request, ProcessEmailApprovalMpdrAction $action)
    {
        $form_no = $request->query('form_no');
        $approver_nik = $request->query('approver_nik');
        $status = $request->query('status');
        $token = $request->query('token');
        $comment = $request->input('comment');

        $data = $action->execute($form_no, $approver_nik, $status, $token, $comment);

        return view('emails.mpdr.resultView', $data);
    }

    public function resendEmail(Request $request, $no_reg, ResendEmailMpdrAction $action)
    {
        $validated = $request->validate([
            'approver_nik' => 'required|string',
        ]);

        try {
            $approverUser = $action->execute($no_reg, $validated['approver_nik'], Auth::user());

            return response()->json([
                'status'  => 'success',
                'message' => 'The notification email was resent to ' . $approverUser->name . '. The previous email link has expired.',
            ]);
        } catch (\Exception $e) {
            $code = str_contains($e->getMessage(), 'not found') ? 404 : 400;

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to resend the email: ' . $e->getMessage(),
            ], $code);
        }
    }
}
