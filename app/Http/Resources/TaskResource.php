<?php

namespace App\Http\Resources;

use App\Enums\TaskProgress;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'task_id' => $this->task_id,
            'task' => new TaskResource($this->whenLoaded('task')),
            'name' => $this->name,
            'description' => $this->description,
            'due_at' => $this->due_at,
            'status' => TaskProgress::getState($this->resource),
            'priority' => $this->priority,
            'created_by' => $this->created_by,
            'createdBy' => new UserResource($this->whenLoaded('createdBy')),
            'assigned_to' => UserResource::collection($this->whenLoaded('users')),
            'projects' => ProjectResource::collection($this->whenLoaded('projects')),
            'buckets' => BucketResource::collection($this->whenLoaded('buckets')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'is_deleted' => $this->deleted_at ? true : false,
        ];
    }
}
