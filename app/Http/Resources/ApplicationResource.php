<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company' => $this->company,
            'position' => $this->position,
            'location' => $this->location,
            'status' => $this->status,
            'applied_at' => $this->applied_at?->toDateString(),
            'next_followup_at' => $this->next_followup_at?->toDateString(),
            'notes' => $this->notes,
            'followups_count' => $this->whenCounted('followups'),
            'followups' => FollowupResource::collection($this->whenLoaded('followups')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
