<?php

namespace App\Policies;

use App\Models\Collection;
use App\Models\User;

class CollectionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Collection $collection): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Collection $collection): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Collection $collection): bool
    {
        return $user->hasRole('admin');
    }
}
