<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;

class ImagePolicy
{

    public static function create(User $user, ?int $file_id): bool
    {
        if (empty($user)) return false;

        if (!$file_id) return true;

        $file = File::where([
            ['id', '=', $file_id],
            ['type', 'LIKE', 'image/%']
        ])
            ->first();

        return ($user->id === $file?->user_id);
    }
}
