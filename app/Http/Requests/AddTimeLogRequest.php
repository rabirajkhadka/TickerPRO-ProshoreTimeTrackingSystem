<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddTimeLogRequest extends FormRequest
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
        // regex: activity name must contain atleast one character or number
        return [
            'activity_name' => 'required|max:255|regex:/^.*[a-zA-Z0-9]+.*$/',
            'user_id' => 'required|integer|exists:users,id',
            'project_id' => 'required|integer|exists:projects,id',
            'billable' => 'required|boolean',
            'start_date' => 'required|date_format:Y-m-d',
            'started_time'=>'required|date_format:H:i:s'
        ];
    }

    /**
     * Custom message to be more specific
     *
     * @return void
     */
    public function messages()
    {
        return [
            'activity_name.regex' => 'Activity name must contain atleast one character or number'
        ];
        
    }
}
