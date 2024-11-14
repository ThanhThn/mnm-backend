<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    function createAuthor(Request $request)
    {
        $data = $request->only(['full_name']);
        if(!empty($request->pen_name)){
            $data['pen_name'] = $request->pen_name;
        }
        if(!empty($request->birth_date)){
            $data['birth_date'] = $request->birth_date;
        }
        if(!empty($request->profile_picture_id)){
            $data['profile_picture_id'] = $request->profile_picture_id;
        }

        $author = Author::create($data);
        if(!$author){
            return response()->json([
                'status' => JsonResponse::HTTP_BAD_REQUEST,
                'body' => [
                    'message' => 'Cannot create author'
                ]
            ], JsonResponse::HTTP_OK);
        }
        return response()->json([
            'status' => JsonResponse::HTTP_CREATED,
            'body' => [
                'author' => $author
            ]
        ], JsonResponse::HTTP_OK);
    }

    function listAuthors()
    {
        $authors = Author::with('profilePicture')->get();
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'authors' => $authors
            ]
        ], JsonResponse::HTTP_OK);
    }

    function deleteAuthor($id){
        $author = Author::find($id);
        if(!$author){
            return response()->json([
                'status' => JsonResponse::HTTP_BAD_REQUEST,
                'body' => [
                    'message' => 'Cannot find author'
                ]
            ], JsonResponse::HTTP_OK);
        }
        $author->delete();
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'message' => 'Author deleted'
            ]
        ]);
    }
}
