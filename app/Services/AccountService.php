<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Patient;
use App\Models\InsuranceCompany;
use Illuminate\Database\Eloquent\Model;

class AccountService
{
    /**
     * Create account for patient
     */
    public function createAccountForPatient(Patient $patient): Account
    {
        return Account::firstOrCreate(
            [
                'accountable_id' => $patient->id,
                'accountable_type' => Patient::class,
            ],
            ['balance' => 0]
        );
    }

    /**
     * Create account for insurance company
     */
    public function createAccountForInsuranceCompany(InsuranceCompany $company): Account
    {
        return Account::firstOrCreate(
            [
                'accountable_id' => $company->id,
                'accountable_type' => InsuranceCompany::class,
            ],
            ['balance' => 0]
        );
    }

    /**
     * Debit account (decrease balance)
     */
    public function debitAccount(
        Model $accountable,
        float $amount,
        string $description,
        string $referenceType,
        ?int $referenceId = null
    ) {
        $account = $this->getAccount($accountable);
        return $account->debit($amount, $referenceType, $referenceId, $description);
    }

    /**
     * Credit account (increase balance)
     */
    public function creditAccount(
        Model $accountable,
        float $amount,
        string $description,
        string $referenceType,
        ?int $referenceId = null
    ) {
        $account = $this->getAccount($accountable);
        return $account->credit($amount, $referenceType, $referenceId, $description);
    }

    /**
     * Get account balance
     */
    public function getAccountBalance(Model $accountable): float
    {
        return $this->getAccount($accountable)->getBalance();
    }

    /**
     * Get account statement for date range
     */
    public function getAccountStatement(Model $accountable, \DateTime $fromDate, \DateTime $toDate): array
    {
        return $this->getAccount($accountable)->getStatementFor($fromDate, $toDate);
    }

    /**
     * Get or create account for entity
     */
    private function getAccount(Model $accountable): Account
    {
        if ($accountable instanceof Patient) {
            return $this->createAccountForPatient($accountable);
        } elseif ($accountable instanceof InsuranceCompany) {
            return $this->createAccountForInsuranceCompany($accountable);
        }

        throw new \InvalidArgumentException('Invalid accountable model');
    }

    /**
     * Get all patient balances (who owes money)
     */
    public function getPatientsWithBalance()
    {
        return Account::where('accountable_type', Patient::class)
            ->where('balance', '<', 0) // Negative balance = patient owes money
            ->with('accountable')
            ->get();
    }

    /**
     * Get all insurance company balances (who we owe money)
     */
    public function getInsuranceCompaniesWithBalance()
    {
        return Account::where('accountable_type', InsuranceCompany::class)
            ->where('balance', '>', 0) // Positive balance = we owe them money
            ->with('accountable')
            ->get();
    }
}
