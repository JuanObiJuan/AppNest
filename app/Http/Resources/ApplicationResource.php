<?php

namespace App\Http\Resources;

use App\Models\AttributeCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'app_id' => $this->id,
            'name' => $this->name,
            'organization_id' => $this->organization->id,
            'default_language' => $this->default_language,
            'scenes'=> $this->whenLoaded('scenes', $this->scenes->pluck('id')),
            'voices'=> $this->whenLoaded('voices', $this->voices->pluck('id')),
            'json_data'=>$this->json_data,
            'json_schema'=>$this->json_schema,

        ];
    }



}
