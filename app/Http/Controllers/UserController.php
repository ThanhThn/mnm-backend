<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    function updateUser(Request $request)
    {
        $data = $request->all();
        Log::info($data);
        $partnerCode = env("MOMO_PARTNER_CODE");
        $accessKey = env("MOMO_ACCESS_KEY");
        $serectkey = env("MOMO_SECRET_KEY");
        $orderId = $data["orderId"];
        $localMessage = $data["localMessage"];
        $message = $data["message"];
        $transId = $data["transId"];
        $orderInfo = $data["orderInfo"];
        $amount = $data["amount"];
        $errorCode = $data["errorCode"];
        $responseTime = $data["responseTime"];
        $requestId = $data["requestId"];
        $payType = $data["payType"];
        $orderType = $data["orderType"];
        $extraData = $data["extraData"];
        $m2signature = $data["signature"];


        //Checksum
        $rawHash = "partnerCode=" . $partnerCode . "&accessKey=" . $accessKey . "&requestId=" . $requestId . "&amount=" . $amount . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo .
            "&orderType=" . $orderType . "&transId=" . $transId . "&message=" . $message . "&localMessage=" . $localMessage . "&responseTime=" . $responseTime . "&errorCode=" . $errorCode .
            "&payType=" . $payType . "&extraData=" . $extraData;

        $partnerSignature = hash_hmac("sha256", $rawHash, $serectkey);

        if ($m2signature != $partnerSignature){
            Log::error("Can not update role of user");
            return Helpers::response(JsonResponse::HTTP_BAD_REQUEST, 'Can not update role of user');
        }

        $payment = Payment::where('order_id', $orderId)->first();

        if($errorCode != 0){
            Log::error($message);
            $payment->update(['status' => 2]);
            return Helpers::response(JsonResponse::HTTP_BAD_REQUEST, $message);
        }

        $user = User::find($payment->user_id);
        if($user->role == 1){
            Log::info("Role of user is admin");
            return Helpers::response(JsonResponse::HTTP_CONFLICT, 'Role of user is admin');
        }
        $user->update(['role' => 2]);
        $payment->update(['status' => 1]);
    }
}
