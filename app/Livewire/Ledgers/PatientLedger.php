<?php

namespace App\Livewire\Ledgers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Patient;
use App\Models\PatientAccount;
use Carbon\Carbon;

class PatientLedger extends Component
{
    use WithPagination;

    public $patient;
    public $entries = [];
    public $balance = 0;
    public $perPage = 25;
    public $fromDate;
    public $toDate;

    public function mount($patient_id)
    {
        $this->patient = Patient::findOrFail($patient_id);
        $this->fromDate = Carbon::now()->subDays(90)->format('Y-m-d');
        $this->toDate = Carbon::now()->format('Y-m-d');
        $this->loadLedger();
    }

    public function loadLedger()
    {
        // Get all transactions for patient
        $this->entries = \DB::table('patient_transactions')
            ->where('patient_id', $this->patient->id)
            ->whereBetween('transaction_date', [$this->fromDate, $this->toDate])
            ->orderBy('transaction_date', 'asc')
            ->get()
            ->toArray();

        // Calculate running balance
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

    public function printLedger()
    {
        return response()->view('livewire.ledgers.patient-ledger-print', [
            'patient' => $this->patient,
            'entries' => $this->entries,
            'balance' => $this->balance,
        ]);
    }

    public function render()
    {
        return view('livewire.ledgers.patient-ledger', [
            'patient' => $this->patient,
            'entries' => array_slice($this->entries, 0, $this->perPage),
            'balance' => $this->balance,
        ]);
    }
}
