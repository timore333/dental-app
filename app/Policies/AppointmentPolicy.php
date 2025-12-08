<?php
namespace App\Policies;
use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    public function viewAny(User $user)
    {
        return in_array($user->role, ['receptionist', 'doctor', 'admin']);
    }

    public function view(User $user, Appointment $appointment)
    {
        return $user->id === $appointment->created_by
            || $user->doctor?->id === $appointment->doctor_id
            || $user->role === 'admin';
    }

    public function create(User $user)
    {
        return in_array($user->role, ['receptionist', 'admin']);
    }

    public function update(User $user, Appointment $appointment)
    {
        if ($appointment->status !== 'scheduled') {
            return false;
        }

        return $user->id === $appointment->created_by || $user->role === 'admin';
    }

    public function delete(User $user, Appointment $appointment)
    {
        if ($appointment->status !== 'scheduled') {
            return false;
        }

        return $user->id === $appointment->created_by || $user->role === 'admin';
    }

    public function complete(User $user, Appointment $appointment)
    {
        return $user->doctor?->id === $appointment->doctor_id || $user->role === 'admin';
    }
}
