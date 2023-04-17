<?php

namespace App\Exceptions\JsonApi;

use Exception;
use Illuminate\Http\Request;
# use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response;

class BadRequestHttpException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): Response
    {
        return response()->json([
            'errors' => [
                [
                    'title' => 'Bad Request',
                    'detail' => $this->getMessage(),
                    'status' => '400'
                ]
            ]
        ], 400);
    }
}
