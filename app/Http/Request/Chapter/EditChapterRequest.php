<?php

namespace App\Http\Request\Chapter;

use Illuminate\Validation\Rule;
use App\Http\Request\BaseRequest;
use App\Models\Chapter;

class EditChapterRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'id' => 'required|uuid|exists:chapters,id',
            'title' => ['required', 'string', 'max:100', Rule::unique((new Chapter())->getTable())->ignore($this->id ?? null)],
            'content' => 'required|string',
            'status' => 'required|integer',
            'story_id' => 'required|uuid|exists:stories,id'
        ];
    }
}
