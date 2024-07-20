<?php

namespace App\Policies;

use App\Models\ShippingPoint;

class ShippingPointPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        return $user->hasPermissionTo('Read-Shipping-Points');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, ShippingPoint $shippingPoint): bool
    {
        return $user->hasPermissionTo('Read-Shipping-Points');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        return $user->hasPermissionTo('Create-Shipping-Point');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, ShippingPoint $shippingPoint): bool
    {
        return $user->hasPermissionTo('Update-Shipping-Point');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, ShippingPoint $shippingPoint): bool
    {
        return $user->hasPermissionTo('Delete-Shipping-Point');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore($user, ShippingPoint $shippingPoint): bool
    {
        return $user->hasPermissionTo('Restore-Shipping-Point');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete($user, ShippingPoint $shippingPoint): bool
    {
        return $user->hasPermissionTo('ForceDelete-Shipping-Point');
    }
}