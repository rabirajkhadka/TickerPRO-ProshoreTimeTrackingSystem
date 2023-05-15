<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ClientResource;

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
            'client'=>ClientResource::collection($this->whenloaded('client')),
            'billable'=>$this->billable,
            'status'=>$this->status,
        ];
    }
}
