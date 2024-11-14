<?php

namespace App\Http\Request;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class BaseRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $mess = [];
        foreach ($errors as $field => $messages) {
            foreach ($messages as $message) {
                $mess[] = [
                    'error_message' => $message,
                    'field' => $field,
                ];
            }
        }

        throw new HttpResponseException(response()->json(
            [
                'status' => JsonResponse::HTTP_BAD_REQUEST,
                'body' => [
                    'message' => $mess],

            ], JsonResponse::HTTP_OK));
    }
}
