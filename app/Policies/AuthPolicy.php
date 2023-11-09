<?php

namespace App\Policies;

use App\Models\User;

class AuthPolicy
{
    public static function login(User $user): bool
    {
        return $user->role !== 'admin' && $user->role !== 'client' && !$user->is_confirm;
    }
}
