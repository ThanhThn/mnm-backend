<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\NovelCategory;
use App\Models\Story;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function novelsOfCategory($slugCategory, Request $request)
    {
        $limit = $request->input('limit', 15);
        $offset = $request->input('offset', 0);
        $category = Category::where('slug', $slugCategory)->first();
        if (!$category) {
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'body' => [
                    'message' => 'Category not found'
                ]
            ], JsonResponse::HTTP_OK);
        }
        $count = NovelCategory::where('category_id', $category->id)->count();
        $stories = NovelCategory::where("category_id", $category->id)
            ->with('novel')->get()
            ->unique('novel_id')
            ->filter(function ($novel) {
                return $novel->novel->status != 0;
            })
            ->sortByDesc(function ($novel) {
                return $novel->novel->views ?? 0;
            })->splice($offset, $limit)->values()->toArray();

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'count' => $count,
                'data' => $stories,
                'category' => $category->name,
                'description' => $category->desciption
            ]
        ], JsonResponse::HTTP_OK);
    }
}
