<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile_number' => $this->mobile_number,
            'location' => $this->location,
            'image_path' => asset('storage/' . $this->image_path),
            'is_verified' => $this->is_verified,
            'user_type' => $this->user_type,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
