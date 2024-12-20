<?php

namespace App\Policies;

use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RecruitmentPolicy
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
    // public function view(User $user, Recruitment $recruitment): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return array_search($user->role, ['realtor', 'agency', 'builder', 'admin']) !== false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Recruitment $recruitment): bool
    {
        return $user->role == 'admin' || $user?->id == $recruitment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Recruitment $recruitment): bool
    {
        return $user->role == 'admin' || $user?->id == $recruitment->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Recruitment $recruitment): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Recruitment $recruitment): bool
    // {
    //     //
    // }
}
