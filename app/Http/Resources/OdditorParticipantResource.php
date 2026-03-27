<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OdditorParticipantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    
    public function toArray($request)
    {
        return [
            'name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone_no,
            'status' => $this->status,
            'location' => $this->location,
            'date_joined' => $this->created_at,
        ];
    }
}
