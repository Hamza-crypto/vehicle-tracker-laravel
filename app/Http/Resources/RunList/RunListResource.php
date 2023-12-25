<?php

namespace App\Http\Resources\RunList;

use Illuminate\Http\Resources\Json\JsonResource;

class RunListResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'item_number' => $this->item_number,
            'lot_number' => $this->lot_number,
            'claim_number' => $this->claim_number,
            'number_of_runs' => $this->number_of_runs,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
