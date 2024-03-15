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
            'voice_id' => $this->id,
            'name' => $this->name,
            'description'=> $this->description,
            'json_data'=>$this->json_data,
            'json_schema'=>$this->json_schema
        ];
    }
}
