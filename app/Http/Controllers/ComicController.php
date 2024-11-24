<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Advertisement;
use App\Models\Author;
use App\Models\Comics;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComicController extends Controller
{
    public function list(Request $request){
        $limit = $request->limit ?? 10;
        $offset = $request->offset ?? 0;
        $comics = Comics::with('categories')->limit($limit)->offset($offset)->get();
        $count  = Comics::all()->count();
        return Helpers::response(JsonResponse::HTTP_OK, data: $comics, options: [ "count" => $count, "offset" => $offset]);
    }

    function detail($id)
    {
        $comic = Comics::find($id);
        if (!$comic) return Helpers::response(JsonResponse::HTTP_NOT_FOUND, "Comic not found");
        return Helpers::response(JsonResponse::HTTP_OK, data: $comic);
    }

    function update(Request $request){
        $data = $request->only("id", "status");
        if(empty($data["id"])){
           return Helpers::response(JsonResponse::HTTP_BAD_REQUEST, "ID is required");
        }
        $comic = Comics::find($data["id"]);
        if(!$comic) return Helpers::response(JsonResponse::HTTP_NOT_FOUND, "Comic not found");
        $comic->update($data);
        return Helpers::response(JsonResponse::HTTP_OK, data: $comic);
    }
}
