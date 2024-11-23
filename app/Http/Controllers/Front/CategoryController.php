<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Story;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categories(Request $request)
    {
        $categories = Category::where('status', 1)->get();
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'data' => $categories
            ]
        ], JsonResponse::HTTP_OK);
    }

    public function storiesOfCategory($slugCategory, Request $request)
    {
        $limit = $request->input('limit', 15);
        $category = Category::where('slug', $slugCategory)->first();
        if (!$category) {
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'body' => [
                    'message' => 'Category not found'
                ]
            ], JsonResponse::HTTP_OK);
        }
        $stories = $category->stories()->where('status', '!=', 0)->paginate($limit);
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'data' => $stories
            ]
        ], JsonResponse::HTTP_OK);
    }
}
