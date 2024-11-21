<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class S3Utils
{
    static function upload($file, $store)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Tải file lên Amazon S3
        $path = $file->storeAs($store, $fileName, 's3');

        // Lấy URL của file vừa tải lên
        return Storage::disk('s3')->url($path);
    }

    static function delete($filePath)
    {
        $key = parse_url($filePath, PHP_URL_PATH);
        $key = ltrim($key, '/');
        return Storage::disk('s3')->delete($key);
    }
}
