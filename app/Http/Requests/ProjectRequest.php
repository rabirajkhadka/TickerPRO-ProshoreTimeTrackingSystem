<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'project_name' => 'required',
            'client_id' => 'required | integer',
            'billable' => 'required | boolean',
            'status' => 'required | boolean',
            'project_color_code' => 'required',
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
            'project_name.required' => 'Project name required',
            'client_id.required' => 'Client id required',
            'billable.required' => 'Please enter if the activity is billable or not',
            'status.required' => 'Please enter if the status is active or not',
            'project_color_code.required' => 'Please give a color code to project',
        ];
    }
}
