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
        return [
            'activity_name' => 'required',
            'user_id' => 'required | integer',
            'project_id' => 'required | integer|exists:projects,id',
            'billable' => 'required | boolean',
            'start_time' => 'required | date_format:Y-m-d H:i:s',
            'end_time' => 'required | date_format:Y-m-d H:i:s|after:start_time',
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
            'activity_name.required' => 'Activity name required',
            'project_id.required' => 'Please enter a valid project id',
            'billable.required' => 'Please enter if the activity is billable or not',
            'start_time.required' => 'Please enter starting time of the activity',
            'end_time.required' => 'Please enter ending time of the activity',
        ];
    }
}
