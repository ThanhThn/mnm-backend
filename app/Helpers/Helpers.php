<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Helpers
{
    public static function createSlug($text)
    {
        $slug = strtolower($text);
        $slug = preg_replace(
            ['/á|Á|à|À|ả|Ả|ã|Ã|ạ|Ạ|ă|Ă|ắ|Ắ|ằ|Ằ|ẳ|Ẳ|ẵ|Ẵ|ặ|Ặ|â|Â|ấ|Ấ|ầ|Ầ|ẩ|Ẩ|ẫ|Ẫ|ậ|Ậ/', '/đ|Đ/', '/é|É|è|È|ẻ|Ẻ|ẽ|Ẽ|ẹ|Ẹ|ê|Ê|ế|Ế|ề|Ề|ể|Ể|ễ|Ễ|ệ|Ệ/', '/í|Í|ì|Ì|ỉ|Ỉ|ĩ|Ĩ|ị|Ị/', '/ó|Ó|ò|Ò|ỏ|Ỏ|õ|Õ|ọ|Ọ|ô|Ô|ố|Ố|ồ|Ồ|ổ|Ổ|ỗ|Ỗ|ộ|Ộ|ơ|Ơ|ớ|Ớ|ờ|Ờ|ở|Ở|ỡ|Ỡ|ợ|Ợ/', '/ú|Ú|ù|Ù|ủ|Ủ|ũ|Ũ|ụ|Ụ|ư|Ư|ứ|Ứ|ừ|Ừ|ử|Ử|ữ|Ữ|ự|Ự/', '/ý|Ý|ỳ|Ỳ|ỷ|Ỷ|ỹ|Ỹ|ỵ|Ỵ/'],
            ['a', 'd', 'e', 'i', 'o', 'u', 'y'],
            $slug
        );
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }

    public static function response($status, $message = '', $data = null, array $options = [])
    {
        $response = [
            'status' => $status,
            'body' => [
            ]
        ];

        if (!empty($message)) {
            $response['body']['message'] = $message;
        }
        if (!empty($data)) {
            $response['body']['data'] =  $data;
        }

        foreach ($options as $option => $value) {
            $response['body'][$option] = $value;
        }
        return response()->json($response, JsonResponse::HTTP_OK);
    }

    public static function getFileNameAnExtension (UploadedFile $file){
        $originalName = $file->getClientOriginalName();
        $fileName = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        return [
            'extension' => $extension,
            'name' => $fileName
        ];
    }

    static function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }
}
