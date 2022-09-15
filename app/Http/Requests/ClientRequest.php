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
            'client_number' => 'required|integer|max:255',
            'client_email' => 'required | email |max:255|unique:clients',
            'status' => 'required | boolean',
        ];
    }
}
