<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Request\Interaction\InteractionDeleteRequest;
use App\Http\Request\Interaction\InteractionRequest;
use App\Models\Interaction;
use App\Support\Interaction\InteractionSupport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InteractionController extends Controller
{
    function addInteraction(InteractionRequest $request)
    {
        $result = Interaction::create([
            'object_a_type' => $request->object_a_type,
            'object_b_type' => $request->object_b_type,
            'object_a_id' => $request->object_a_id,
            'object_b_id' => $request->object_b_id,
            'interaction_type' => $request->interaction_type,
        ]);

        if (!$result){
            return response()->json([
                'status' => JsonResponse::HTTP_BAD_REQUEST,
                'message' => 'Interaction add failed'
            ], JsonResponse::HTTP_OK);
        }
        return response()->json([
            'status' => JsonResponse::HTTP_CREATED,
            'body' => [
                'data' => $result
            ]
        ]);
    }

    function deleteInteraction(InteractionDeleteRequest $request, InteractionSupport $support, Helpers $helper){
        $data = $request->only(['object_type', 'object_id', 'interaction_type']);
        $interaction = $support::getInteraction(Auth::user()->id, $data['object_id'], $data['object_type'], $data['interaction_type']);
        if (!$interaction){
            return $helper::response(JsonResponse::HTTP_NOT_FOUND, 'Interaction not found');
        }
        $result = $interaction->delete();
        if (!$result){
            return $helper::response(JsonResponse::HTTP_BAD_REQUEST, 'Interaction delete failed');
        }
        return $helper::response(JsonResponse::HTTP_OK, 'Interaction delete success');
    }
}
