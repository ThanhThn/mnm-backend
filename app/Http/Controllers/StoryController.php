<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Request\Story\CreateStoryRequest;
use App\Http\Request\Story\EditStoryRequest;
use App\Models\Story;
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
            'status' => $request->status
        ]);
        if ($story) {
            return response()->json([
                'status' => JsonResponse::HTTP_CREATED,
                'body' => [
                    'message' => 'Story successfully created',
                    'data' => $story
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
            'description' => $request->description,
            'author_id' => $request->author_id,
            'thumbnail_id' => $request->thumbnail_id,
            'status' => $request->status
        ]);
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'message' => 'Story successfully updated',
                'data' => $story
            ]
        ]);
        return response()->json([
            'status' => JsonResponse::HTTP_BAD_REQUEST,
            'body' => [
                'message' => 'Failed to update story'
            ]
        ]);
    }
}
