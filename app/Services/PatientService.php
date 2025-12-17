<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\Account;
use Illuminate\Pagination\Paginator;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PatientService
{
    /**
     * Create a new patient with financial account
     *
     * @param array $data Validated patient data
     * @return Patient
     * @throws Exception
     */
    public function createPatient(array $data): Patient
    {
        // Ensure empty strings are converted to null
        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }


        try {
            return DB::transaction(function () use ($data) {
                // Generate file number
                $data['file_number'] = $this->getNextFileNumber();

                // Set creator
                $data['created_by'] = auth()->id() ?? 1;

                // Create patient
                $patient = Patient::create($data);

                // Create financial account
                $this->createAccountForPatient($patient);

                // Log activity
                Log::info("Patient created: {$patient->full_name} (ID: {$patient->id})", [
                    'created_by' => auth()->id(),
                    'file_number' => $patient->file_number,
                ]);

                return $patient;
            });
        } catch (Exception $e) {
            Log::error("Error creating patient: {$e->getMessage()}", [
                'data' => $data,
                'exception' => $e,
            ]);
            throw new Exception("Failed to create patient: {$e->getMessage()}");
        }
    }

    /**
     * Update patient information
     *
     * @param Patient $patient
     * @param array $data Validated patient data
     * @return Patient
     * @throws Exception
     */
    public function updatePatient(Patient $patient, array $data): Patient
    {
        try {
            return DB::transaction(function () use ($patient, $data) {
                $oldData = $patient->toArray();

                // Set updater
                $data['updated_by'] = auth()->id() ?? 1;

                // Update patient
                $patient->update($data);

                // Log activity with before/after
                Log::info("Patient updated: {$patient->full_name} (ID: {$patient->id})", [
                    'updated_by' => auth()->id(),
                    'changes' => array_diff_assoc($data, $oldData),
                ]);

                return $patient->fresh();
            });
        } catch (Exception $e) {
            Log::error("Error updating patient: {$e->getMessage()}", [
                'patient_id' => $patient->id,
                'exception' => $e,
            ]);
            throw new Exception("Failed to update patient: {$e->getMessage()}");
        }
    }

    /**
     * Delete patient (soft delete)
     *
     * @param Patient $patient
     * @return bool
     * @throws Exception
     */
    public function deletePatient(Patient $patient): bool
    {
        try {
            return DB::transaction(function () use ($patient) {
                $patientName = $patient->full_name;
                $patientId = $patient->id;

                // Soft delete
                $deleted = $patient->delete();

                if ($deleted) {
                    Log::info("Patient deleted: {$patientName} (ID: {$patientId})", [
                        'deleted_by' => auth()->id(),
                    ]);
                }

                return $deleted;
            });
        } catch (Exception $e) {
            Log::error("Error deleting patient: {$e->getMessage()}", [
                'patient_id' => $patient->id,
                'exception' => $e,
            ]);
            throw new Exception("Failed to delete patient: {$e->getMessage()}");
        }
    }

    /**
     * Restore soft-deleted patient
     *
     * @param Patient $patient
     * @return bool
     * @throws Exception
     */
    public function restorePatient(Patient $patient): bool
    {
        try {
            $restored = $patient->restore();

            if ($restored) {
                Log::info("Patient restored: {$patient->full_name} (ID: {$patient->id})", [
                    'restored_by' => auth()->id(),
                ]);
            }

            return $restored;
        } catch (Exception $e) {
            Log::error("Error restoring patient: {$e->getMessage()}", [
                'patient_id' => $patient->id,
                'exception' => $e,
            ]);
            throw new Exception("Failed to restore patient: {$e->getMessage()}");
        }
    }

    /**
     * Get next available file number
     *
     * @return int
     */
    public function getNextFileNumber(): int
    {
        $lastPatient = Patient::withTrashed()
            ->orderByDesc('file_number')
            ->first();

        $lastNumber = $lastPatient?->file_number ?? 0;

        return $lastNumber + 1;
    }

    /**
     * Create financial account for patient
     *
     * @param Patient $patient
     * @return Account
     * @throws Exception
     */
    public function createAccountForPatient(Patient $patient): Account
    {
        try {
            return $patient->account()->firstOrCreate(
                [
                    'accountable_id' => $patient->id,
                    'accountable_type' => Patient::class,
                ],
                [
                    'balance' => 0,
                    'is_active' => true,
                ]
            );
        } catch (Exception $e) {
            Log::error("Error creating account for patient: {$e->getMessage()}", [
                'patient_id' => $patient->id,
                'exception' => $e,
            ]);
            throw new Exception("Failed to create patient account: {$e->getMessage()}");
        }
    }

    /**
     * Activate patient
     *
     * @param Patient $patient
     * @return bool
     * @throws Exception
     */
    public function activatePatient(Patient $patient): bool
    {
        try {
            $activated = $patient->update(['is_active' => true]);

            if ($activated) {
                Log::info("Patient activated: {$patient->full_name} (ID: {$patient->id})", [
                    'activated_by' => auth()->id(),
                ]);
            }

            return $activated;
        } catch (Exception $e) {
            Log::error("Error activating patient: {$e->getMessage()}", [
                'patient_id' => $patient->id,
                'exception' => $e,
            ]);
            throw new Exception("Failed to activate patient: {$e->getMessage()}");
        }
    }

    /**
     * Deactivate patient
     *
     * @param Patient $patient
     * @return bool
     * @throws Exception
     */
    public function deactivatePatient(Patient $patient): bool
    {
        try {
            $deactivated = $patient->update(['is_active' => false]);

            if ($deactivated) {
                Log::info("Patient deactivated: {$patient->full_name} (ID: {$patient->id})", [
                    'deactivated_by' => auth()->id(),
                ]);
            }

            return $deactivated;
        } catch (Exception $e) {
            Log::error("Error deactivating patient: {$e->getMessage()}", [
                'patient_id' => $patient->id,
                'exception' => $e,
            ]);
            throw new Exception("Failed to deactivate patient: {$e->getMessage()}");
        }
    }

    /**
     * Get insurance patients with pending bills
     *
     * @return Paginator
     */
    public function getInsurancePatientsWithPendingApprovals()
    {
        return Patient::where('type', 'insurance')
            ->where('is_active', true)
            ->with([
                'insuranceCompany',
                'bills' => function ($query) {
                    $query->whereIn('status', ['issued', 'partially_paid']);
                },
            ])
            ->paginate(15);
    }

    /**
     * Get patient statistics
     *
     * @return array
     */
    public function getPatientStatistics(): array
    {
        return [
            'total' => Patient::count(),
            'active' => Patient::where('is_active', true)->count(),
            'inactive' => Patient::where('is_active', false)->count(),
            'cash_patients' => Patient::where('type', 'cash')->count(),
            'insurance_patients' => Patient::where('type', 'insurance')->count(),
            'by_category' => Patient::select('category')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category')
                ->toArray(),
        ];
    }

    /**
     * Search patients
     *
     * @param string $search
     * @param int $perPage
     * @return Paginator
     */
    public function searchPatients(string $search, int $perPage = 10): Paginator
    {
        return Patient::where('first_name', 'like', "%{$search}%")
            ->orWhere('last_name', 'like', "%{$search}%")
            ->orWhere('phone', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('file_number', 'like', "%{$search}%")
            ->where('is_active', true)
            ->orderBy('first_name')
            ->paginate($perPage);
    }

    /**
     * Get patients by category
     *
     * @param string $category
     * @param int $perPage
     * @return Paginator
     */
    public function getPatientsByCategory(string $category, int $perPage = 10): Paginator
    {
        return Patient::where('category', $category)
            ->where('is_active', true)
            ->orderBy('first_name')
            ->paginate($perPage);
    }

    /**
     * Bulk update patient status
     *
     * @param array $patientIds
     * @param bool $active
     * @return int
     * @throws Exception
     */
    public function bulkUpdateStatus(array $patientIds, bool $active): int
    {
        try {
            $count = Patient::whereIn('id', $patientIds)
                ->update(['is_active' => $active]);

            Log::info("Bulk status update: {$count} patients", [
                'updated_by' => auth()->id(),
                'new_status' => $active ? 'active' : 'inactive',
            ]);

            return $count;
        } catch (Exception $e) {
            Log::error("Error bulk updating patient status: {$e->getMessage()}", [
                'exception' => $e,
            ]);
            throw new Exception("Failed to update patient status: {$e->getMessage()}");
        }
    }

    /**
     * Get duplicate phone numbers
     *
     * @return array
     */
    public function findDuplicatePhones(): array
    {
        return Patient::select('phone')
            ->selectRaw('COUNT(*) as count')
            ->where('phone', '!=', null)
            ->groupBy('phone')
            ->having('count', '>', 1)
            ->pluck('count', 'phone')
            ->toArray();
    }

    /**
     * Get duplicate emails
     *
     * @return array
     */
    public function findDuplicateEmails(): array
    {
        return Patient::select('email')
            ->selectRaw('COUNT(*) as count')
            ->where('email', '!=', null)
            ->groupBy('email')
            ->having('count', '>', 1)
            ->pluck('count', 'email')
            ->toArray();
    }
}
