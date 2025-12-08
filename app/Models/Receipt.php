<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'receipt_number',
        'receipt_date',
    ];

    protected $casts = [
        'receipt_date' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * The payment
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
