<?php

namespace App\Http\Request\Story;

use Illuminate\Validation\Rule;
use App\Http\Request\BaseRequest;
use App\Models\Story;

class EditStoryRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'id' => 'required|uuid|exists:stories,id',
            'name' => ['required', 'string', 'max:100', Rule::unique((new Story())->getTable())->ignore($this->id ?? null)],
            'description' => 'required|string',
            'status' => 'required|integer',
            'author_id' => 'required|uuid|exists:authors,id',
            'category_ids' => 'required|array',
            'category_ids.*' => 'uuid|exists:categories,id',
            'thumbnail_id' => 'required|uuid|exists:images,id|unique:stories,thumbnail_id,' . $this->id,
        ];
    }
}
