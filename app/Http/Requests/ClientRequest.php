<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'client_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'client_number' => 'required|numeric|digits:10|regex:/^\d{10}$/',
            'client_email' => 'required | email |max:255|unique:clients',
            'status' => 'required | boolean',
        ];
    }

    public function messages()
    {
        return [
            'client_number.numeric' => 'The client number must only contain numbers',
            'client_number.digits' => 'The client number must be a 10 digit mobile number',
        ];
    }
}
