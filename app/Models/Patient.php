<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'file_number',
        'first_name',
        'middle_name',
        'last_name',
        'phone',
        'email',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'type',
        'insurance_company_id',
        'insurance_card_number',
        'insurance_policyholder',
        'insurance_expiry_date',
        'notes',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'type' => 'string', // cash, insurance
        'gender' => 'string', // male, female
        'date_of_birth' => 'date',
        'insurance_expiry_date' => 'date',
        'is_active' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Patient's primary doctor (optional)
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class)->withDefault();
    }

    /**
     * All appointments for this patient
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * All visits for this patient
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * All bills for this patient
     */
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    /**
     * Insurance company (if type = insurance)
     */
    public function insuranceCompany(): BelongsTo
    {
        return $this->belongsTo(InsuranceCompany::class)->withDefault();
    }

    /**
     * All documents (insurance cards, etc.) - polymorphic
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Patient's financial account - polymorphic
     */
    public function account(): MorphOne
    {
        return $this->morphOne(Account::class, 'accountable');
    }

    // ==================== ACCESSORS & MUTATORS ====================

    /**
     * Get full name (accessor)
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    /**
     * Get patient age (accessor)
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }
        return now()->diffInYears($this->date_of_birth);
    }

    // ==================== SCOPES ====================

    /**
     * Get only active patients
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Filter by patient type (cash or insurance)
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Filter by insurance company
     */
    public function scopeByInsuranceCompany($query, int $id)
    {
        return $query->where('insurance_company_id', $id);
    }

    // ==================== METHODS ====================

    /**
     * Check if patient pays with cash
     */
    public function isCash(): bool
    {
        return $this->type === 'cash';
    }

    /**
     * Check if patient uses insurance
     */
    public function isInsurance(): bool
    {
        return $this->type === 'insurance';
    }

    /**
     * Get file number (returns existing or generates new)
     */
    public function getFileNumber(): ?int
    {
        if (!$this->file_number) {
            $this->file_number = $this->generateNextFileNumber();
            $this->save();
        }
        return $this->file_number;
    }

    /**
     * Generate next file number (last + 1)
     */
    public static function generateNextFileNumber(): int
    {
        $lastPatient = self::orderByDesc('file_number')->first();
        $lastNumber = $lastPatient?->file_number ?? 0;
        return $lastNumber + 1;
    }

    // ==================== EVENTS ====================

    protected static function booted(): void
    {
        // Auto-set created_by when creating patient
        static::creating(function ($patient) {
            if (!$patient->created_by) {
                $patient->created_by = auth()->id() ?? 1;
            }
        });
    }
}
