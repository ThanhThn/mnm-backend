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
            'status' => 'required|integer',
            'thumbnail_id' => 'required|uuid|exists:images,id|unique:stories,thumbnail_id',
            'category_ids' => 'required|array',
            'category_ids.*' => 'uuid|distinct|exists:categories,id',
            'author_id' => 'required|uuid|exists:authors,id'
        ];
    }
}
