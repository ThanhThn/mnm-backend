<?php

namespace App\Http\Controllers;

use App\Helpers\S3Utils;
use App\Http\Request\Image\UploadRequest;
use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SoundController extends Controller
{
    function upload(Request $request, S3Utils $utils)
    {
        $path = $utils->uploadLargeFile( 'sounds', $request->file('file'));
//        $image = [
//            'path' => $path,
//            'title' => $request->title,
//        ];
//
//        if(!empty($request->description)){
//            $image['description'] = $request->description;
//        }

//        $result = Image::create($image);

//        if($result){
//            return response()->json([
//                'status'=> JsonResponse::HTTP_OK,
//                'body' => [
//                    'data' => $result
//                ]], JsonResponse::HTTP_OK);
//        }
        return response()->json([
            'status'=> JsonResponse::HTTP_OK,
            'body' => [
                'path' => $path
            ]
        ], JsonResponse::HTTP_OK);
    }
}
