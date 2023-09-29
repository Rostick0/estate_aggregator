<?php

namespace App\Policies;

use App\Models\File;
use App\Models\FileRelationship;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FileRelationshipPolicy
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
    // public function view(User $user, FileRelationship $fileRelationship): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can create models.
     */
    public static function create(User $user, int $file_id): bool
    {
        return !empty($user) && ($user->id === File::find($file_id)?->user_id);
    }

    /**
     * Determine whether the user can update the model.
     */
    // public function update(User $user, FileRelationship $fileRelationship): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FileRelationship $fileRelationship): bool
    {
        return $user->id === $fileRelationship->file->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, FileRelationship $fileRelationship): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, FileRelationship $fileRelationship): bool
    // {
    //     //
    // }
}
