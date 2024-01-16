<?php

namespace App\Policies;

use App\Models\SiteInfo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SiteInfoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    // public function viewAny(User $user): bool
    // {
    // }

    /**
     * Determine whether the user can view the model.
     */
    // public function view(User $user, SiteInfo $siteInfo): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SiteInfo $siteInfo): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SiteInfo $siteInfo): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SiteInfo $siteInfo): bool
    {
        return $user->role === 'admin';
    }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    public function forceDelete(User $user, SiteInfo $siteInfo): bool
    {
        return $user->role === 'admin';
    }
}
