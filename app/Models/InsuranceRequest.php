<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InsuranceRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'insurance_company_id',
        'doctor_id',
        'request_date',
        'request_document_id',
        'request_document_type',
        'status',
        'created_by',
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'status' => 'string', // submitted, approved, rejected, partial
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * The patient
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * The insurance company
     */
    public function insuranceCompany(): BelongsTo
    {
        return $this->belongsTo(InsuranceCompany::class);
    }

    /**
     * The requesting doctor
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * The insurance approval (if approved)
     */
    public function approval(): HasOne
    {
        return $this->hasOne(InsuranceApproval::class);
    }

    // ==================== SCOPES ====================

    /**
     * Filter by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
