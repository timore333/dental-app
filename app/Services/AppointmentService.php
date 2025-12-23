<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentService
{
    /**
     * Create appointment
     */
    public function create(array $data): Appointment
    {
        $start = Carbon::parse($data['start']);
        $data['end'] = $start->copy()->addMinutes(Appointment::APPOINTMENT_DURATION);

        // Move authorization check FIRST (before conflicts)
        if (!auth()->user()->can('create', Appointment::class)) {
            throw new \Exception(__('Unauthorized'));
        }

        // Check for conflicts
        if ($data['doctor_id'] && $this->checkConflicts($data['doctor_id'], $start)) {
            throw new \Exception(__('Time slot conflict detected'));
        }

        if ($start->isPast()) {
            throw new \Exception(__('appointment must be in the future'));
        }

        return Appointment::create($data);
    }

    /**
     * Update appointment
     */
    public function update(Appointment $appointment, array $data): Appointment
    {
        $newStart = Carbon::parse($data['start']);
        $data['end'] = $newStart->copy()->addMinutes(Appointment::APPOINTMENT_DURATION);

        // Check for conflicts
        $this->checkForUpdateConflicts($appointment, $newStart);


        $appointment->update($data);
        return $appointment;
    }

    /**
     * Reschedule appointment
     */
    public function reschedule(Appointment $appointment, $newDateTime): Appointment
    {
        $newStart = Carbon::parse($newDateTime);
        $newEnd = $newStart->copy()->addMinutes(Appointment::APPOINTMENT_DURATION);


        // Check for conflicts
        $this->checkForUpdateConflicts($appointment, $newStart);

        $appointment->update(['start' => $newStart, 'end' => $newEnd]);
        return $appointment;
    }

    /**
     * Cancel appointment
     */
    public function cancelAppointment(Appointment $appointment): Appointment
    {
        $appointment->update(['status' => 'cancelled']);
        return $appointment;
    }

    /**
     * Mark appointment as completed
     */
    public function markCompleted(Appointment $appointment): Appointment
    {
        $appointment->update(['status' => 'completed']);
        return $appointment;
    }

    /**
     * Mark appointment as no-show
     */
    public function markNoShow(Appointment $appointment): Appointment
    {
        $appointment->update(['status' => 'no-show']);
        return $appointment;
    }

    /**
     * Get upcoming appointments
     */
    public function getUpcomingAppointments($doctorId = null, $days = 7)
    {
        $query = Appointment::where('start', '>=', now())
            ->where('start', '<=', now()->addDays($days))
            ->where('status', 'scheduled');

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        return $query->orderBy('start')->get();
    }

    /**
     * Get appointments by date
     */
    public function getAppointmentsByDate($date)
    {
        return Appointment::whereDate('start', $date)->get();
    }

    /**
     * Check for time slot conflicts
     */
    public function checkConflicts($doctorId, $appointmentDate, $appointmentId = null): bool
    {
        $endTime = (new Carbon($appointmentDate))->addMinutes(Appointment::APPOINTMENT_DURATION);

        $query = Appointment::where('doctor_id', $doctorId)
            ->whereBetween('start', [$appointmentDate, $endTime])
            ->where('status', 'scheduled');

        // Exclude current appointment from conflict check
        if ($appointmentId) {
            $query->where('id', '!=', $appointmentId);
        }

        return $query->exists();
    }

    /**
     * Get appointments for calendar view
     */
    public function getAppointmentsForCalendar($filters = [])
    {
        $query = Appointment::with(['patient', 'doctor']);

        if (isset($filters['doctor_id'])) {
            $query->where('doctor_id', $filters['doctor_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['start_date'])) {
            $query->where('start', '>=', Carbon::parse($filters['start_date']));
        }

        if (isset($filters['end_date'])) {
            $query->where('end', '<=', Carbon::parse($filters['end_date'])->endOfDay());
        }

        return $query->orderBy('start')->get();
    }

    /**
     * @param Appointment $appointment
     * @param Carbon $newStart
     * @return void
     * @throws \Exception
     */
    public function checkForUpdateConflicts(Appointment $appointment, Carbon $newStart): void
    {
        if ($appointment->doctor_id && $this->checkConflicts($appointment->doctor_id, $newStart, $appointment->id)) {
            throw new \Exception(__('Time slot conflict detected'));
        }

        if (!auth()->user()->can('update', $appointment)) {
            abort(403);
        }

        if ($appointment->status != 'scheduled') {
            throw new \Exception(__('cannot reschedule completed appointments'));
        }

        if ($newStart->isPast()) {
            throw new \Exception(__('appointment must be in the future'));
        }
    }
}
