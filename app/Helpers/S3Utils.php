<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class S3Utils
{
    static function upload($file)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Tải file lên Amazon S3
        $path = $file->storeAs(env('AWS_BUCKET'), $fileName, 's3');

        // Lấy URL của file vừa tải lên
        return Storage::disk('s3')->url($path);
    }

    static function delete($filePath)
    {
        // Xóa file khỏi Amazon S3
        return Storage::disk('s3')->delete($filePath);
    }
}
