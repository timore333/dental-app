<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdvanceCredit extends Model
{
    protected $table = 'patient_advance_credits';

    protected $fillable = [
        'patient_id',
        'amount',
        'remaining_balance',
        'source_type',
        'source_reference_id',
        'expires_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(PaymentAllocation::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('remaining_balance', '>', 0)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            });
    }

    public function scopeForPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    // ==================== METHODS ====================

    public function getAvailableBalance(): float
    {
        return (float)$this->remaining_balance;
    }

    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }
        return $this->expires_at < now();
    }

    public function applyToPayment(float $amount): float
    {
        $applicableAmount = min($amount, $this->remaining_balance);

        $this->decrement('remaining_balance', $applicableAmount);

        return $applicableAmount;
    }

    public function refund(float $amount): void
    {
        $this->increment('remaining_balance', $amount);
    }

    public function markExpired(): void
    {
        $this->update(['expires_at' => now()]);
    }

    public function isFull(): bool
    {
        return $this->remaining_balance == 0;
    }

    protected static function booted(): void
    {
        static::creating(function ($credit) {
            if (!$credit->expires_at) {
                $days = config('payment.advance_credit_expiry_days', 365);
                $credit->expires_at = now()->addDays($days);
            }
        });
    }
}
