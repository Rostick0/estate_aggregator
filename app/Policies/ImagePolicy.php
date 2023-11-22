<?php

namespace App\Policies;

use App\Models\File;
use App\Models\Image;
use App\Models\User;

class ImagePolicy
{

    public static function create(User $user, ?int $image_id): bool
    {
        if (empty($user)) return false;

        if (!$image_id) return true;

        $image = Image::find($image_id);

        return ($user->id === $image?->user_id);
    }
}
