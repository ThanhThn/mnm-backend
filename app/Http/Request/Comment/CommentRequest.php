<?php

namespace App\Http\Request\Comment;

use App\Http\Request\BaseRequest;

class CommentRequest extends BaseRequest
{
    public function rules(){
        $rules = [
          'novel_id' => ['required','uuid'],
          'novel_type' => 'required|string|in:story,comic',
          'content' => 'required|string',
          'parent_comment_id' => 'nullable|uuid|exists:comments,id',
        ];

        switch ($this->input('novel_type')){
            case 'story':
                array_push($rules['novel_id'], 'exists:stories,id');
                break;
            case 'comic':
                array_push($rules['novel_id'], 'exists:comics,id');
                break;
        }

        return $rules;
    }
}
