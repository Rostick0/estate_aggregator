<?php

namespace App\Policies;

use App\Models\ApplicationCompany;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ApplicationCompanyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    // public function viewAny(User $user): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can view the model.
     */
    // public function view(User $user, ApplicationCompany $applicationCompany): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'realtor';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ApplicationCompany $applicationCompany): bool
    {
        return array_search($user->role, ['agency', 'builder']) && $applicationCompany->company_id == $user->company_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ApplicationCompany $applicationCompany): bool
    {
        return array_search($user->role, ['agency', 'builder']) && $applicationCompany->company_id == $user->company_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, ApplicationCompany $applicationCompany): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(User $user, ApplicationCompany $applicationCompany): bool
    // {
    //     //
    // }
}
