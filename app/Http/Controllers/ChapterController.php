<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Helpers\S3Utils;
use App\Http\Request\Chapter\CreateChapterRequest;
use App\Http\Request\Chapter\EditChapterRequest;
use App\Http\Request\Chapter\ListChapterRequest;
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
            'processing' => empty($request->sound)
        ]));

        if ($request->sound && !empty($request->sound)) {
            $file =  $request -> file('sound');
            $path = $file->store('temp');

            $inforFile = Helpers::getFileNameAnExtension($file);
            UploadSound::dispatch($path, $chapter->id, $inforFile['name'], $inforFile['extension']);
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

    public function listChapters(ListChapterRequest $request)
    {
        $limit = $request['limit'] ?? 15;
        $storyId = $request['story_id'] ?? null;
        if ($storyId) {
            $chapters = Chapter::with('story')->where('story_id', $storyId)
                ->orderByDesc('created_at')
                ->paginate($limit);
        } else {
            $chapters = Chapter::with('story')->orderByDesc('created_at')->paginate($limit);
        }
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'data' => $chapters
            ]
        ], JsonResponse::HTTP_OK);
    }

    public function updateChapter(EditChapterRequest $request, S3Utils $utils)
    {
        $data = $request->only(['id', 'title', 'story_id', 'content', 'status', 'sound']);
        $chapter = Chapter::find($data['id']);

        if ($chapter->processing) {
            return Helpers::response(JsonResponse::HTTP_FORBIDDEN, 'Cannot update chapter because it has already been processed.');
        }
        $processing = false;

        if(!empty($data['sound']) && $chapter->sound)  {
            $utils::delete($chapter->sound);

            $file =  $request -> file('sound');
            $path = $file->store('temp');

            $inforFile = Helpers::getFileNameAnExtension($file);

            UploadSound::dispatch($path, $chapter->id, $inforFile['name'], $inforFile['extension']);
            $processing = true;
        }

        $chapter->update([
            'title' => $data['title'],
            'slug' => Helpers::createSlug($data['title']),
            'content' => $data['content'],
            'status' => $data['status'],
            'story_id' => $data['story_id'],
            'processing' => $processing
        ]);
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'message' => 'Chapter updated successfully',
                'data' => $chapter->load('story')
            ]
        ]);
    }

    public function deleteChapter($id, S3Utils $utils)
    {
        $chapter = Chapter::find($id);

        if ($chapter->processing) {
            return Helpers::response(JsonResponse::HTTP_FORBIDDEN, 'Cannot update chapter because it has already been processed.');
        }

        if (!$chapter) {
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'body' => [
                    'message' => 'Chapter not found'
                ]
            ]);
        }

        if ($chapter->sound) {
            $utils::delete($chapter->sound);
        }

        $result = $chapter->delete();
        if(!$result){
            return Helpers::response(JsonResponse::HTTP_BAD_REQUEST, 'Could not delete chapter');
        }
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'message' => 'Chapter deleted successfully'
            ]
        ]);
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
                'data' => $chapter->load('story')
            ]
        ]);
    }
}
