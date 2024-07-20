<?php

namespace App\Policies;

use App\Models\PaymentProvider;

class PaymentProviderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        return $user->hasPermissionTo('Read-Payment-Providers');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, PaymentProvider $paymentProvider): bool
    {
        return $user->hasPermissionTo('Read-Payment-Providers');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        return $user->hasPermissionTo('Create-Payment-Provider');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, PaymentProvider $paymentProvider): bool
    {
        return $user->hasPermissionTo('Update-Payment-Provider');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, PaymentProvider $paymentProvider): bool
    {
        return $user->hasPermissionTo('Delete-Payment-Provider');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore($user, PaymentProvider $paymentProvider): bool
    {
        return $user->hasPermissionTo('restore-Payment-Provider');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete($user, PaymentProvider $paymentProvider): bool
    {
        return $user->hasPermissionTo('ForceDelete-Payment-Provider');
    }
}