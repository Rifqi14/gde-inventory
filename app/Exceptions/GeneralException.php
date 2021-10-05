<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class GeneralException extends Exception
{
    public function __construct($message = 'The given data is invalid.', $code = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        parent::__construct($message, $code);
    }

    public function report()
    {
        # code...
    }

    public function render($request)
    {
        if($request->wantsJson()){
            return response()->json([
                'status'    => $this->code,
                'message'   => $this->getMessage()
            ], $this->code);
        }
    }
}
