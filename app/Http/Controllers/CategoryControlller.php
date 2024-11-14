<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Request\Category\CategoryRequest;

class CategoryControlller extends Controller
{
    public function createCategory(CategoryRequest $request)
    {
        $request->validated();
        $category = Category::create([
            'name' => $request->name,
            'slug' => Helpers::createSlug($request->name),
            'description' => $request->description,
            'active' => $request->active
        ]);
        if ($category) {
            return response()->json([
                'status' => JsonResponse::HTTP_CREATED,
                'body' => [
                    'message' => 'Category successfully created'
                ]
            ], JsonResponse::HTTP_OK);
        }
        return response()->json([
            'status' => JsonResponse::HTTP_BAD_REQUEST,
            'message' => 'Something went wrong',
        ], JsonResponse::HTTP_OK);
    }
}
