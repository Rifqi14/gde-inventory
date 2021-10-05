<?php

namespace App\Exceptions;

use Exception;
use illuminate\Http\Response;

class RegisterException extends Exception
{
    public function __construct($message = 'Register failed due to error.')
    {
        parent::__construct($message);
    }

    public function report()
    {
        # code...
    }

    public function render($request)
    {
        if($request->wantJson()){
            return response()->json([
                'status'    => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message'   => $this->getMessage(),
            ],Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
