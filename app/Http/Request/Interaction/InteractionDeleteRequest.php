<?php

namespace App\Http\Request\Interaction;

use App\Http\Request\BaseRequest;

class InteractionDeleteRequest extends BaseRequest
{
    public function rules(){
        return [
            'object_type' => 'required|integer|in:1,2,3,4',
            'object_id' => 'required|uuid|exists:interactions,object_b_id',
            'interaction_type' => 'required|integer|in:1,2,3,4'];
    }
}
