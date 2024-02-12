<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        //"language_key": "en",
        //                "attributes":
        return [
            'language_key' => $this->language_key,
            'attributes' => json_decode($this->attributes)
        ];

    }
}
