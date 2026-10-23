<?php

namespace App\Http\Controllers\PreMpdr;

use App\Actions\PreMpdr\ApprovePreMpdrAction;
use App\Actions\PreMpdr\ProcessEmailApprovalPreMpdrAction;
use App\Actions\PreMpdr\ResendEmailPreMpdrAction;
use App\Actions\PreMpdr\SendUrgentDirectToLastApproverPreMpdrAction;
use App\Http\Controllers\Controller;
use App\Models\PREMPDR\PreMpdrForm;
use App\Models\User;
use App\Services\PreMpdr\PreMpdrApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PreMpdrApprovalController extends Controller
{
    public function viewApprovalForm($no_reg)
    {
        confirmDelete();
        return view('page.pre-mpdr.form-approval-prempdr')->with('no_reg', $no_reg);
    }

    public function getApproverAvailableLevels(PreMpdrApprovalService $service)
    {
        return response()->json($service->getApproverAvailableLevels());
    }

    public function approveForm(Request $request, $no_reg, ApprovePreMpdrAction $action)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|string|in:approve,approve with review,not approve',
            'comment' => 'required_if:action,approve with review,not approve|nullable|string',
            'target_approver_nik' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        try {
            $result = $action->execute($no_reg, $validator->validated(), Auth::user());

            return response()->json($result);
        } catch (\Exception $e) {
            $status = in_array($e->getMessage(), [
                'Form not found.',
            ], true) ? 404 : (in_array($e->getMessage(), [
                'You have already processed this form or it is not your turn.',
                'Only the current approver can be taken over.',
            ], true) ? 403 : 500);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $status);
        }
    }

    public function getApproverListData(PreMpdrApprovalService $service)
    {
        return response()->json($service->getApproverListData());
    }

    public function getApprovalListData(PreMpdrApprovalService $service)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['data' => []]);
        }

        return response()->json(['data' => $service->getApprovalListData($user)]);
    }

    public function getApprovalFormData($no_reg = null)
    {
        return view('page.pre-mpdr.form-approval-prempdr')->with('no_reg', $no_reg);
    }

    public function destroy($nik, PreMpdrApprovalService $service)
    {
        try {
            $deleted = $service->deleteApprover($nik);

            if ($deleted) {
                return response()->json(['status' => 'success', 'message' => 'Approver has been deleted successfully.']);
            }

            return response()->json(['status' => 'error', 'message' => 'Approver not found or you do not have permission to delete it.'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'A server error occurred.'], 500);
        }
    }

    public function approveNotReview(Request $request, ProcessEmailApprovalPreMpdrAction $action)
    {
        $form_no = $request->query('form_no');
        $approver_nik = $request->query('approver_nik');
        $status = $request->query('status');
        $token = $request->query('token');

        $data = $action->execute($form_no, $approver_nik, $status, $token, null);

        return view('emails.pre-mpdr.resultView', $data);
    }

    protected function getAlreadyProcessedData(PreMpdrForm $form, User $approver, ?string $token, string $status, PreMpdrApprovalService $service): ?array
    {
        $userApproval = $form->approvedDetail()->where('approver', $approver->nik)->first();
        $nextPending = $service->nextPendingApproval($form);

        $isAlreadyProcessed = false;
        $alreadyMessage = 'You have already approved this form previously.';

        if ($form->status !== 'In Approval') {
            $isAlreadyProcessed = true;
            $alreadyMessage = 'This form has already been processed or finalized.';
        } elseif ($userApproval && $userApproval->status !== null) {
            $isAlreadyProcessed = true;
            $status = $userApproval->status;
        } elseif ($userApproval && $userApproval->token === null) {
            $isAlreadyProcessed = true;
            $status = $userApproval->status ?? $status;
        } elseif (!$nextPending || $nextPending->approver !== $approver->nik) {
            $isAlreadyProcessed = true;
            if ($userApproval && $userApproval->status) {
                $alreadyMessage = 'You have already approved this form previously.';
                $status = $userApproval->status;
            } else {
                $alreadyMessage = 'This form is currently not pending your approval.';
            }
        } elseif ($token && $userApproval && $userApproval->token && !hash_equals((string) $userApproval->token, (string) $token)) {
            $isAlreadyProcessed = true;
            $alreadyMessage = 'This approval link is invalid or has expired.';
        }

        if ($isAlreadyProcessed) {
            return [
                'form_no' => $form->no,
                'result' => $alreadyMessage,
                'status' => $status,
                'form' => $form,
                'approver' => $approver,
                'approverDetail' => $userApproval ?? $nextPending,
                'comment' => $userApproval?->comment,
            ];
        }

        return null;
    }

    public function approveWithReview(Request $request, PreMpdrApprovalService $service)
    {
        $form_no = $request->query('form_no');
        $approver_nik = $request->query('approver_nik');
        $status = $request->query('status');
        $token = $request->query('token');
        $route_name = 'approveWithReview';

        $form = PreMpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market',
            'approvedDetail', 'user'
        ])->where('no', $form_no)->firstOrFail();

        $approver = User::where('nik', $approver_nik)->firstOrFail();

        if ($alreadyData = $this->getAlreadyProcessedData($form, $approver, $token, $status, $service)) {
            return view('emails.pre-mpdr.resultView', $alreadyData);
        }

        return view('emails.pre-mpdr.commentForm', compact('form', 'form_no', 'approver', 'approver_nik', 'status', 'token', 'route_name'));
    }

    public function notApprove(Request $request, PreMpdrApprovalService $service)
    {
        $form_no = $request->query('form_no');
        $approver_nik = $request->query('approver_nik');
        $status = $request->query('status');
        $token = $request->query('token');
        $route_name = 'notApprove';

        $form = PreMpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market',
            'approvedDetail', 'user'
        ])->where('no', $form_no)->firstOrFail();

        $approver = User::where('nik', $approver_nik)->firstOrFail();

        if ($alreadyData = $this->getAlreadyProcessedData($form, $approver, $token, $status, $service)) {
            return view('emails.pre-mpdr.resultView', $alreadyData);
        }

        return view('emails.pre-mpdr.commentForm', compact('form', 'form_no', 'approver', 'approver_nik', 'status', 'token', 'route_name'));
    }

    public function mailComment(Request $request, ProcessEmailApprovalPreMpdrAction $action)
    {
        $form_no = $request->query('form_no');
        $approver_nik = $request->query('approver_nik');
        $status = $request->query('status');
        $token = $request->query('token');
        $comment = $request->input('comment');

        $data = $action->execute($form_no, $approver_nik, $status, $token, $comment);

        return view('emails.pre-mpdr.resultView', $data);
    }

    public function approver()
    {
        User::role('approver')->get();
        confirmDelete();
        return view('page.pre-mpdr.approver-prempdr');
    }

    public function getSelectedApproverList(PreMpdrApprovalService $service)
    {
        return response()->json($service->getSelectedApproverList(Auth::user()->nik));
    }

    public function updateApproverOrder(Request $request, PreMpdrApprovalService $service)
    {
        $validator = Validator::make($request->all(), [
            'nik'     => 'present|array',
            'nik.*'   => 'required|string',
            'name'    => 'present|array',
            'name.*'  => 'required|string',
            'status'  => 'present|array',
            'status.*' => 'required|string',
            'level'   => 'present|array',
            'level.*' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid data provided.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user_nik          = Auth::user()->nik;
        $approver_niks     = $request->input('nik');
        $approver_names    = $request->input('name');
        $approver_statuses = $request->input('status');
        $approver_levels   = $request->input('level');

        if (!empty($approver_niks) && (count($approver_niks) !== count($approver_names) || count($approver_niks) !== count($approver_statuses) || count($approver_niks) !== count($approver_levels))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Input arrays have mismatched counts.',
            ], 400);
        }

        try {
            $service->updateApproverOrder($user_nik, $approver_niks, $approver_names, $approver_statuses, $approver_levels);

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

    public function resendEmail($no_reg, ResendEmailPreMpdrAction $action)
    {
        try {
            $approverUser = $action->execute($no_reg, Auth::user());

            return response()->json([
                'status' => 'success',
                'message' => 'The notification email was resent to ' . $approverUser->name . '. The previous email link has expired.',
            ]);
        } catch (\Exception $e) {
            $status = str_contains($e->getMessage(), 'not found') ? 404 : 400;

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to resend the email: ' . $e->getMessage(),
            ], $status);
        }
    }

    public function sendMailToLastApproverUrgent($no_reg, SendUrgentDirectToLastApproverPreMpdrAction $action)
    {
        $user = Auth::user();
        if (!$user->can('view direct gm mpdr') && !$user->can('view direct head snm pre') && !$user->hasRole(['super-admin', 'Super-Admin', 'admin', 'Admin'])) {
            abort(403);
        }

        try {
            $approver = $action->execute($no_reg, $user);

            return response()->json([
                'success' => true,
                'message' => 'The direct approval email has been sent to ' . $approver->name . ' (Last Approver / Head S&M). Pending or vacant approvals will be handled when approved.',
            ]);
        } catch (\Exception $e) {
            $code = in_array($e->getMessage(), [
                'Email can only be sent for forms that are "In Approval".',
                'Direct to Last Approval cannot be sent because an approval has been rejected.',
                'The Last Approver has already processed this form.',
            ], true) ? 400 : (str_contains($e->getMessage(), 'not found') ? 404 : 500);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $code);
        }
    }
}
