<?php

namespace App\Policies;

use App\Models\Admin;

class AdminPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        return $user->hasPermissionTo('Read-Admins');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, $admin): bool
    {
        return $user->hasPermissionTo('Read-Admins');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        return $user->hasPermissionTo('Create-Admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, $admin): bool
    {
        return $user->hasPermissionTo('Update-Admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, $admin): bool
    {
        return $user->hasPermissionTo('Delete-Admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore($user, $admin): bool
    {
        return $user->hasPermissionTo('Restore-Admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete($user, $admin): bool
    {
        return $user->hasPermissionTo('ForceDelete-Admin');
    }
}