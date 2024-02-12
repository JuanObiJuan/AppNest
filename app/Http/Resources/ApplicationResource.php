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
            'id' => $this->id,
            'name' => $this->name,
            'organization' => $this->organization->name,
            'default_language' => $this->default_language,
            'app_attributes'=>$this->when($this->attributeCollection, new AttributeCollectionResource($this->attributeCollection)),
            'scenes'=> SceneResource::collection($this->whenLoaded('scenes')),
            'voices'=>  VoiceResource::collection($this->whenLoaded('voices')),
        ];
    }



}
