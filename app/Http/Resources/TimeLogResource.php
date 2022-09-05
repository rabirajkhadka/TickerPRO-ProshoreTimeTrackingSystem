<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimeLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id'=>$this->id,
            'activity_name'=>$this->activity_name,
            'user_id'=>$this->user_id,
            'project_id'=>$this->project_id,
            'project_name'=>$this->Project->project_name,
            'billable'=>$this->billable,
            'start_time'=>$this->start_time,
            'end_time'=>$this->end_time
        ];
    }
}
