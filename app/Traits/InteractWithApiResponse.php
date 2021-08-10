<?php

namespace App\Traits;

use Illuminate\Http\Response;

/**
 * 
 */
trait InteractWithApiResponse
{
  public function success($code, $message, $data, $results = null)
	{
		$response['status']   = $code;
		$response['message']  = $message;
		$response['data']     = $data;
    $response['results']  = $results;
		
		return response()->json($response, $code);
	}

	public function error($message=null)
	{
		$response['status']   = Response::HTTP_UNPROCESSABLE_ENTITY;
		$response['message']  = $message ?: 'Terjadi kesalahan';

		return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
	}
}
