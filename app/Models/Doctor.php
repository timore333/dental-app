<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'license_number',
        'specialization',
        'phone',
        'bio',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Doctor's user account
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * All visits by this doctor
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * All appointments with this doctor
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * All patients treated by this doctor (through visits)
     */
    public function patients(): HasManyThrough
    {
        return $this->hasManyThrough(Patient::class, Visit::class);
    }

    /**
     * Insurance requests created by this doctor
     */
    public function insuranceRequests(): HasMany
    {
        return $this->hasMany(InsuranceRequest::class);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get doctor's full name
     */
    public function getFullNameAttribute(): string
    {
        return $this->user->name ?? 'Unknown';
    }

    // ==================== SCOPES ====================

    /**
     * Get only active doctors
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Filter by specialization
     */
    public function scopeBySpecialization($query, string $specialization)
    {
        return $query->where('specialization', $specialization);
    }
}
