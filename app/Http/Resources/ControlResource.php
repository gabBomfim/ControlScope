<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ControlResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'function' => $this->function,
            'category' => $this->category,
            'title' => $this->title,
            'description' => $this->description,
            'recommendation' => $this->recommendation,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'score' => $this->status->score(),
        ];
    }
}
