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
            'client_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255', //makes sure there are no numbers in the name and doesn't allow more than one space
            'client_number' => 'required|numeric|digits:10|regex:/^\d{10}$/', // makes sure the number is exactly 10 digits 
            'client_email' => 'required | email |max:255|unique:clients',
            'status' => 'required | boolean',
        ];
    }
}
