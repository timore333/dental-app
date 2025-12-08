<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsuranceApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'insurance_request_id',
        'approval_document_id',
        'approval_document_type',
        'approval_date',
        'approved_procedures',
        'rejected_procedures',
        'approved_amount',
        'approval_notes',
        'created_by',
    ];

    protected $casts = [
        'approval_date' => 'datetime',
        'approved_procedures' => 'array',
        'rejected_procedures' => 'array',
        'approved_amount' => 'decimal:2',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * The insurance request
     */
    public function insuranceRequest(): BelongsTo
    {
        return $this->belongsTo(InsuranceRequest::class);
    }

    public function getApprovedProceduresAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function getRejectedProceduresAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }
}
