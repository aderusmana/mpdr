<?php

namespace App\Http\Controllers\PreMpdr;

use App\Actions\PreMpdr\DeletePreMpdrAction;
use App\Actions\PreMpdr\StorePreMpdrAction;
use App\Actions\PreMpdr\UpdatePreMpdrAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PreMpdr\StorePreMpdrRequest;
use App\Http\Requests\PreMpdr\UpdatePreMpdrRequest;
use App\Models\PREMPDR\PreMpdrForm;
use App\Services\PreMpdr\PreMpdrService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class PreMpdrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Delete Form!';
        $text = "Are you sure you want to delete this form?";
        confirmDelete($title, $text);

        return view('page.pre-mpdr.list-prempdr');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('prempdr.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePreMpdrRequest $request, StorePreMpdrAction $action)
    {
        try {
            $action->execute($request->validated(), Auth::user());

            return response()->json([
                'success' => true,
                'message' => 'The form was created successfully.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($no_reg)
    {
        return view('page.pre-mpdr.form-prempdr')->with('no_reg', $no_reg);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $no_reg)
    {
        return redirect()->route('prempdr.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePreMpdrRequest $request, string $no_reg, UpdatePreMpdrAction $action)
    {
        try {
            $action->execute($no_reg, $request->validated(), Auth::user());

            return response()->json([
                'success' => true,
                'message' => 'Form successfully updated!',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating form: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $no, DeletePreMpdrAction $action)
    {
        try {
            $action->execute($no, Auth::user());

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'The Pre-MPDR request was deleted successfully.',
                ]);
            }

            Alert::toast('Form successfully deleted!.', 'success');
            return redirect()->route('prempdr.index');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'There was an error deleting the form: ' . $e->getMessage(),
                ], 500);
            }

            Alert::toast('There was an error deleting the form: ' . $e->getMessage(), 'error');
            return back();
        }
    }

    public function report()
    {
        return view('page.pre-mpdr.report-prempdr');
    }

    public function approval()
    {
        return view('page.pre-mpdr.approval-prempdr');
    }

    public function log()
    {
        return view('page.pre-mpdr.log-prempdr');
    }

    public function printPdf($no_reg)
    {
        $form = PreMpdrForm::with([
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
            'approvedDetail' => function ($query) {
                $query->orderBy('level', 'asc');
            },
            'approvedDetail.user.position'
        ])->where('no', $no_reg)->firstOrFail();

        $fileName = 'Pre-MPDR-Report-' . $no_reg . '.pdf';
        $pdf = Pdf::loadView('page.pre-mpdr.print-prempdr', ['form' => $form]);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream($fileName);
    }

    public function noReg(PreMpdrService $service)
    {
        return response()->json(['no_reg' => $service->generateNoReg()]);
    }

    public function getFormData(Request $request)
    {
        $form = PreMpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market', 'approvedDetail'
        ])->where('no', $request->input('no_reg'))->first();

        if ($form) {
            return response()->json($form);
        }

        return response()->json("No form found.");
    }

    public function getReportData(PreMpdrService $service)
    {
        return response()->json($service->getReportData());
    }

    public function template()
    {
        return redirect()->route('prempdr.index');
    }

    public function showDraftForm($no_reg)
    {
        return redirect()->route('prempdr.index');
    }

    public function getFormList(Request $request, PreMpdrService $service)
    {
        return response()->json($service->getFormList($request));
    }

    public function showDetail($formNo, PreMpdrService $service)
    {
        return response()->json($service->getDetailData($formNo));
    }
}
