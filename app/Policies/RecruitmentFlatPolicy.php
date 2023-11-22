<?php

namespace App\Policies;

use App\Models\Recruitment;
use App\Models\User;

class RecruitmentFlatPolicy
{
    public static function create(int $recruitment_id)
    {
        return auth()?->id() ==  Recruitment::find($recruitment_id)->user_id;
    }
}
