<?php

namespace App\Http\Request\Advertisement;

use App\Http\Request\BaseRequest;

class AdsRequest extends BaseRequest
{
    public function rules(){
        return [
            'link' => 'required|url',
            'picture_id' => 'required|uuid|exists:images,id',
        ];
    }
}
