<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
abstract class BaseApiRequest extends FormRequest
{
    /**
     * Handle failed validation - return JSON response for APIs.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'failed',
            'errors'  => $validator->errors(),
        ], 422));
    }
}
