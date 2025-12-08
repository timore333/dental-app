<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\Account;
use Illuminate\Database\Eloquent\Model;

class PatientService
{
    /**
     * Register a new patient with auto-generated file number
     */
    public function registerPatient(array $data): Patient
    {
        // Get next file number
        $data['file_number'] = $this->getNextFileNumber();

        // Ensure created_by is set
        $data['created_by'] = auth()->id() ?? 1;

        // Create patient
        $patient = Patient::create($data);

        // Create financial account for patient
        $this->createAccountForPatient($patient);

        return $patient;
    }

    /**
     * Get next available file number
     */
    public function getNextFileNumber(): int
    {
        $lastPatient = Patient::orderByDesc('file_number')->first();
        $lastNumber = $lastPatient?->file_number ?? 0;
        return $lastNumber + 1;
    }

    /**
     * Create financial account for patient
     */
    public function createAccountForPatient(Patient $patient): Account
    {
        return $patient->account()->firstOrCreate(
            ['accountable_id' => $patient->id, 'accountable_type' => Patient::class],
            ['balance' => 0]
        );
    }

    /**
     * Get all insurance patients with pending approvals
     */
    public function getInsurancePatientsWithPendingApprovals()
    {
        return Patient::where('type', 'insurance')
            ->with([
                'insuranceCompany',
                'bills' => function ($query) {
                    $query->whereIn('status', ['issued', 'partially_paid']);
                }
            ])
            ->where('is_active', true)
            ->get();
    }

    /**
     * Update patient information
     */
    public function updatePatient(Patient $patient, array $data): Patient
    {
        $data['updated_by'] = auth()->id() ?? 1;
        $patient->update($data);
        return $patient;
    }

    /**
     * Deactivate patient
     */
    public function deactivatePatient(Patient $patient): bool
    {
        return $patient->update(['is_active' => false]);
    }

    /**
     * Activate patient
     */
    public function activatePatient(Patient $patient): bool
    {
        return $patient->update(['is_active' => true]);
    }
}
