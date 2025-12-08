<?php
namespace App\Policies;
use App\Models\User;
use App\Models\Visit;

class VisitPolicy
{
    public function viewAny(User $user)
    {
        return in_array($user->role, ['doctor', 'accountant', 'admin']);
    }

    public function view(User $user, Visit $visit)
    {
        return $user->doctor?->id === $visit->doctor_id
            || $user->role === 'accountant'
            || $user->role === 'admin';
    }

    public function create(User $user)
    {
        return in_array($user->role, ['doctor', 'admin']);
    }

    public function update(User $user, Visit $visit)
    {
        if ($visit->bill_id !== null) {
            return false;
        }

        return $user->doctor?->id === $visit->doctor_id || $user->role === 'admin';
    }

    public function delete(User $user, Visit $visit)
    {
        return $user->role === 'admin';
    }
}
