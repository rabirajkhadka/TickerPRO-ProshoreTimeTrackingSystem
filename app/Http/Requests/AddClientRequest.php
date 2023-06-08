<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddClientRequest extends FormRequest
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
            // client_name-regex: allows for spaces and hyphens while also ensuring that the name only contains letters.
            'client_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',  
            'client_number' => 'required|numeric|digits:10|unique:clients',
            'client_email' => ['required ',
                'email:filter' ,
                'max:255',
                'unique:clients'],
            'status' => 'required | boolean',
        ];
    }

}
