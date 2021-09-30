<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ProductBorrowingException extends Exception
{
    public function __construct($message = 'The given data was invalid')
    {
        parent::__construct($message);
    }

    public function report()
    {
        # code...
    }

    public function render($request)
    {
        if($request->wantsJson()){
            return response()->json([
                'status'    =>  Response::HTTP_UNPROCESSABLE_ENTITY,
                'message'   => $this->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
