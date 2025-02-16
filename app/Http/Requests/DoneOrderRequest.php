<?php

namespace App\Http\Requests;

use App\Rules\IsDeliveringOrderRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class DoneOrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id' => ['required', 'exists:orders,id', new IsDeliveringOrderRule]
        ];
    }

    public function messages()
    {
        return [
            'id.exists' => 'Wrong id, not found',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $validator->errors()->first(),
        ], 422));
    }
}
