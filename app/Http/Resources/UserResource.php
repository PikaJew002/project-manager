<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
            'name' => $this->name,
            'initials' => Str::initials($this->name),
            'email' => $this->email,
            'is_admin' => $this->is_admin,
            'is_me' => $request->user()->id === $this->id,
            'projects' => ProjectResource::collection($this->whenLoaded('projects')),
        ];
    }
}
