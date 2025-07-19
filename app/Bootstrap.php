<?php

declare(strict_types=1);

namespace App;

use App\Kernel\Kernel;
use App\Controllers\HomeController;

class Bootstrap
{
    public static function run(): void
    {
        $kernel = new Kernel();
        $router = $kernel->getRouter();

        $router->get("/", new HomeController());

        $kernel->handleRequest();
    }
}
