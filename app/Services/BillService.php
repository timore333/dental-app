<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Visit;
use App\Models\Patient;
use App\Models\InsuranceCompany;
use App\Models\InsuranceApproval;
use App\Models\Procedure;

class BillService
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Create bill from visit (with all procedures)
     */
    public function createBillFromVisit(Visit $visit): Bill
    {
        $bill = Bill::create([
            'bill_number' => Bill::generateBillNumber(),
            'patient_id' => $visit->patient_id,
            'doctor_id' => $visit->doctor_id,
            'bill_date' => now(),
            'type' => $visit->patient->type,
            'insurance_company_id' => $visit->patient->insurance_company_id,
            'total_amount' => 0,
            'status' => 'draft',
            'created_by' => auth()->id() ?? 1,
        ]);

        // Add all procedures from visit
        $totalAmount = 0;
        foreach ($visit->procedures as $procedure) {
            $price = $procedure->pivot->price_at_time;
            $bill->addItem($procedure, 1, $price);
            $totalAmount += $price;
        }

        // Update bill total
        $bill->update(['total_amount' => $totalAmount]);

        return $bill;
    }

    /**
     * Create bill for cash payment
     */
    public function createCashBill(Patient $patient, array $items): Bill
    {
        $bill = Bill::create([
            'bill_number' => Bill::generateBillNumber(),
            'patient_id' => $patient->id,
            'bill_date' => now(),
            'type' => 'cash',
            'total_amount' => 0,
            'status' => 'draft',
            'created_by' => auth()->id() ?? 1,
        ]);

        // Add items to bill
        $totalAmount = 0;
        foreach ($items as $item) {
            $procedure = Procedure::find($item['procedure_id']);
            if ($procedure) {
                $bill->addItem($procedure, $item['quantity'] ?? 1, $item['price']);
                $totalAmount += ($item['price'] * ($item['quantity'] ?? 1));
            }
        }

        $bill->update(['total_amount' => $totalAmount]);
        return $bill;
    }

    /**
     * Create bill from insurance approval
     */
    public function createInsuranceBill(InsuranceApproval $approval): Bill
    {
        $request = $approval->insuranceRequest;

        $bill = Bill::create([
            'bill_number' => Bill::generateBillNumber(),
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'bill_date' => now(),
            'type' => 'insurance',
            'insurance_company_id' => $request->insurance_company_id,
            'insurance_request_id' => $request->id,
            'total_amount' => $approval->approved_amount ?? 0,
            'status' => 'issued',
            'created_by' => auth()->id() ?? 1,
        ]);

        // Add approved procedures
        if ($approval->approved_procedures) {
            foreach ($approval->approved_procedures as $procedureId) {
                $procedure = Procedure::find($procedureId);
                if ($procedure) {
                    $price = $request->insuranceCompany->getPriceForProcedure($procedureId)
                        ?? $procedure->default_price;
                    $bill->addItem($procedure, 1, $price);
                }
            }
        }

        return $bill;
    }

    /**
     * Get all bills for a patient
     */
    public function getBillsForPatient(int $patientId)
    {
        return Bill::where('patient_id', $patientId)
            ->with('billItems.procedure', 'payments')
            ->orderByDesc('bill_date')
            ->get();
    }

    /**
     * Get outstanding bills for patient
     */
    public function getOutstandingBills(int $patientId)
    {
        return Bill::where('patient_id', $patientId)
            ->whereIn('status', ['issued', 'partially_paid'])
            ->with('billItems.procedure')
            ->get();
    }

    /**
     * Get overdue bills
     */
    public function getOverdueBills()
    {
        return Bill::where('due_date', '<', now())
            ->whereIn('status', ['issued', 'partially_paid'])
            ->with('patient', 'billItems')
            ->get();
    }

    /**
     * Issue bill (change status from draft to issued)
     */
    public function issueBill(Bill $bill): bool
    {
        return $bill->update(['status' => 'issued']);
    }

    /**
     * Cancel bill
     */
    public function cancelBill(Bill $bill): bool
    {
        return $bill->update(['status' => 'cancelled']);
    }
}
