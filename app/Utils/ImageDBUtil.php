<?php

namespace App\Utils;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;

class ImageDBUtil
{
    public static function create($image, int $type_id, string $type)
    {
        $path = ImageUtil::upload($image);

        [$width, $height] = getimagesize($image);

        $insert = Image::create([
            'name' => $image->getClientOriginalName(),
            'path' => $path,
            'type' => $type,
            'type_id' => $type_id,
            'width' => $width,
            'height' => $height,
        ]);

        return $insert->id;
    }

    public static function uploadImage($images, int $type_id, string $type)
    {
        foreach ($images as $image) {
            ImageDBUtil::create($image, $type_id, $type);
        }
    }

    public static function deleteImage(array $images_delete_ids, int $id, string $type)
    {
        $images = collect(Image::whereIn('id', $images_delete_ids)->where([
            ['type_id', '=', $id],
            ['type', '=', $type]
        ])->get());

        $images->each(function ($item) {
            ImageUtil::delete($item->path);

            Image::destroy($item->id);
        });
    }
}
