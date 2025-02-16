<?php

namespace App\Http\Requests;

use App\Rules\IsWorkingOrderRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class EndingOrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id' => ['required', 'exists:orders,id', new IsWorkingOrderRule]
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
