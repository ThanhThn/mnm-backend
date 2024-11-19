<?php

namespace App\Http\Request\Image;

use App\Http\Request\BaseRequest;

class UploadRequest extends BaseRequest
{
    public function rules(){
        return [
            'file' => 'required|file|mimes:jpeg,jpg,png,gif,webp',
            'title' => 'required|string',
            'description' => 'nullable|string',
        ];
    }
}
