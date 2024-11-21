<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Request\Story\CreateStoryRequest;
use App\Http\Request\Story\EditStoryRequest;
use App\Models\Story;
use App\Support\Image\ImageSupport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    public function createStory(CreateStoryRequest $request)
    {
        $story = Story::create([
            'name' => $request->name,
            'slug' => Helpers::createSlug($request->name),
            'description' => $request->description,
            'author_id' => $request->author_id,
            'thumbnail_id' => $request->thumbnail_id,
            'status' => $request->status,
        ]);

        if ($story) {
            $categoryData = [];
            foreach ($request->category_ids as $category) {
                $categoryData[$category] = ['novel_type' => 'story'];
            }
            $story->categories()->sync($categoryData);
            return response()->json([
                'status' => JsonResponse::HTTP_CREATED,
                'body' => [
                    'message' => 'Story successfully created',
                    'data' => $story->load('categories', 'storyPicture')
                ]
            ], JsonResponse::HTTP_OK);
        }

        return response()->json([
            'status' => JsonResponse::HTTP_BAD_REQUEST,
            'body' => [
                'message' => 'Failed to create story'
            ]
        ], JsonResponse::HTTP_OK);
    }


    public function updateStory(EditStoryRequest $request)
    {
        $story = Story::find($request->id);
        $story->update([
            'name' => $request->name,
            'slug' => Helpers::createSlug($request->name),
            'description' => $request->description,
            'author_id' => $request->author_id,
            'thumbnail_id' => $request->thumbnail_id,
            'status' => $request->status
        ]);
        if ($story) {
            $categoryData = [];
            foreach ($request->category_ids as $category) {
                $categoryData[$category] = ['novel_type' => 'story'];
            }
            $story->categories()->sync($categoryData);

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'body' => [
                    'message' => 'Story successfully updated',
                    'data' => $story->load('categories')
                ]
            ]);
        }
        return response()->json([
            'status' => JsonResponse::HTTP_BAD_REQUEST,
            'body' => [
                'message' => 'Failed to update story'
            ]
        ]);
    }

    public function listStories(Request $request)
    {
        $limit = $request->input('limit', 15);
        $stories = Story::with('categories', 'author')->paginate($limit);
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'data' => $stories
            ]
        ], JsonResponse::HTTP_OK);
    }

    public function deleteStory($id)
    {
        $story = Story::find($id);
        if ($story) {
            $story->categories()->detach();
            ImageSupport::delete($story->thumbnail_id);
            $story->delete();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'body' => [
                    'message' => 'Story successfully deleted'
                ]
            ], JsonResponse::HTTP_OK);
        }
        return response()->json([
            'status' => JsonResponse::HTTP_NOT_FOUND,
            'body' => [
                'message' => 'Story not found'
            ]
        ], JsonResponse::HTTP_NOT_FOUND);
    }

    public function detailStory($id)
    {
        $story = Story::with('storyPicture', 'categories')->find($id);
        if (!$story) return Helpers::response(JsonResponse::HTTP_NOT_FOUND, 'Story not found');
        return Helpers::response(JsonResponse::HTTP_OK, data: $story);
    }
}
