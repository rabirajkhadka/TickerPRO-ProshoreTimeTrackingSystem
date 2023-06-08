<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimelogReportRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_ids' => 'required|array|exists:users,id',
            'user_ids.*' => 'integer',
            'project_ids' => ' array|exists:projects,id',
            'project_ids.*' => 'integer',
            'start_date' => ' required|date_format:Y-m-d',
            'end_date' => '  nullable|date_format:Y-m-d',
        ];
    }
}
