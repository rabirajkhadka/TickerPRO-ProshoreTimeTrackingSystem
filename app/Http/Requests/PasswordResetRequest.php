<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
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
    /**
     *  password-regex: 
     *       -> Has at least three characters.
     *       -> Contains at least one letter (lowercase or uppercase).
     *       -> Includes at least one digit.
     *       -> Includes at least one special characters from !$#%@.   
     *
     * @return void
     */
    public function rules()
    {
        return [
            'email' => 'required | email |max:255',
            'token' => 'required',
            'password' => ['required','min:6','regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%@]).*$/','confirmed'],
        ];
    }
}
