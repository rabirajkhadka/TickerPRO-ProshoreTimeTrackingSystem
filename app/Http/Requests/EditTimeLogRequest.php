<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditTimeLogRequest extends FormRequest
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
            'activity_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'user_id' => 'required | integer|exists:users,id',
            'project_id' => 'required | integer|exists:projects,id',
            'billable' => 'required | boolean',
            'start_time' => 'required | date_format:Y-m-d H:i:s',
            'end_time' => 'required | date_format:Y-m-d H:i:s|after:start_time',
        ];
    }
}
