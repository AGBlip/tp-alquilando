<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
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
        $validations=[
            'nombre' => 'required|string|min:2|max:50',
            'apellido' => 'required|string|min:2|max:50',
            'email' => 'required|email|unique:users,email',
            'usuario' => 'required|string|min:4|max:30'
        ];

        if($this->user ?? false){
            $validations['email']='required|email|unique:users,email,'.$this->user->id;
        }

        return $validations;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'message' => 'Uno o mas datos no son validos.',
            'errors'=> $validator->errors()
        ],400));
    }
}