<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationInvitationResource extends JsonResource
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
            'organization_id' => $this->organization_id,
            'organization' => new OrganizationResource($this->whenLoaded('organization')),
            'invited_by' => $this->invited_by,
            'invitedBy' => new UserResource($this->whenLoaded('invitedBy')),
            'email' => $this->email,
            'token' => $this->token,
            'declined_at' => $this->declined_at,
            'created_at' => $this->created_at,
        ];
    }
}
