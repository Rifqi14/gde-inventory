<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StockMovementResource;
use Illuminate\Http\Response;

class StockMovementController extends Controller
{
    public function read(Request $request)
    {         
        if(!$request){
            return response()->json([
                'status'    => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message'   => 'The given data was invalid.'
            ],Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new StockMovementResource($request);
    }
}
