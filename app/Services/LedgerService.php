<?php

namespace App\Services;

use Carbon\Carbon;

class LedgerService
{
    /**
     * Get patient ledger entries
     */
    public function getPatientLedger($patientId, $fromDate = null, $toDate = null)
    {
        $query = \DB::table('patient_transactions')
            ->where('patient_id', $patientId);

        if ($fromDate && $toDate) {
            $dateFrom = Carbon::parse($fromDate)->startOfDay();
            $dateTo = Carbon::parse($toDate)->endOfDay();
            $query->whereBetween('transaction_date', [$dateFrom, $dateTo]);
        }

        return $query->orderBy('transaction_date', 'asc')->get();
    }

    /**
     * Get insurance company ledger entries
     */
    public function getInsuranceLedger($companyId, $fromDate = null, $toDate = null)
    {
        $query = \DB::table('insurance_transactions')
            ->where('insurance_company_id', $companyId);

        if ($fromDate && $toDate) {
            $dateFrom = Carbon::parse($fromDate)->startOfDay();
            $dateTo = Carbon::parse($toDate)->endOfDay();
            $query->whereBetween('transaction_date', [$dateFrom, $dateTo]);
        }

        return $query->orderBy('transaction_date', 'asc')->get();
    }

    /**
     * Get general ledger entries
     */
    public function getGeneralLedger($fromDate, $toDate)
    {
        $dateFrom = Carbon::parse($fromDate)->startOfDay();
        $dateTo = Carbon::parse($toDate)->endOfDay();

        return \DB::table('general_ledger')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->orderBy('transaction_date', 'asc')
            ->get();
    }

    /**
     * Get balance for accountable model
     */
    public function getBalance($accountable)
    {
        if ($accountable instanceof \App\Models\Patient) {
            return \DB::table('patient_accounts')
                ->where('patient_id', $accountable->id)
                ->value('balance') ?? 0;
        }

        if ($accountable instanceof \App\Models\InsuranceCompany) {
            return \DB::table('insurance_accounts')
                ->where('insurance_company_id', $accountable->id)
                ->value('balance') ?? 0;
        }

        return 0;
    }

    /**
     * Get statement for date range
     */
    public function getStatement($accountable, $fromDate, $toDate)
    {
        if ($accountable instanceof \App\Models\Patient) {
            return $this->getPatientLedger($accountable->id, $fromDate, $toDate);
        }

        if ($accountable instanceof \App\Models\InsuranceCompany) {
            return $this->getInsuranceLedger($accountable->id, $fromDate, $toDate);
        }

        return collect();
    }

    /**
     * Create ledger entry
     */
    public function createEntry($table, $data)
    {
        return \DB::table($table)->insert($data);
    }

    /**
     * Calculate running balance
     */
    public function calculateRunningBalance($entries)
    {
        $runningBalance = 0;

        foreach ($entries as &$entry) {
            if ($entry->type === 'debit') {
                $runningBalance += $entry->amount;
            } else {
                $runningBalance -= $entry->amount;
            }
            $entry->running_balance = $runningBalance;
        }

        return $entries;
    }
}
