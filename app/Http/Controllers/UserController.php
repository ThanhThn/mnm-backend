<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Mail\OTPMail;
use App\Models\PasswordResetToken;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    function updateUser(Request $request)
    {
        $data = $request->all();
        $partnerCode = $data["partnerCode"];
        $accessKey = env("MOMO_ACCESS_KEY");
        $serectkey = env("MOMO_SECRET_KEY");
        $orderId = $data["orderId"];
//        $localMessage = $data["localMessage"];
        $message = $data["message"];
        $transId = $data["transId"];
        $orderInfo = $data["orderInfo"];
        $amount = $data["amount"];
        $resultCode = $data["resultCode"];
        $responseTime = $data["responseTime"];
        $requestId = $data["requestId"];
        $payType = $data["payType"];
        $orderType = $data["orderType"];
        $extraData = $data["extraData"];
        $m2signature = $data["signature"];


//        "&localMessage=" . $localMessage .
        //Checksum
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&message=" . $message . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo .
            "&orderType=" . $orderType . "&partnerCode=" . $partnerCode . "&payType=" . $payType . "&requestId=" . $requestId . "&responseTime=" . $responseTime .
            "&resultCode=" . $resultCode . "&transId=" . $transId;

        $partnerSignature = hash_hmac("sha256", $rawHash, $serectkey);

        if ($m2signature != $partnerSignature){
            return Helpers::response(JsonResponse::HTTP_BAD_REQUEST, 'Can not update role of user');
        }

        $payment = Payment::where('order_id', $orderId)->first();

        if($resultCode != 0){
            $payment->update(['status' => 2]);
            return Helpers::response(JsonResponse::HTTP_BAD_REQUEST, $message);
        }

        $user = User::find($payment->user_id);
        if($user->role == 1){
            return Helpers::response(JsonResponse::HTTP_CONFLICT, 'Role of user is admin');
        }
        $user->update(['role' => 2]);
        $payment->update(['status' => 1]);
        return Helpers::response(JsonResponse::HTTP_CREATED, 'User updated successfully');
    }

    function forgetUser(Request $request)
    {
        $email = $request->email;

        if(empty($email)){
            return Helpers::response(JsonResponse::HTTP_BAD_REQUEST, 'Email cannot be empty');
        }

        $user = User::where('email', $email)->first();
        if(!$user){
            return Helpers::response(JsonResponse::HTTP_BAD_REQUEST, 'User not found');
        }

        if($user->role == 1){
            return Helpers::response(JsonResponse::HTTP_CONFLICT, 'Role of user is admin');
        }
        $resetToken = PasswordResetToken::where('email', $email) -> exits();

        $otp = rand(1000, 9999);
        $data = json_encode([
            'otp' => $otp,
            'exp' => time() + 60 * 60,
        ]);
        $encrypt = Helpers::encrypt($data);
        $decrypt = Helpers::decrypt($encrypt);

        Mail::to($email)->send(new OTPMail($otp));
        return response() -> json([
            'otp' => $otp,
            'encrypt' => $encrypt,
            'decrypt' => json_decode($decrypt, true)
        ]);

    }
}
