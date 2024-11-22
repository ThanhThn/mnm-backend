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
        $query = $request->input('q');
        $type = $request->input('type');

        if (!$query || !$type) {
            return response()->json([
                'status' => JsonResponse::HTTP_BAD_REQUEST,
                'body' => [
                    'message' => 'Invalid parameters'
                ]
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        LogSearchQuery::dispatch($query, $type);
        $results = [];
        if ($type === 'author') {
            $results = Author::where('full_name', 'LIKE', "%{$query}%")
                ->orWhere('pen_name', 'LIKE', "%{$query}%")
                ->get();
        } elseif ($type === 'story') {
            $results = Story::where('name', 'LIKE', "%{$query}%")
                ->get();
        } else {
            return response()->json([
                'status' => JsonResponse::HTTP_BAD_REQUEST,
                'body' => [
                    'message' => 'Invalid parameters'
                ]
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'data' => $results
            ]
        ]);
    }
}
