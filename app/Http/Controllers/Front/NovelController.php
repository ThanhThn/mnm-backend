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
        $story = Story::where('slug', $slugStory)
            ->where('status', '!=', 0)
            ->with([
                'categories:id,name,slug',
                'chapters' => function ($query) {
                    $query->where('status', 1)
                        ->orderBy('created_at', 'asc');
                }
            ])->first();
        if (!$story) {
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'body' => [
                    'message' => 'Story not found',
                ]
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        $story->increment('views');
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'data' => $story
            ]
        ], JsonResponse::HTTP_OK);
    }

    public function chaptersOfTheStory($slugStory)
    {
        $chapters = Chapter::whereHas('story', function ($query) use ($slugStory) {
            $query->where('slug', $slugStory);
        })->where('status', 1)->select('title', 'slug')->get();
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'data' => $chapters
            ]
        ]);
    }

    public function detailChapter($slugStory, $slugChapter)
    {
        $chapter = Chapter::where('slug', $slugChapter)->where('status', 1)
            ->whereHas('story', function ($query) use ($slugStory) {
                $query->where('slug', $slugStory);
            })
            ->with('story:id,name,slug')
            ->first();

        if (!$chapter) {
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'body' => [
                    'message' => 'Story or Chapter not found'
                ]
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        $chapter->story->increment('views');
        $previousChapter = Chapter::where('story_id', $chapter->story_id)
            ->where('created_at', '<', $chapter->created_at)
            ->orderBy('created_at', 'desc')
            ->select('slug')
            ->first();

        $nextChapter = Chapter::where('story_id', $chapter->story_id)
            ->where('created_at', '>', $chapter->created_at)
            ->orderBy('created_at', 'asc')
            ->select('slug')
            ->first();

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'data' => $chapter,
                'previous_chapter' => $previousChapter,
                'next_chapter' => $nextChapter,
            ]
        ]);
    }
}
