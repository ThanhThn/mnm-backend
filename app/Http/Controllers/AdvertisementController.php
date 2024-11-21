<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Request\Advertisement\AdsRequest;
use App\Models\Advertisement;
use App\Support\Image\ImageSupport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function createAds(AdsRequest $request){
        $data = $request->only(['link', 'picture_id']);
        $result = Advertisement::create($data);
        if ($result){
            return Helpers::response(JsonResponse::HTTP_OK, 'Created Advertisement Successfully', $result);
        }
        return Helpers::response(JsonResponse::HTTP_BAD_REQUEST, 'Created Advertisement Failed');
    }

    public function updateAds(AdsRequest $request){
        $data = $request->only(['link', 'picture_id']);
        $adsId = $request->id ?? null;
        $ads = Advertisement::find($adsId);

        if (!$ads){
            return Helpers::response(JsonResponse::HTTP_BAD_REQUEST, 'Advertisement not found');
        }

        if($data['picture_id'] != $ads->picture_id && $ads->picture_id != null){
            ImageSupport::delete($ads->picture_id);
        }

        $result = $ads->update($data);
        if ($result){
            return Helpers::response(JsonResponse::HTTP_OK, 'Updated Advertisement Successfully', $result);
        }
        return Helpers::response(JsonResponse::HTTP_BAD_REQUEST, 'Updated Advertisement Failed');
    }

    public function deleteAds($id){
        $ads = Advertisement::find($id);
        if(!$ads){
            return response()->json([
                'status' => JsonResponse::HTTP_BAD_REQUEST,
                'body' => [
                    'message' => 'Cannot find advertisement'
                ]
            ], JsonResponse::HTTP_OK);
        }
        ImageSupport::delete($ads->picture_id);
        $result = $ads->delete();
        if ($result){
            return Helpers::response(JsonResponse::HTTP_OK, 'Deleted Advertisement Successfully');
        }
        return Helpers::response(JsonResponse::HTTP_NOT_FOUND, 'Deleted Advertisement Failed');
    }

    public function listAds(Request $request){
        $limit = $request->limit ?? 10;
        $offset = $request->offset ?? 0;
        $ads = Advertisement::limit($limit)->offset($offset)->get();
        $count  = Advertisement::all()->count();
        return Helpers::response(JsonResponse::HTTP_OK, data: $ads, options: [ "count" => $count, "offset" => $offset]);
    }

    public function detailAds($id)
    {
        $ads = Advertisement::find($id);
        if (!$ads) return Helpers::response(JsonResponse::HTTP_NOT_FOUND, "Advertisement not found");
        return Helpers::response(JsonResponse::HTTP_OK, data: $ads);
    }

    public function getAdsRandom()
    {
        $ads = Advertisement::inRandomOrder()->first();
        return Helpers::response(JsonResponse::HTTP_OK, data: $ads);
    }
}
