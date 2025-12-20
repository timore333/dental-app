<?php
namespace App\Policies;
use App\Models\User;
use App\Models\Visit;

class VisitPolicy
{
    public function viewAny(User $user)
    {
        return $user->isAdmin() || $user->isDoctor() || $user->isReceptionist();
    }

    public function view(User $user, Visit $visit)
    {
        return $user->doctor?->id === $visit->doctor_id
            || $user->isAccountant()
            || $user->isAdmin();
    }

    public function create(User $user)
    {
       return $user->isAdmin() || $user->isDoctor();
    }

    public function update(User $user, Visit $visit)
    {
        if ($visit->bill_id !== null) {
            return false;
        }

        return $user->doctor?->id === $visit->doctor_id || $user->isAdmin();
    }

    public function delete(User $user, Visit $visit)
    {
       return $user->isAdmin();
    }
}
