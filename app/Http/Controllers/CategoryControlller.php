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
            'status' => $request->status
        ]);
        if ($category) {
            return response()->json([
                'status' => JsonResponse::HTTP_CREATED,
                'body' => [
                    'message' => 'Category successfully created',
                    'data' => $category
                ]
            ], JsonResponse::HTTP_OK);
        }
        return response()->json([
            'status' => JsonResponse::HTTP_BAD_REQUEST,
            'message' => 'Something went wrong',
        ], JsonResponse::HTTP_OK);
    }

    public function updateCategory(CategoryRequest $request)
    {
        $category = Category::find($request->id);
        $category->update([
            'name' => $request->name,
            'slug' => Helpers::createSlug($request->name),
            'description' => $request->description,
            'status' => $request->status
        ]);
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'message' => 'Category successfully updated',
                'data' => $category
            ]
        ], JsonResponse::HTTP_OK);
    }

    public function listCategories(Request $request)
    {
        $limit = $request->input('limit', 15);
        $category = Category::paginate($limit);
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'data' => $category
            ]
        ], JsonResponse::HTTP_OK);
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'body' => [
                    'message' => 'Category deleted'
                ]
            ], JsonResponse::HTTP_OK);
        }
        return response()->json([
            'status' => JsonResponse::HTTP_NOT_FOUND,
            'body' => [
                'message' => 'Category not found'
            ]
        ], JsonResponse::HTTP_NOT_FOUND);
    }

    public function detailCategory($id)
    {
        $category = Category::find($id);
        if (!$category) return Helpers::response(JsonResponse::HTTP_NOT_FOUND, 'Category not found');
        return Helpers::response(JsonResponse::HTTP_OK, data: $category);
    }
}
