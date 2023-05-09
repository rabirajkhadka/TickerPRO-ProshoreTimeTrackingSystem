<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\UserRole;
use App\Rules\AssignedRoleRule;
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
                'required',
                'integer',
                'exists:roles,id',
                new AssignedRoleRule($this->email, $this->role_id),
            ]
        ];
    }
}
