<?php

declare(strict_types=1);

namespace App\Kernel;

use App\Kernel\Context;
use App\Kernel\ResponseWriter;

interface ControllerInterface
{
    public function handle(Context $ctx): ResponseWriter;
}
