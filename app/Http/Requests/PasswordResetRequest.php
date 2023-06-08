<?php

namespace App\Http\Requests;

use App\Rules\CheckHashedPasswordValue;
use App\Rules\VerfiyResetToken;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


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
            'email' => ['required',
                'email:filter',
                'max:255',
                'exists:users,email'],
            'token' => ['required', new VerfiyResetToken($this->email)],
            'password' => [
                'required',
                'max:30',
                Password::min(6)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
                'confirmed',
                new CheckHashedPasswordValue($this->email)
            ],
        ];
    }

    public function messages()
    {
        return [
            'email.exists' => 'User not found. Please check your email address.'
        ];
        
    }
}
