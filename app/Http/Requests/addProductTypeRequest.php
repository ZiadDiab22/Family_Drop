<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class addProductTypeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id' => ['required', 'exists:product_types,id']
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
