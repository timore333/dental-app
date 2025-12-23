<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Bill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bill_number',
        'patient_id',
        'doctor_id',
        'bill_date',
        'type',
        'insurance_company_id',
        'insurance_request_id',
        'total_amount',
        'paid_amount',
        'status',
        'due_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'status' => 'string', // draft, issued, partially_paid, fully_paid, cancelled
        'type' => 'string', // cash, insurance
        'bill_date' => 'datetime',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * All line items in this bill
     */
    public function billItems(): HasMany
    {
        return $this->hasMany(BillItem::class);
    }

    /**
     * All payments for this bill
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * The patient
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * The doctor (if applicable)
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class)->withDefault();
    }

    /**
     * Insurance company (if type = insurance)
     */
    public function insuranceCompany(): BelongsTo
    {
        return $this->belongsTo(InsuranceCompany::class)->withDefault();
    }

    /**
     * Insurance request (if from insurance approval)
     */
    public function insuranceRequest(): BelongsTo
    {
        return $this->belongsTo(InsuranceRequest::class)->withDefault();
    }

    /**
     * All receipts through payments
     */
    public function receipts(): HasManyThrough
    {
        return $this->hasManyThrough(Receipt::class, Payment::class);
    }

    // ==================== SCOPES ====================

    /**
     * Filter by patient
     */
    public function scopeForPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Filter by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Filter by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get unpaid bills
     */
    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['issued', 'partially_paid']);
    }

    /**
     * Get overdue bills
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereIn('status', ['issued', 'partially_paid']);
    }

    // ==================== METHODS ====================

    /**
     * Generate bill number in format BILL-YYYY-0001
     */
    public static function generateBillNumber(): string
    {
        $year = now()->format('Y');
        $lastBill = self::where('bill_number', 'like', "BILL-$year-%")
            ->orderByDesc('bill_number')
            ->first();

        $sequence = 1;
        if ($lastBill) {
            $lastSequence = (int) explode('-', $lastBill->bill_number)[2];
            $sequence = $lastSequence + 1;
        }

        return sprintf('BILL-%d-%04d', $year, $sequence);
    }

    /**
     * Get outstanding balance
     */
    public function getBalance(): float
    {
        return max(0, $this->total_amount - $this->paid_amount);
    }

    /**
     * Check if bill is fully paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'fully_paid';
    }

    /**
     * Check if bill is partially paid
     */
    public function isPartiallyPaid(): bool
    {
        return $this->status === 'partially_paid';
    }

    /**
     * Add line item to bill
     */
    public function addItem(Procedure $procedure, int $quantity, float $price): void
    {
        $this->billItems()->create([
            'procedure_id' => $procedure->id,
            'description' => $procedure->name,
            'quantity' => $quantity,
            'unit_price' => $price,
            'total_price' => $quantity * $price,
        ]);

        // Update bill total
        $newTotal = $this->billItems()->sum('total_price');
        $this->update(['total_amount' => $newTotal]);
    }

    /**
     * Apply payment to bill
     */
    public function applyPayment(float $amount): void
    {
        $newPaidAmount = $this->paid_amount + $amount;

        // Determine new status
        if ($newPaidAmount >= $this->total_amount) {
            $status = 'fully_paid';
            $newPaidAmount = $this->total_amount;
        } elseif ($newPaidAmount > 0) {
            $status = 'partially_paid';
        } else {
            $status = $this->status;
        }

        $this->update([
            'paid_amount' => $newPaidAmount,
            'status' => $status,
        ]);
    }

    /**
     * Create bill from visit
     */
    public static function createFromVisit(Visit $visit): self
    {
        $bill = self::create([
            'bill_number' => self::generateBillNumber(),
            'patient_id' => $visit->patient_id,
            'doctor_id' => $visit->doctor_id,
            'bill_date' => now(),
            'type' => $visit->patient->type,
            'insurance_company_id' => $visit->patient->insurance_company_id,
            'total_amount' => 0,
            'status' => 'draft',
            'created_by' => auth()->id() ?? 1,
        ]);

        // Add procedures from visit to bill
        foreach ($visit->procedures as $procedure) {
            $bill->addItem($procedure, 1, $procedure->pivot->price_at_time);
        }

        return $bill;
    }

    // ==================== EVENTS ====================

    protected static function booted(): void
    {
        // Auto-generate bill number when creating
        static::creating(function ($bill) {
            if (!$bill->bill_number) {
                $bill->bill_number = self::generateBillNumber();
            }
            if (!$bill->created_by) {
                $bill->created_by = auth()->id() ?? 1;
            }
        });
    }

    // Add these relationships and methods to your existing Bill model

public function paymentAllocations(): HasMany
{
    return $this->hasMany(PaymentAllocation::class);
}

public function getAmountDue(): float
{
    return max(0, $this->total_amount - $this->paid_amount);
}

public function canApplyAdvanceCredit(): bool
{
    return !$this->isPaid() && $this->getAmountDue() > 0;
}

public function getPaymentHistory()
{
    return $this->payments()
        ->with('allocations')
        ->where('status', 'completed')
        ->orderBy('payment_date', 'desc')
        ->get();
}

public function applyAdvanceCredit(AdvanceCredit $credit, float $amount = null): void
{
    $amount = $amount ?? min($credit->getAvailableBalance(), $this->getAmountDue());

    if ($amount <= 0 || $credit->isExpired()) {
        return;
    }

    $credit->applyToPayment($amount);
    $this->applyPayment($amount);
}



}
