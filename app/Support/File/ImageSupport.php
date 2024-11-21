<?php

namespace App\Support\File;

use App\Helpers\S3Utils;
use App\Models\Image;

class ImageSupport
{
    static function delete($id)
    {
        $image = Image::find($id);
        S3Utils::delete($image->path);
        $image->delete();
    }
}
