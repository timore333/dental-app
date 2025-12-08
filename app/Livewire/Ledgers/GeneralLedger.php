<?php

namespace App\Livewire\Ledgers;

use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class GeneralLedger extends Component
{
    use WithPagination;

    public $entries = [];
    public $filterType = 'all';
    public $fromDate;
    public $toDate;
    public $perPage = 50;

    public function mount()
    {
        $this->fromDate = Carbon::now()->subDays(90)->format('Y-m-d');
        $this->toDate = Carbon::now()->format('Y-m-d');
        $this->loadLedger();
    }

    public function loadLedger()
    {
        $query = \DB::table('general_ledger')
            ->whereBetween('transaction_date', [$this->fromDate, $this->toDate]);

        if ($this->filterType !== 'all') {
            $query->where('account_type', $this->filterType);
        }

        $this->entries = $query->orderBy('transaction_date', 'asc')
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
    }

    public function filterByType()
    {
        $this->resetPage();
        $this->loadLedger();
    }

    public function filterByDate()
    {
        $this->loadLedger();
    }

    public function render()
    {
        return view('livewire.ledgers.general-ledger', [
            'entries' => array_slice($this->entries, 0, $this->perPage),
            'totalDebits' => collect($this->entries)->sum(fn($e) => $e->type === 'debit' ? $e->amount : 0),
            'totalCredits' => collect($this->entries)->sum(fn($e) => $e->type === 'credit' ? $e->amount : 0),
        ]);
    }
}
