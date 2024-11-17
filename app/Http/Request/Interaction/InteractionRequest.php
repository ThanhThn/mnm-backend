<?php

namespace App\Http\Request\Interaction;

use App\Http\Request\BaseRequest;
use function Symfony\Component\String\b;

class InteractionRequest extends BaseRequest
{
    public function rules(){
        $rules = [
            'interaction_type' => 'required|integer',
            'object_a_type' => 'required|integer|in:1,2,3,4',
            'object_b_type' => 'required|integer|in:1,2,3,4',
            'object_a_id' => ['required', 'uuid'],
            'object_b_id' => ['required', 'uuid'],
        ];

        switch($this->input('object_a_type')){
            case 1:
                array_push($rules['object_a_id'], 'exists:users,id');
                break;
            case 2:
                array_push($rules['object_a_id'], 'exists:stories,id');
                break;
            case 3:
                array_push($rules['object_a_id'], 'exists:comics,id');
                break;
            case 4:
                array_push($rules['object_a_id'], 'exists:comments,id');
        }

        switch($this->input('object_b_type')){
            case 1:
                array_push($rules['object_b_id'], 'exists:users,id');
                break;
            case 2:
                array_push($rules['object_b_id'], 'exists:stories,id');
                break;
            case 3:
                array_push($rules['object_b_id'], 'exists:comics,id');
                break;
            case 4:
                array_push($rules['object_b_id'], 'exists:comments,id');
                break;
        }

        return $rules;
    }
}
