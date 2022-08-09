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
            'user_id' => 'required | integer',
            'project_id' => 'required | integer',
            'billable' => 'boolean',
            'start_time' => 'date_format:Y-m-d H:i:s',
            'end_time' => 'date_format:Y-m-d H:i:s',
            'activity_name' => 'string'
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
            'project_id.required' => 'Please enter a valid project id',
            'user_id.required' => 'Please enter a valid user id',
            'start_time' => 'Please enter starting time of the activity',
            'end_time' => 'Please enter ending time of the activity',
        ];
    }
}
