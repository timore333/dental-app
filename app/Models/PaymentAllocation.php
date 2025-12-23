<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentAllocation extends Model
{
    protected $fillable = [
        'payment_id',
        'bill_id',
        'advance_credit_id',
        'allocated_amount',
        'allocation_date'
    ];

    protected $casts = [
        'allocated_amount' => 'decimal:2',
        'allocation_date' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function advanceCredit(): BelongsTo
    {
        return $this->belongsTo(AdvanceCredit::class);
    }
}
