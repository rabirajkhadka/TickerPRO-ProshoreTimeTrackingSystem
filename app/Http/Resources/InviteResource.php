<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InviteResource extends JsonResource
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
            'name'=> $this->name,
            'email'=> $this->email,
            'role'=> new RoleResource($this->role),
            'token'=> $this->token,
            'tokenExpires'=> $this->tokenExpires,
            'id'=> $this->id,
        ];
    }
}
