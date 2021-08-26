<?php

namespace App\Exceptions\DocumentExternal\Properties;

use Exception;
use Illuminate\Http\Response;

class SiteCodeException extends Exception
{
    public $status = 422;

    /**
     * Create a new exception instance.
     *
     * @param  string $message
     * @return void
     */
    public function __construct($message = 'The given data was invalid.', $status = 422)
    {
        parent::__construct($message, $status);
    }

    /**
     * Report the exception.
     *
     * @return void
     */
     public function report()
     {
        //
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'status'    => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message'   => $this->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
