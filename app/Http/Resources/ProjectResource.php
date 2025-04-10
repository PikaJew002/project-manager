<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'name' => $this->name,
            'initials' => $this->initials,
            'is_personal' => $this->is_personal,
            'administered_by' => $this->administered_by,
            'administeredBy' => new UserResource($this->whenLoaded('administeredBy')),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'buckets' => BucketResource::collection($this->whenLoaded('buckets')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
        ];
    }
}
