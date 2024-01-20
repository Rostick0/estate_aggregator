<?php

namespace App\Policies;

use App\Models\Recruitment;
use App\Models\RecruitmentFlat;
use App\Models\User;

class RecruitmentFlatPolicy
{
    public static function create(int $recruitment_id)
    {
        return auth()?->id() ==  Recruitment::find($recruitment_id)->user_id;
    }

    public function delete(User $user, RecruitmentFlat $recruitment_flat) {
        return $user->role == 'admin' || $user?->id == $recruitment_flat->recruitment->user_id;
    }
}
