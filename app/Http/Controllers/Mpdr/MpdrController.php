<?php

namespace App\Http\Controllers\Mpdr;

use App\Actions\Mpdr\DeleteMpdrAction;
use App\Actions\Mpdr\StoreMpdrAction;
use App\Actions\Mpdr\UpdateMpdrAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mpdr\StoreMpdrRequest;
use App\Http\Requests\Mpdr\UpdateMpdrRequest;
use App\Models\MPDR\MpdrForm;
use App\Services\Mpdr\MpdrService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class MpdrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        confirmDelete();
        return view('page.mpdr.list-mpdr');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('mpdr.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMpdrRequest $request, StoreMpdrAction $action)
    {
        try {
            $action->execute($request->validated(), Auth::user());

            return response()->json([
                'success' => true,
                'message' => 'Form and details successfully saved!',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'There was an error saving the form: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($no_reg)
    {
        return view('page.mpdr.form-mpdr')->with('no_reg', $no_reg);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $no_reg)
    {
        return redirect()->route('mpdr.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMpdrRequest $request, string $no_reg, UpdateMpdrAction $action)
    {
        try {
            $action->execute($no_reg, $request->validated(), Auth::user());

            return response()->json([
                'success' => true,
                'message' => 'Form and details successfully updated!',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'There was an error updating the form: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $no, DeleteMpdrAction $action)
    {
        try {
            $action->execute($no, Auth::user());

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'The MPDR request was deleted successfully.',
                ]);
            }

            Alert::toast('Form successfully deleted!.', 'success');
            return redirect()->route('mpdr.index');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'There was an error deleting the form: ' . $e->getMessage(),
                ], 500);
            }

            Alert::toast('There was an error updating the form.' . $e->getMessage(), 'error');
            return back();
        }
    }

    public function report()
    {
        return view('page.mpdr.report-mpdr');
    }

    public function log()
    {
        return view('page.mpdr.log-mpdr');
    }

    public function viewForm()
    {
        return redirect()->route('mpdr.index');
    }

    public function template()
    {
        return redirect()->route('mpdr.index');
    }

    public function noReg(MpdrService $service)
    {
        return response()->json(['no_reg' => $service->generateNoReg()]);
    }

    public function getFormList(Request $request, MpdrService $service)
    {
        return response()->json($service->getFormList($request));
    }

    public function getFormData(Request $request)
    {
        $user = Auth::user();
        $query = MpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market',
            'approvedDetail', 'initiatorDetail', 'user', 'requester', 'preMpdr'
        ])->where('no', $request->input('no_reg'));

        if ($user && !$user->hasRole(['super-admin', 'Super-Admin'])) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('requester_id', $user->id)
                  ->orWhereHas('preMpdr', function ($sub) use ($user) {
                      $sub->where('user_id', $user->id);
                  });
            });
        }

        $form = $query->first();

        if ($form) {
            return response()->json($form);
        }

        return response()->json("No form found.");
    }

    public function getReportData(MpdrService $service)
    {
        return response()->json($service->getReportData());
    }

    public function printPdf($no_reg)
    {
        $user = Auth::user();
        $query = MpdrForm::with([
            'revision',
            'detail',
            'category',
            'channel',
            'description',
            'certification',
            'competitor',
            'packaging',
            'market',
            'user.position',
            'requester.position',
            'preMpdr.user.position',
            'approvedDetail' => function ($query) {
                $query->orderBy('created_at', 'asc');
            },
            'approvedDetail.user.position'
        ])->where('no', $no_reg);

        if ($user && !$user->hasRole(['super-admin', 'Super-Admin'])) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('requester_id', $user->id)
                  ->orWhereHas('preMpdr', function ($sub) use ($user) {
                      $sub->where('user_id', $user->id);
                  });
            });
        }

        $form = $query->firstOrFail();

        $fileName = 'MPDR-Report-' . $no_reg . '.pdf';
        $pdf = Pdf::loadView('page.mpdr.print-mpdr', ['form' => $form]);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream($fileName);
    }

    public function getPrempdrList(MpdrService $service)
    {
        return response()->json($service->getPrempdrList());
    }

    public function getpremprdtompdr(Request $request, MpdrService $service)
    {
        $form = $service->getPrempdrToMpdr($request->input('no_reg'));
        if ($form) {
            return response()->json($form);
        }

        return response()->json("No form found.");
    }

    public function showDetail($formNo, MpdrService $service)
    {
        return response()->json($service->getDetailData($formNo));
    }
}
