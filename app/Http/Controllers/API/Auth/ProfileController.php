<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ProfileController extends Controller
{
    public function __invoke(Request $request)
    {   
        return response()->json([
            'status' => Response::HTTP_OK,
            'data'   => $request->user('api')
        ], Response::HTTP_OK);
    }
    
}
