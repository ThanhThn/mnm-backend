<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NovelController extends Controller
{
    public function detailStory($slugStory)
    {
        $story = Story::where('slug', $slugStory)->where('status', '!=', 0)->with('categories', 'author')->first();
        if (!$story) {
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'body' => [
                    'message' => 'Story not found',
                ]
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'data' => $story
            ]
        ], JsonResponse::HTTP_OK);
    }
}
