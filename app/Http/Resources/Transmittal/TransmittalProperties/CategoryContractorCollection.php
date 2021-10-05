<?php

namespace App\Http\Resources\Transmittal\TransmittalProperties;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryContractorCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
