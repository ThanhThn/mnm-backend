<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
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
}
