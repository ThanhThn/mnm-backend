<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Request\Comment\CommentRequest;
use App\Http\Request\Comment\ListCommentRequest;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function addComment(CommentRequest $request)
    {
        $data = $request->only(['novel_id', 'novel_type', 'content', 'parent_comment_id']);
        $data['user_id'] = Auth::user()->id;
        $comment = Comment::create($data);
        if($comment){
            return Helpers::response(JsonResponse::HTTP_OK, data: $comment );
        }
        return Helpers::response(JsonResponse::HTTP_BAD_REQUEST, 'Create comment failed' );
    }

    public function listComments(ListCommentRequest $request)
    {
        $data = $request->only(['novel_id', 'novel_type', 'limit', 'offset']);
        $limit = $data['limit'] ?? 5;
        $offset = $data['offset'] ?? 0;
        $comments = Comment::where([
            ['novel_id', '=' ,$data['novel_id']],
            ['novel_type', '=' ,$data['novel_type']],
            ['parent_comment_id', '=' , null]
        ])->limit($limit)->offset($offset)->get();
        return Helpers::response(JsonResponse::HTTP_OK, data: $comments);
    }
}
