<?php

namespace App\Http\Resources;

use App\Models\AttributeCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description'=> $this->description,
            //'attribute_collection' => AttributeCollectionResource($this->whenLoaded('attributeCollection')),
            'voice_attributes'=>$this->when($this->attributeCollection, new AttributeCollectionResource($this->attributeCollection))
        ];
    }
}
