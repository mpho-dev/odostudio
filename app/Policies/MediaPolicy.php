<?php

namespace App\Policies;

use App\Models\Media;
use App\Models\User;

class MediaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Everyone can view portfolio
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Media $media): bool
    {
        return true; // Everyone can view portfolio
    }

    /**
     * Determine whether the user can create models.
     *
     * Originally only administrators were allowed to push files into the
     * archive, but photographers (and future roles) should also be able to
     * submit assets.  Non‑privileged users are still blocked.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'crew']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Media $media): bool
    {
        return $user->hasRole('admin') || $user->id === $media->uploaded_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Media $media): bool
    {
        return $user->hasRole('admin') || $user->id === $media->uploaded_by;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Media $media): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Media $media): bool
    {
        return $user->hasRole('admin');
    }
}
