<?php

namespace App\Http\Request\Chapter;

use App\Http\Request\BaseRequest;

class ListChapterRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'story_id' => 'nullable|exists:stories,id',
            'limit' => 'nullable|integer|min:1'
        ];
    }
}
