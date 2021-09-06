<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ProductException extends Exception
{
    public function __construct($message = 'The given data was invalid.')
    {
        parent::__construct($message);
    }

    public function render($request)
    {
        return response()->json([
            'status'  => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => $this->getMessage()
        ],Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
