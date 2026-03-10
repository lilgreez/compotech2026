<?php

namespace App\Policies;

use App\Models\MasterDieset;
use App\Models\User;

class MasterDiesetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MasterDieset $masterDieset): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MasterDieset $masterDieset): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MasterDieset $masterDieset): bool
    {
        // Only admin can delete, and only if no related parts exist
        return $user->hasRole('Admin') && $masterDieset->parts()->count() === 0;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MasterDieset $masterDieset): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MasterDieset $masterDieset): bool
    {
        return $user->hasRole('Admin');
    }
}