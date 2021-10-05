<?php

namespace App\Http\Resources\Transmittal\Outcoming;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class OutcomingCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'status'        => Response::HTTP_OK,
            'data'          => $this->collection
        ];
    }
}
