<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponder
{
    public function successResponse($data = null, $message = 'Success', $code = Response::HTTP_OK)
    {
        $status = 'success';
        return response()->json(compact('status', 'message', 'data'), $code)->header('Content-Type', 'application/json');
    }

    public function errorResponse($data = null, $message = 'Error', $code = Response::HTTP_BAD_REQUEST)
    {
        $status = 'error';
        return response()->json(compact('status', 'message', 'data'), $code)->header('Content-Type', 'application/json');
    }
}
