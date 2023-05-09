<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class AssignRoleRequest extends FormRequest
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
            'email' => 'required | email |exists:users',
            'role_id' => [
                'required', 'integer', 'exists:roles,id',
            ]
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $existingRoles = User::whereEmail($this->email)->first()->roles->pluck('id');

            if ($existingRoles->contains($this->role_id)) {
                $validator->errors()->add('role_id', 'User is already assigned this role');
            }
        });
    }
}
