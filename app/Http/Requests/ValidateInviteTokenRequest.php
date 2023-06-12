<?php

namespace App\Http\Requests;

use App\Rules\CheckHashedToken;
use Illuminate\Foundation\Http\FormRequest;

class ValidateInviteTokenRequest extends FormRequest
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
            'email' => 'required|email|max:255|exists:invite_tokens,email',
            'token' => ['required', new CheckHashedToken($this->email)],
        ];
    }
}
