<?php

declare(strict_types=1);

namespace App\Kernel;

use App\Kernel\Http\Request;
use App\Kernel\Http\Response;

class Kernel
{
    private Context $ctx;
    private Router $router;

    public function __construct()
    {
        $request = Request::fromGlobals();
        $response = new Response();

        $this->ctx = new Context($request, $response);
        $this->router = new Router();
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function handleRequest(): void
    {
        $this->router->dispatch($this->ctx);
    }
}
