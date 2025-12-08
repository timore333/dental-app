<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'status',
        'reason',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'status' => 'string', // scheduled, completed, cancelled, no-show
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
     * The assigned doctor (optional)
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class)->withDefault();
    }

    /**
     * Associated visit (if appointment was completed)
     */
    public function visit(): HasOne
    {
        return $this->hasOne(Visit::class);
    }

    // ==================== SCOPES ====================

    /**
     * Get upcoming appointments
     */
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', now())
            ->where('status', 'scheduled');
    }

    /**
     * Get past appointments
     */
    public function scopePast($query)
    {
        return $query->where('appointment_date', '<', now());
    }

    /**
     * Filter by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Filter by date
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('appointment_date', $date);
    }

    /**
     * Filter by doctor
     */
    public function scopeByDoctor($query, int $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    // ==================== METHODS ====================

    /**
     * Mark appointment as completed
     */
    public function markCompleted(): bool
    {
        return $this->update(['status' => 'completed']);
    }

    /**
     * Mark appointment as cancelled
     */
    public function markCancelled(): bool
    {
        return $this->update(['status' => 'cancelled']);
    }

    /**
     * Mark appointment as no-show
     */
    public function markNoShow(): bool
    {
        return $this->update(['status' => 'no-show']);
    }
}
