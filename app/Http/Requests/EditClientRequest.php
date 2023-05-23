<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditClientRequest extends FormRequest
{
 /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): Bool
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
            'client_name' => 'required|max:255|regex:/^[A-Za-z]+(?:\s[A-Za-z]+)+$/', //makes sure the name only accepts aplabetic characters and some specific name formats.
            'client_number' => 'required|numeric|digits:10',
            'status' => 'required | boolean',
        ];
    } 
}
