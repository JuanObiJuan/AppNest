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
            'sort_by' => $this->sort_by,
            'name' => $this->name,
            'json_data'=>$this->json_data,
            'json_schema'=>$this->json_schema,
        ];
    }
}
