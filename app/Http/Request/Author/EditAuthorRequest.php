<?php

namespace App\Http\Request\Author;

use App\Http\Request\BaseRequest;

class EditAuthorRequest extends BaseRequest
{
    public function rules(){
        return [
            'id' => 'required|uuid|exists:authors,id',
            'full_name' => 'nullable|string',
            'pen_name' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'profile_picture_id' => 'nullable|uuid|exists:images,id',
        ];
    }
}
