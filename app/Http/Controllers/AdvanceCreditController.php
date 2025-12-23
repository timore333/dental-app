<?php

namespace App\Http\Controllers;

use App\Models\AdvanceCredit;
use App\Models\Patient;
use App\Services\AdvanceCreditService;
use Illuminate\Http\Request;

class AdvanceCreditController extends Controller
{
    public function __construct(private AdvanceCreditService $service) {}

    public function index(Patient $patient)
    {
        $credits = $this->service->getAvailableCredits($patient);
        return response()->json($credits);
    }

    public function apply(AdvanceCredit $credit, Request $request)
    {
        try {
            $bill = $credit->patient->bills()->findOrFail($request->bill_id);
            $credit->patient->applyAdvanceCredit($credit, $request->amount);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function refund(AdvanceCredit $credit, Request $request)
    {
        try {
            $this->service->issueCreditRefund($credit, $request->amount);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
