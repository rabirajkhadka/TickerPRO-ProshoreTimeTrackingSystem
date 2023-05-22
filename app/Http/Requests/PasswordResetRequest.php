<?php

namespace App\Http\Requests;

use App\Rules\CheckHashedPasswordValue;
use App\Rules\VerifyResetToken;
use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
            'email' => 'required|email|max:255',
            'token' => [
                'required',
                new VerifyResetToken($this->email)
            ],
            'password' => [
                'bail',
                'required',
                'min:6',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                'confirmed',
                new CheckHashedPasswordValue($this->email)
            ],
        ];
    }
}
