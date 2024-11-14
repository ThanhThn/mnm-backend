<?php

namespace App\Http\Request\Author;

use App\Http\Request\BaseRequest;

class AuthorRequest extends BaseRequest
{
    public function rules(){
        return [
            'full_name' => 'required|string',
            'pen_name' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'profile_picture_id' => 'nullable|uuid|exists:images,id',
        ];
    }
}
