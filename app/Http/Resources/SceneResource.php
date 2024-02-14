<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SceneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'scene_id' => $this->id,
            'name' => $this->name,
            'scene_attributes'=>$this->when($this->attributeCollection, new AttributeCollectionResource($this->attributeCollection))


        ];
    }
}
