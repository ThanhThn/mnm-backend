<?php

namespace App\Http\Request\Auth;

use App\Http\Request\BaseRequest;

class RegisterRequest extends BaseRequest
{
    public function rules(){
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ];
    }
}
