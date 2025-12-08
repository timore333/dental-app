<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'amount',
        'payment_method',
        'payment_date',
        'reference_number',
        'receipt_number',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_method' => 'string', // cash, cheque, card, bank_transfer
        'payment_date' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * The bill this payment is for
     */
    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    /**
     * Associated receipt
     */
    public function receipt(): HasOne
    {
        return $this->hasOne(Receipt::class);
    }

    // ==================== METHODS ====================

    /**
     * Generate receipt number
     */
    public static function generateReceiptNumber(): string
    {
        $year = now()->format('Y');
        $lastPayment = self::where('receipt_number', 'like', "RCP-$year-%")
            ->orderByDesc('receipt_number')
            ->first();

        $sequence = 1;
        if ($lastPayment) {
            $lastSequence = (int) explode('-', $lastPayment->receipt_number)[2];
            $sequence = $lastSequence + 1;
        }

        return sprintf('RCP-%d-%04d', $year, $sequence);
    }

    // ==================== EVENTS ====================

    protected static function booted(): void
    {
        static::creating(function ($payment) {
            if (!$payment->created_by) {
                $payment->created_by = auth()->id() ?? 1;
            }
            if (!$payment->receipt_number) {
                $payment->receipt_number = self::generateReceiptNumber();
            }
        });
    }
}
