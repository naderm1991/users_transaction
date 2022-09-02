<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class UserRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'amount_range'      => 'nullable|array|size:2',
            'amount_range.from' => 'required_with:amount_range',
            'amount_range.to' => 'required_with:amount_range',
            'date_range'      => 'nullable|array|size:2',
            'date_range.from' => 'required_with:date_range',
            'date_range.to' => 'required_with:date_range',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = response()->json([
            'status'=> "failed",
            "errors"=> $validator->errors()
        ]);

        throw new HttpResponseException($response);
    }
}
