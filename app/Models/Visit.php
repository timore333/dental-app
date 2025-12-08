<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Visit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'appointment_id',
        'patient_id',
        'doctor_id',
        'visit_date',
        'chief_complaint',
        'diagnosis',
        'treatment_notes',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Associated appointment (if visit was from appointment)
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class)->withDefault();
    }

    /**
     * The patient
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * The doctor who conducted the visit
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * All procedures done in this visit
     */
    public function procedures(): BelongsToMany
    {
        return $this->belongsToMany(
            Procedure::class,
            'visit_procedures',
            'visit_id',
            'procedure_id'
        )->withPivot('price_at_time', 'notes')
         ->withTimestamps();
    }

    /**
     * Bill created from this visit (if any)
     */
    public function bill(): HasOne
    {
        return $this->hasOne(Bill::class);
    }

    // ==================== METHODS ====================

    /**
     * Add procedure to visit
     */
    public function addProcedure(int $procedureId, float $price): void
    {
        $this->procedures()->attach($procedureId, [
            'price_at_time' => $price,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Remove procedure from visit
     */
    public function removeProcedure(int $procedureId): void
    {
        $this->procedures()->detach($procedureId);
    }

    /**
     * Get total cost of all procedures in visit
     */
    public function getTotalCost(): float
    {
        return $this->procedures()
            ->get()
            ->sum(fn ($p) => $p->pivot->price_at_time) ?? 0;
    }
}
