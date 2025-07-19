<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Kernel\Context;
use App\Kernel\ControllerInterface;
use App\Kernel\Http\Status;
use App\Kernel\ResponseWriter;

class HomeController implements ControllerInterface
{
    public function handle(Context $ctx): ResponseWriter
    {
        $response = $ctx->response->withStatus(Status::OK)->json([
            "message" => "Hello, World!",
            "timestamp" => time(),
        ]);

        return $response;
    }
}
