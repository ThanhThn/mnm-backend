<?php

namespace App\Http\Request\Chapter;

use App\Http\Request\BaseRequest;

class CreateChapterRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'title' => 'required|string|unique:chapters,title|max:100',
            'content' => 'required|string',
            'status' => 'required|integer',
            'story_id' => 'required|uuid|exists:stories,id',
            'sound' => 'nullable|file|mimes:mp3,wav,ogg,aac,flac|max:10240',
        ];
    }
}
