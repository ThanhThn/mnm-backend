<?php

namespace App\Http\Request\Story;

use App\Http\Request\BaseRequest;

class CreateStoryRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:100|unique:stories,name',
            'description' => 'required|string',
            'active_status' => 'required|integer',
            'completed_status' => 'required|integer',
            'thumbnail_id' => 'required|uuid|exists:images,id',
        ];
    }
}
