<?php

namespace App\Http\Requests;

use App\Rules\CheckHashedToken;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
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
            'name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'email' => ['required' ,
                ' email|max:255',
                'unique:users',
                'exists:invite_tokens,email'],
            'password' => [
                'required',
                'max:30',
                Password::min(6)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
                'confirmed'
            ],
            'token' => [
                'required',
                new CheckHashedToken($this->email)
            ]
        ];
    }
}
