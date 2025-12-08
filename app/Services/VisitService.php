<?php
namespace App\Services;
use App\Models\Visit;
use App\Models\VisitProcedure;

class VisitService
{
    public function createVisit($data)
    {
        return Visit::create($data);
    }

    public function createVisitFromAppointment($appointment)
    {
        return Visit::create([
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
            'visit_date' => now(),
            'created_by' => auth()->id(),
        ]);
    }

    public function addProcedureToVisit($visit, $procedureId, $price)
    {
        return $visit->procedures()->attach($procedureId, ['price' => $price]);
    }

    public function removeProcedureFromVisit($visit, $procedureId)
    {
        return $visit->procedures()->detach($procedureId);
    }

    public function getVisitsTotalCost($visit)
    {
        return $visit->procedures()->sum('pivot.price');
    }

    public function completeVisit($visit)
    {
        $visit->update(['completed_at' => now()]);
        event(new \App\Events\VisitRecorded($visit));
        return $visit;
    }
}
