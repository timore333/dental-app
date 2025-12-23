<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $fillable = [
        'bill_id',
        'patient_id',
        'amount',
        'payment_method',
        'payment_date',
        'reference_number',
        'notes',
        'status',
        'payment_source_type',
        'payment_source_id',
        'receipt_number',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function receipt(): HasOne
    {
        return $this->hasOne(Receipt::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(PaymentAllocation::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ==================== SCOPES ====================

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeByMethod($query, string $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('payment_date', [$from, $to]);
    }

    // ==================== METHODS ====================

    public static function generateReceiptNumber(): string
    {
        $year = now()->format('Y');
        $prefix = config('payment.receipt_prefix', 'RCP');
        $lastPayment = self::where('receipt_number', 'like', "$prefix-$year-%")
            ->orderByDesc('receipt_number')
            ->first();

        $sequence = 1;
        if ($lastPayment) {
            $parts = explode('-', $lastPayment->receipt_number);
            $sequence = (int)$parts[2] + 1;
        }

        return sprintf('%s-%d-%04d', $prefix, $year, $sequence);
    }

    public function isPartialPayment(): bool
    {
        return $this->bill && $this->amount < $this->bill->total_amount;
    }

    public function isAdvancePayment(): bool
    {
        return !$this->bill_id && $this->patient_id;
    }

    public function markAsCompleted(): void
    {
        $this->update(['status' => 'completed']);
    }

    public function markAsCancelled(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public function getAmountDueAttribute(): float
    {
        return $this->bill ? $this->bill->getBalance() : 0;
    }

    protected static function booted(): void
    {
        static::creating(function ($payment) {
            if (!$payment->receipt_number) {
                $payment->receipt_number = self::generateReceiptNumber();
            }
            if (!$payment->created_by) {
                $payment->created_by = auth()->id() ?? 1;
            }
            if (!$payment->payment_date) {
                $payment->payment_date = now();
            }
            $payment->status = $payment->status ?? 'completed';
        });
    }
}
