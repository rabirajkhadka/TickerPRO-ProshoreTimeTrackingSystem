<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'project_id'=>$this->id,
            'project_name'=>$this->project_name,
            'client_id'=>$this->client_id,
            'billable'=>$this->billable,
            'status'=>$this->status,
            'project_color_code'=>$this->project_color_code,
        ];
    }
}
