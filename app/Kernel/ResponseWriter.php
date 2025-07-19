<?php

declare(strict_types=1);

namespace App\Kernel;

use App\Kernel\Http\Response;

class ResponseWriter
{
    private Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function write(): void
    {
        if (headers_sent()) {
            return;
        }

        http_response_code($this->response->getStatus());
        foreach ($this->response->getHeaders() as $header => $value) {
            header("$header: $value", false);
        }

        if ($this->response->getContent() !== null) {
            echo $this->response->getContent();
        }

        return;
    }
}
