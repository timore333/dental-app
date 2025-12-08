<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountLedgerEntry extends Model
{
    use HasFactory;

    public $timestamps = false; // Only created_at
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'account_id',
        'transaction_type',
        'amount',
        'reference_type',
        'reference_id',
        'description',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_type' => 'string', // debit or credit
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * The account
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    // ==================== SCOPES ====================

    /**
     * Get only debit entries
     */
    public function scopeDebits($query)
    {
        return $query->where('transaction_type', 'debit');
    }

    /**
     * Get only credit entries
     */
    public function scopeCredits($query)
    {
        return $query->where('transaction_type', 'credit');
    }

    /**
     * Filter by reference
     */
    public function scopeByReference($query, string $type, int $id)
    {
        return $query->where('reference_type', $type)
            ->where('reference_id', $id);
    }

    /**
     * Filter by date range
     */
    public function scopeBetween($query, \DateTime $from, \DateTime $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }
}
