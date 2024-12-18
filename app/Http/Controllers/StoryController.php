<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Request\Story\CreateStoryRequest;
use App\Http\Request\Story\EditStoryRequest;
use App\Models\Story;
use App\Support\File\ImageSupport;
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
                    'data' => $story->load('categories', 'storyPicture', 'author')
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
        $data = $request->only( 'id','name', 'description', 'author_id', 'thumbnail_id', 'status','category_ids');
        $story = Story::find($data['id']);

        if($data['thumbnail_id'] != $story->thumbnail_id && $story->thumbnail_id != null){
            ImageSupport::delete($story->thumbnail_id);
        }
        $story->update([
            'name' => $request->name,
            'slug' => Helpers::createSlug($request->name),
            'description' => $request->description,
            'author_id' => $request->author_id,
            'thumbnail_id' => $request->thumbnail_id,
            'status' => $request->status
        ]);

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

    public function listStories(Request $request)
    {
        $limit = $request->input('limit', 15);
        $stories = Story::with('categories')->orderByDesc('created_at')->paginate($limit);
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'data' => $stories
            ]
        ], JsonResponse::HTTP_OK);
    }

    public function dataStories()
    {
        $stories = Story::all();
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
            ImageSupport::delete($story->thumbnail_id);

            $story->categories()->detach();
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
        $story = Story::with('categories')->find($id);
        if (!$story) return Helpers::response(JsonResponse::HTTP_NOT_FOUND, 'Story not found');
        return Helpers::response(JsonResponse::HTTP_OK, data: $story);
    }

    public function listStoriesByAuthor($author_id)
    {
        $stories = Story::where("author_id", $author_id)->get();
        $count = count($stories);

        return Helpers::response(JsonResponse::HTTP_OK, data: $stories, options: ['count' => $count]);
    }
}
