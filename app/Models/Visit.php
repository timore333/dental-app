<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Visit extends Model
{
    protected $fillable = [
        'appointment_id',
        'patient_id',
        'doctor_id',
        'visit_date',
        'chief_complaint',
        'diagnosis',
        'treatment_notes',
        'created_by',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class)->withDefault();
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

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

    public function bill(): HasOne
    {
        return $this->hasOne(Bill::class);
    }

    // ==================== METHODS ====================

    /**
     * Add procedure to visit with automatic price calculation
     * Determines price based on patient type (insurance or cash)
     */
    public function addProcedure(int $procedureId, ?float $customPrice = null): void
    {
        $procedure = Procedure::find($procedureId);

        if (!$procedure) {
            throw new \Exception("Procedure not found");
        }

        // Determine the price
        if ($customPrice !== null) {
            $price = $customPrice;
        } else {
            $price = $procedure->getPriceForPatient($this->patient);
        }

        if ($price === null) {
            throw new \Exception("No price available for this procedure for the patient type");
        }

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

    /**
     * Get procedures count
     */
    public function getProceduresCount(): int
    {
        return $this->procedures()->count();
    }
}
