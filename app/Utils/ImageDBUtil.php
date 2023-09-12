<?php

namespace App\Utils;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;

class ImageDBUtil
{
    public static function uploadImage($images, Model $model, string $type)
    {
        foreach ($images as $image) {
            $path = ImageUtil::upload($image);

            [$width, $height] = getimagesize($image);

            $model->images()->create([
                'name' => $image->getClientOriginalName(),
                'path' => $path,
                'type' => $type,
                'width' => $width,
                'height' => $height,
            ]);
        }
    }

    public static function deleteImage(array $images_delete_ids, int $id)
    {
        $images = collect(Image::whereIn('id', $images_delete_ids)->where('type_id', $id)->get());

        $images->each(function ($item) {
            ImageUtil::delete($item->path);

            Image::destroy($item->id);
        });
    }
}