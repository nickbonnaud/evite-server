<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InviteeRsvpResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'will_attend' => $this->will_attend,
            'number_attending' => $this->number_attending,
        ];
    }
}
