<?php

namespace App\Policies;

use App\Models\EquipmentCheckout;
use App\Models\User;

class EquipmentCheckoutPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    public function view(User $user, EquipmentCheckout $checkout): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('manager')) {
            return true;
        }

        if ($user->hasRole('crew')) {
            return $checkout->user_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    public function update(User $user, EquipmentCheckout $checkout): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    public function delete(User $user, EquipmentCheckout $checkout): bool
    {
        return $user->hasRole('admin');
    }

    public function checkIn(User $user, EquipmentCheckout $checkout): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('crew')) {
            return $checkout->user_id === $user->id;
        }

        return false;
    }

    public function viewMyGear(User $user): bool
    {
        return $user->hasRole('crew');
    }

    public function viewOverdue(User $user): bool
    {
        return $user->hasRole('admin');
    }
}
