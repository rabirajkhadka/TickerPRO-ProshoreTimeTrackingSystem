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
            // validation for emails and roles
            'email' => 'required | email',
            'role_id' => 'required | array',
            'role_id.*' => 'required | integer'
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
            'role_id.required' => 'A valid role id is required!',
        ];
    }
}
