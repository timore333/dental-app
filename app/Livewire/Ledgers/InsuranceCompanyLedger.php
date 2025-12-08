<?php

namespace App\Livewire\Ledgers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InsuranceCompany;
use Carbon\Carbon;

class InsuranceCompanyLedger extends Component
{
    use WithPagination;

    public $insuranceCompany;
    public $entries = [];
    public $balance = 0;
    public $perPage = 25;
    public $fromDate;
    public $toDate;

    public function mount($company_id)
    {
        $this->insuranceCompany = InsuranceCompany::findOrFail($company_id);
        $this->fromDate = Carbon::now()->subDays(90)->format('Y-m-d');
        $this->toDate = Carbon::now()->format('Y-m-d');
        $this->loadLedger();
    }

    public function loadLedger()
    {
        $this->entries = \DB::table('insurance_transactions')
            ->where('insurance_company_id', $this->insuranceCompany->id)
            ->whereBetween('transaction_date', [$this->fromDate, $this->toDate])
            ->orderBy('transaction_date', 'asc')
            ->get()
            ->toArray();

        $runningBalance = 0;
        foreach ($this->entries as &$entry) {
            if ($entry->type === 'debit') {
                $runningBalance += $entry->amount;
            } else {
                $runningBalance -= $entry->amount;
            }
            $entry->running_balance = $runningBalance;
        }

        $this->balance = $runningBalance;
    }

    public function filterByDate()
    {
        $this->loadLedger();
    }

    public function render()
    {
        return view('livewire.ledgers.insurance-company-ledger', [
            'insuranceCompany' => $this->insuranceCompany,
            'entries' => array_slice($this->entries, 0, $this->perPage),
            'balance' => $this->balance,
        ]);
    }
}
