<?php

namespace App\Http\Request\Chapter;

use App\Http\Request\BaseRequest;

class CreateChapterRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'title' => 'required|string|max:100|unique:chapters,title',
            'content' => 'required|string',
            'status' => 'required|integer',
            'story_id' => 'required|uuid|exists:stories,id'
        ];
    }
}
