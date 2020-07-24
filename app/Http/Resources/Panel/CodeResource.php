<?php

namespace App\Http\Resources\Panel;

use Illuminate\Http\Resources\Json\JsonResource;

class CodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'code' => $this->code,
            'capacity' => $this->capacity,
            'enable' => $this->enable,
            'creator' => $this->creator->name,
            'modifier' => $this->modifier->name ?? null,
            'customers_count' => $this->customers_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
