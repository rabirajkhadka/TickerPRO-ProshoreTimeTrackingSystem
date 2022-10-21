<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProjectResource;

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
            'project'=>new ProjectResource($this->project),
            'billable'=>$this->billable,
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
            'started_time'=>$this->started_time,
            'ended_time'=>$this->ended_time,
        ];
    }
}
