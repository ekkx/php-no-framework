<?php

declare(strict_types=1);

namespace App\Kernel;

use App\Kernel\Http\Request;
use App\Kernel\Http\Response;

class Context
{
    public readonly Request $request;
    public readonly Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}
