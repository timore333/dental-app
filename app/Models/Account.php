<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'accountable_id',
        'accountable_type',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Polymorphic - can belong to Patient or InsuranceCompany
     */
    public function accountable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * All ledger entries for this account
     */
    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(AccountLedgerEntry::class);
    }

    // ==================== METHODS ====================

    /**
     * Debit account (decrease balance)
     */
    public function debit(float $amount, string $referenceType, ?int $referenceId, string $description): AccountLedgerEntry
    {
        $entry = $this->ledgerEntries()->create([
            'transaction_type' => 'debit',
            'amount' => $amount,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'description' => $description,
            'created_by' => auth()->id() ?? 1,
        ]);

        $this->update(['balance' => $this->balance - $amount]);
        return $entry;
    }

    /**
     * Credit account (increase balance)
     */
    public function credit(float $amount, string $referenceType, ?int $referenceId, string $description): AccountLedgerEntry
    {
        $entry = $this->ledgerEntries()->create([
            'transaction_type' => 'credit',
            'amount' => $amount,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'description' => $description,
            'created_by' => auth()->id() ?? 1,
        ]);

        $this->update(['balance' => $this->balance + $amount]);
        return $entry;
    }

    /**
     * Get current balance
     */
    public function getBalance(): float
    {
        return (float) $this->balance;
    }

    /**
     * Get account statement for date range
     */
    public function getStatementFor(\DateTime $fromDate, \DateTime $toDate): array
    {
        return $this->ledgerEntries()
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->get()
            ->map(fn ($entry) => [
                'date' => $entry->created_at,
                'type' => $entry->transaction_type,
                'amount' => $entry->amount,
                'reference' => "{$entry->reference_type}#{$entry->reference_id}",
                'description' => $entry->description,
            ])
            ->toArray();
    }
}
