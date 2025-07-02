<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => $this->first_name . ' ' . $this->last_name,
            'initials' => $this->initials,
            'email' => $this->email,
            'is_admin' => $this->is_admin,
            'is_me' => $request->user()->id === $this->id,
            'organization' => $this->organization,
            'projects' => ProjectResource::collection($this->whenLoaded('projects')),
            'accepted_at' => $this->whenPivotLoaded('project_user', function () {
                return $this->pivot->accepted_at;
            }),
            'created_at' => $this->created_at,
        ];
    }
}
