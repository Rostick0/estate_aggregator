<?php

namespace App\Utils;

use App\Models\File;
use App\Policies\FileRelationshipPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class FileUtil
{
    public static function create(MorphMany|MorphOne $model, array $file_ids)
    {
        foreach ($file_ids as $file_id) {
            if (!FileRelationshipPolicy::create(auth()->user(), $file_id)) continue;

            $model->create([
                'file_id' => $file_id
            ]);
        }
    }

    public static function delete(MorphMany|MorphOne $model, array $file_relation_ids)
    {
        $delete_ids = [];

        foreach ($file_relation_ids as $file_relation_id) {
            if (!FileRelationshipPolicy::create(auth()->user(), $file_relation_id)) continue;

            $delete_ids[] = $file_relation_id;
        }

        $model->whereIn('id', $delete_ids)->delete();
    }
}
