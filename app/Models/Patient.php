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
    use SoftDeletes;
    use HasFactory;

    // ==================== PROPERTIES ====================

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
        'country',
        'job',
        'category',
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
        'date_of_birth' => 'date',
        'insurance_expiry_date' => 'date',
        'is_active' => 'boolean',
    ];
    protected $appends = ['name'];

    // ==================== CONSTANTS ====================

    const CATEGORIES = ['normal', 'exacting', 'vip', 'special'];
    const PAYMENT_TYPES = ['cash', 'insurance'];
    const GENDERS = ['male', 'female'];

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

    /**
     * Who created this patient
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Who last updated this patient
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
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

    /**
     * Get age display string
     */
    public function getAgeDisplayAttribute(): string
    {
        return $this->age ? "{$this->age} years" : 'N/A';
    }

    /**
     * Get category display (formatted)
     */
    public function getCategoryLabelAttribute(): string
    {
        return ucfirst($this->category);
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
     * Filter by patient category
     */
    public function scopeByCategory($query, string $category)
    {
        if ($category === 'all' || empty($category)) {
            return $query;
        }
        return $query->where('category', $category);
    }

    /**
     * Filter by city/location
     */
    public function scopeByCity($query, string $city)
    {
        if (empty($city)) {
            return $query;
        }
        return $query->where('city', 'like', "%{$city}%");
    }

    /**
     * Filter by job
     */
    public function scopeByJob($query, string $job)
    {
        if (empty($job)) {
            return $query;
        }
        return $query->where('job', 'like', "%{$job}%");
    }

    /**
     * Search by name, email, or phone
     */
    public function scopeSearch($query, string $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where('first_name', 'like', "%{$search}%")
            ->orWhere('last_name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('phone', 'like', "%{$search}%");
    }

    /**
     * Filter by birth date range
     */
    public function scopeByBirthDateRange($query, ?string $fromDate, ?string $toDate)
    {
        if ($fromDate) {
            $query->whereDate('date_of_birth', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('date_of_birth', '<=', $toDate);
        }

        return $query;
    }

    /**
     * Filter by gender
     */
    public function scopeByGender($query, string $gender)
    {
        if (empty($gender) || $gender === 'all') {
            return $query;
        }
        return $query->where('gender', $gender);
    }

    /**
     * Filter by payment type
     */
    public function scopeByType($query, string $type)
    {
        if (empty($type) || $type === 'all') {
            return $query;
        }
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
     * Check if patient is VIP
     */
    public function isVip(): bool
    {
        return $this->category === 'vip';
    }

    /**
     * Check if patient is exacting (requires special attention)
     */
    public function isExacting(): bool
    {
        return $this->category === 'exacting';
    }

    /**
     * Check if patient is special (special needs/cases)
     */
    public function isSpecial(): bool
    {
        return $this->category === 'special';
    }

    /**
     * Get file number (returns existing or generates new)
     */
    public function getFileNumber(): ?int
    {
        if (!$this->file_number) {
            $this->file_number = self::generateNextFileNumber();
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

    /**
     * Get category badge color
     */
    public function getCategoryColorAttribute(): string
    {
        return match ($this->category) {
            'normal' => 'gray',
            'exacting' => 'yellow',
            'vip' => 'purple',
            'special' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get category icon
     */
    public function getCategoryIconAttribute(): string
    {
        return match ($this->category) {
            'normal' => '👤',
            'exacting' => '⚠️',
            'vip' => '👑',
            'special' => '🌟',
            default => '👤',
        };
    }

    public function getName(){
        $middleName = $this->middle_name ?? '';
        return $this->first_name .' '. $middleName .' '. $this->last_name;
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

        // Auto-set updated_by when updating patient
        static::updating(function ($patient) {
            $patient->updated_by = auth()->id() ?? 1;
        });
    }

    public function getNameAttribute()
    {
        return $this->getName();
    }
}
