<?php

namespace App\Services;

use App\Models\InsuranceRequest;
use App\Models\InsuranceApproval;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\InsuranceCompany;
use App\Models\Bill;

class InsuranceService
{
    /**
     * Create insurance request for patient procedures
     */
    public function createInsuranceRequest(
        Patient $patient,
        Doctor $doctor,
        array $procedureIds
    ): InsuranceRequest {
        $request = InsuranceRequest::create([
            'patient_id' => $patient->id,
            'insurance_company_id' => $patient->insurance_company_id,
            'doctor_id' => $doctor->id,
            'request_date' => now(),
            'status' => 'submitted',
            'created_by' => auth()->id() ?? 1,
        ]);

        return $request;
    }

    /**
     * Record insurance approval
     */
    public function recordApproval(
        InsuranceRequest $request,
        array $approvedProcedureIds,
        array $rejectedProcedureIds = [],
        ?float $approvedAmount = null,
        ?string $notes = null
    ): InsuranceApproval {
        // Determine status
        if (empty($approvedProcedureIds) && !empty($rejectedProcedureIds)) {
            $status = 'rejected';
        } elseif (!empty($approvedProcedureIds) && !empty($rejectedProcedureIds)) {
            $status = 'partial';
        } else {
            $status = 'approved';
        }

        // Create approval record
        $approval = InsuranceApproval::create([
            'insurance_request_id' => $request->id,
            'approval_date' => now(),
            'approved_procedures' => $approvedProcedureIds,
            'rejected_procedures' => $rejectedProcedureIds,
            'approved_amount' => $approvedAmount,
            'approval_notes' => $notes,
            'created_by' => auth()->id() ?? 1,
        ]);

        // Update request status
        $request->update(['status' => $status]);

        return $approval;
    }

    /**
     * Create bill from insurance approval
     */
    public function createBillFromApproval(InsuranceApproval $approval): Bill
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

        return $bill;
    }

    /**
     * Get pending insurance requests
     */
    public function getPendingRequests()
    {
        return InsuranceRequest::where('status', 'submitted')
            ->with('patient', 'insuranceCompany', 'doctor')
            ->orderBy('request_date')
            ->get();
    }

    /**
     * Get approval status for patient
     */
    public function getApprovalStatus(Patient $patient)
    {
        return InsuranceRequest::where('patient_id', $patient->id)
            ->with('approval')
            ->orderByDesc('request_date')
            ->get();
    }

    /**
     * Get insurance price for procedure
     */
    public function getInsurancePrice(InsuranceCompany $company, int $procedureId): ?float
    {
        return $company->getPriceForProcedure($procedureId);
    }
}
