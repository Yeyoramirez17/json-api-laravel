<?php

namespace App\Exceptions\JsonApi;

use Exception;
use Illuminate\Http\Request;
# use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response;

class NotFoundHttpException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): Response
    {
        $id = $request->input('data.id');

        $type = $request->input('data.type');

        return response()->json([
            'errors' => [
                [
                    'title' => 'Not Found',
                    'detail' => "No records found with the id '{$id}' in the '{$type}' resource.",
                    'status' => '404'
                ]
            ]
        ], 404);
    }
}
