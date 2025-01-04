<?php

namespace App\Exceptions\Handlers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HttpExceptionException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): Response
    {
        //
    }
}
