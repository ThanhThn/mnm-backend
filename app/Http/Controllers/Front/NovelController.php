<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Story;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NovelController extends Controller
{
    public function detailStory($slugStory)
    {
        $story = Story::where('slug', $slugStory)->where('status', '!=', 0)->with('categories', 'chapters')->first();
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

    public function detailChapter($slugStory, $slugChapter)
    {
        $chapter = Chapter::where('slug', $slugChapter)
            ->whereHas('story', function ($query) use ($slugStory) {
                $query->where('slug', $slugStory);
            })
            ->with('story')
            ->first();

        if (!$chapter) {
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'body' => [
                    'message' => 'Story or Chapter not found'
                ]
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'data' => $chapter
            ]
        ]);
    }
}
