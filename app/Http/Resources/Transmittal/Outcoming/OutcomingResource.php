<?php

namespace App\Http\Resources\Transmittal\Outcoming;

use Illuminate\Http\Resources\Json\JsonResource;

class OutcomingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
