<?php

namespace App\Http\Requests;

use App\Rules\CheckUserRoleExistsRule;
use Illuminate\Foundation\Http\FormRequest;


class AssignRoleRequest extends FormRequest
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
            'email' => 'required | email| exists:users',
            'role_id' => [
                'required',
                'integer',
                'exists:roles,id',
                new CheckUserRoleExistsRule($this->email, $this->role_id),
            ]
        ];
    }
}
