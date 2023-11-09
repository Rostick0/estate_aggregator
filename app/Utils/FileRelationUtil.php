<?php

namespace App\Utils;

use App\Policies\FileRelationshipPolicy;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class FileRelationUtil
{
    public static function createAndDelete(MorphMany|MorphOne $model, array $file_ids)
    {
        $model->delete();

        foreach ($file_ids as $file_id) {
            // if (!FileRelationshipPolicy::create(auth()->user(), $file_id)) continue;

            $model->create([
                'file_id' => $file_id
            ]);
        }
    }
}
