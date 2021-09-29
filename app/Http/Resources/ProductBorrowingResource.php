<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductBorrowingResource extends JsonResource
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
            'id'               => $this->id,
            'borrowing_number' => $this->borrowing_number,
            $this->mergeWhen($this->products,[
                'products' => $this->products
            ])
        ];
    }
}
