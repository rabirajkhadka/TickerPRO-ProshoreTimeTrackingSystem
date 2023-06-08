<?php

namespace App\Http\Requests;

use App\Rules\VerfiyResetToken;
use Illuminate\Foundation\Http\FormRequest;

class ValidateResetTokenRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|email|max:255|exists:password_resets,email',
            'token' => ['required', new VerfiyResetToken($this->email)],
        ];
    }
}
