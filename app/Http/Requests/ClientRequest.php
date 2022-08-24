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
            'client_name' => 'required',
            'client_number' => 'required',
            'client_email' => 'required | email|unique:clients,client_email',
            'status' => 'required | boolean',
        ];
    }

    /*
     * Custom message for validation
     *
     * @return array
     * */
    public function messages()
    {
        return [
            'client_name.required' => 'Client name is required',
            'client_number.required' => 'Client number is required',
            'client_email.required' => 'Client email is required',
            'status.required' => 'Please enter if the status is active or not',
        ];
    }
}
