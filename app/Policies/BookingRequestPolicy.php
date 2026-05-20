<?php

namespace App\Policies;

use App\Models\BookingRequest;
use App\Models\User;

class BookingRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['manager', 'crew']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BookingRequest $bookingRequest): bool
    {
        return $user->hasRole('manager') || $user->hasRole('crew');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Public can create booking requests via form
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BookingRequest $bookingRequest): bool
    {
        return $user->hasRole('manager');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BookingRequest $bookingRequest): bool
    {
        return $user->hasRole('manager');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BookingRequest $bookingRequest): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BookingRequest $bookingRequest): bool
    {
        return $user->hasRole('admin');
    }
}
