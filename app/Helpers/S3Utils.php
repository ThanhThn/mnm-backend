<?php

namespace App\Helpers;

use Aws\S3\MultipartUploader;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    public function uploadLargeFile(string $store, $file, $fileName, $extension)
    {
        $fileName = time() . '_' . $fileName . '.' . $extension;
        $path = $store .  '/' . $fileName;
        Storage::disk('s3')->put($path, $file , 's3');
        return $this->getObjectUrlFromS3($path);
    }

    /**
     * getUrlFromS3
     *
     * @param string|array $pathFile
     * @return void
     */
    public function getObjectUrlFromS3(string|array $pathFile): string|array
    {
        if (empty($pathFile)) {
            return '';
        }
        if (is_array($pathFile)) {
            $arrPathExist = [];
            foreach ($pathFile as $item) {
                if (Storage::disk('s3')->exists($item)) {
                    $arrPathExist[] = Storage::disk('s3')->url($item);
                }
            }

            if(!empty($arrPathExist)) {
                return $arrPathExist;
            }
        }
        if (is_string($pathFile) && Storage::disk('s3')->exists($pathFile)) {
            return Storage::disk('s3')->url($pathFile);;
        }
    }
}
