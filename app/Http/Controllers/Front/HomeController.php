<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Jobs\LogSearchQuery;
use App\Models\Author;
use App\Models\Story;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function completed_stories(Request $request)
    {
        $limit = $request->input('limit', 12);
        $offset = $request->input('offset', 0);
        $stories = Story::with('categories')
            ->where('status', 2)
            ->offset($offset)
            ->paginate($limit);
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'data' => $stories
            ]
        ], JsonResponse::HTTP_OK);
    }

    public function search(Request $request)
    {
        $query = $request->input('keyword');

        if (!$query) {
            return response()->json([
                'status' => JsonResponse::HTTP_BAD_REQUEST,
                'body' => [
                    'message' => 'Invalid parameters'
                ]
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        LogSearchQuery::dispatch($query);
        $storyResults = [];
        $storyResults = Story::where('name', 'LIKE', "%{$query}%")->where('status', '!=', 0)
            ->orWhereHas('author', function ($queryBuilder) use ($query) {
                $queryBuilder->where('full_name', 'LIKE', "%{$query}%")
                    ->orWhere('pen_name', 'LIKE', "%{$query}%");
            })
            ->get();

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'data' => $storyResults
            ]
        ]);
    }

    public function latestStories(Request $request)
    {
        $limit = $request->limit ?? 20;
        $stories = Story::where('status', '!=', 0)->whereHas('chapters')
            ->with(['categories', 'chapters' => function ($query) {
                $query->orderByDesc('created_at');
            }])->limit($limit)
            ->get()
            ->map(function ($story) {
                $story->chapter = $story->chapters->first();
                unset($story->chapters);
                return $story;
            });

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'data' => $stories
            ]
        ]);
    }
}
