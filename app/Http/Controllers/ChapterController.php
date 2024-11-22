<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Request\Chapter\CreateChapterRequest;
use App\Http\Request\Chapter\ListChapterRequest;
use App\Models\Chapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function createChapter(CreateChapterRequest $request)
    {
        $data = $request->input();
        $chapter = Chapter::create(array_merge($data, [
            'slug' => Helpers::createSlug($request->title),
            // 'sound' => $request->sound
        ]));
        if (!$chapter) {
            return response()->json([
                'status' => JsonResponse::HTTP_BAD_REQUEST,
                'body' => [
                    'message' => 'Created Chapter Failed',
                ],
            ]);
        }
        return response()->json([
            'status' => JsonResponse::HTTP_CREATED,
            'body' => [
                'message' => 'Created Chapter Successfully',
                'data' => $chapter->load('story')
            ]
        ]);
    }

    public function listChapters(ListChapterRequest $request)
    {
        $limit = $request['limit'] ?? 15;
        $storyId = $request['story_id'] ?? null;
        if ($storyId) {
            $chapters = Chapter::where('story_id', $storyId)
                ->orderByDesc('created_at')
                ->paginate($limit);
        } else {
            $chapters = Chapter::orderByDesc('created_at')->paginate($limit);
        }
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'data' => $chapters
            ]
        ], JsonResponse::HTTP_OK);
    }

    public function detailChapter($id)
    {
        $chapter = Chapter::find($id);
        if (!$chapter) {
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'body' => [
                    'message' => 'Chapter not found'
                ]
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'data' => $chapter
            ]
        ]);
    }
}
