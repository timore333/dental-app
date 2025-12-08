<?php
namespace App\Services;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentService
{
    public function createAppointment($data)
    {
        return Appointment::create($data);
    }

    public function updateAppointment($appointment, $data)
    {
        $appointment->update($data);
        return $appointment;
    }

    public function cancelAppointment($appointment)
    {
        $appointment->update(['status' => 'cancelled']);
        return $appointment;
    }

    public function markCompleted($appointment)
    {
        $appointment->update(['status' => 'completed']);
        event(new \App\Events\AppointmentCompleted($appointment));
        return $appointment;
    }

    public function markNoShow($appointment)
    {
        $appointment->update(['status' => 'no-show']);
        return $appointment;
    }

    public function getUpcomingAppointments($doctorId = null, $days = 7)
    {
        $query = Appointment::where('appointment_date', '>=', now())
            ->where('appointment_date', '<=', now()->addDays($days))
            ->where('status', 'scheduled');

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        return $query->orderBy('appointment_date')->get();
    }

    public function getAppointmentsByDate($date)
    {
        return Appointment::whereDate('appointment_date', $date)->get();
    }

    public function checkConflicts($doctorId, $appointmentDate, $duration = 30)
    {
        $endTime = (new Carbon($appointmentDate))->addMinutes($duration);
        return Appointment::where('doctor_id', $doctorId)
            ->whereBetween('appointment_date', [$appointmentDate, $endTime])
            ->where('status', 'scheduled')
            ->exists();
    }
}
?>
