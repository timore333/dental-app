<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Appointment;

class AppointmentPolicy
{
    public function viewAny(User $user)
    {
        return $user->isAdmin() || $user->isDoctor() || $user->isReceptionist();
    }

    public function view(User $user, Appointment $appointment)
    {
        return $user->id === $appointment->created_by
            || $user->doctor?->id === $appointment->doctor_id
            || $user->isAdmin();
    }

    public function create(User $user)
    {
        // Use helper methods - they handle case insensitivity
        return $user->isAdmin() || $user->isReceptionist();
    }

    public function update(User $user, Appointment $appointment)
    {
        if ($appointment->status !== 'scheduled') {
            return false;
        }

        return $user->isAdmin() || $user->id === $appointment->created_by;
    }

    public function delete(User $user, Appointment $appointment)
    {
        if ($appointment->status !== 'scheduled') {
            return false;
        }

        return $user->isAdmin() || $user->id === $appointment->created_by;
    }

    public function complete(User $user, Appointment $appointment)
    {
        return $user->isDoctor() || $user->isAdmin();
    }
}
