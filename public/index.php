<?php

declare(strict_types=1);

use App\Bootstrap;

// Autoload dependencies
require dirname(__DIR__, 1) . "/vendor/autoload.php";

Bootstrap::run();
