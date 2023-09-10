<?php

namespace App\Utils;

use Illuminate\Support\Facades\Storage;

class ImageUtil
{

    private static function getUploadPath()
    {
        return 'upload/image';
    }
    public static function upload($image)
    {
        $upload_path = ImageUtil::getUploadPath();

        $extension = $image->getClientOriginalExtension();
        $random_name = random_int(1000, 9999) . time() . '.' . $extension;
        Storage::disk('public')->put($upload_path, $random_name);

        return $upload_path . $random_name;
    }

    public static function delete($image_path)
    {
        Storage::disk('public')->delete($image_path);
    }
}
