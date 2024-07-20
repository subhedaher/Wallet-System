<?php

namespace App\Policies;

use App\Models\PaymentCategory;

class PaymentCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        return $user->hasPermissionTo('Read-Payment-Categories');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, PaymentCategory $paymentCategory): bool
    {
        return $user->hasPermissionTo('Read-Payment-Categories');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        return $user->hasPermissionTo('Create-Payment-Category');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, PaymentCategory $paymentCategory): bool
    {
        return $user->hasPermissionTo('Update-Payment-Category');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, PaymentCategory $paymentCategory): bool
    {
        return $user->hasPermissionTo('Delete-Payment-Category');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore($user, PaymentCategory $paymentCategory): bool
    {
        return $user->hasPermissionTo('Restore-Payment-Category');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete($user, PaymentCategory $paymentCategory): bool
    {
        return $user->hasPermissionTo('ForceDelete-Payment-Category');
    }
}
