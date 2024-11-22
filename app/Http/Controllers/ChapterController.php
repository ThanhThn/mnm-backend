<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Request\Chapter\CreateChapterRequest;
use App\Jobs\UploadSound;
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

        if($request->sound && !empty($request->sound)) {
            UploadSound::dispatch($request->sound, $chapter->id);
        }
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
}
