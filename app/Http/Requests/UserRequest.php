<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|min:8",            
        ];
    }

    /** 
     * Custom Error
     *
     * @return json     
    */
    public function failedValidation(Validator $validator){
        throw new HttpResponseException(
            response()->json([
                "status" => "Failed",
                "error" => $validator->errors()->first()
            ],422)
        );
    }
}
