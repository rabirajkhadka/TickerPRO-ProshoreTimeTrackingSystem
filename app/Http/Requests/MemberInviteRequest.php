<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberInviteRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required | email|unique:invite_tokens',
            'role_id' => 'required | integer',
            'user_id' => 'required | integer'
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
            'name.required' => 'User name required',
            'role_id.required' => 'A valid role id is required!',
            'user_id.required' => 'Admin user id required'
        ];
    }
}
