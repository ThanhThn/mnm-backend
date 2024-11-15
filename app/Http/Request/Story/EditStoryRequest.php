<?php

namespace App\Http\Request\Story;

use App\Http\Request\BaseRequest;

class EditStoryRequest extends BaseRequest
{
    public function rules()
    {
        $storyId = $this->route('story');
        return [
            'id' => 'required|uuid|exists:stories,id',
            'name' => 'required|string|max:100|unique:stories,name,' . $storyId . ',id',
            'description' => 'required|string',
            'active_status' => 'required|integer',
            'completed_status' => 'required|integer',
            'thumbnail_id' => 'required|uuid|exists:images,id',
        ];
    }
}
